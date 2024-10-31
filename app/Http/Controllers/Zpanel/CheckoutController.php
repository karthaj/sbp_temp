<?php

namespace Shopbox\Http\Controllers\Zpanel;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Shopbox\Traits\Install;
use Shopbox\Models\Zpanel\Plan;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Zpanel\Billing;
use Shopbox\Models\Zpanel\BillingInvoice;
use Shopbox\Models\Zpanel\Service;
use Shopbox\Models\Zpanel\StorePayment;
use Shopbox\Models\Front\StoreTheme;
use Shopbox\Models\Zpanel\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Shopbox\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    use Install;

    public function index(Billing $billing)
    {
        $discount = '';

        if($billing->discount_id) {
            $discount = Discount::whereNull('store_id')->where('id', $billing->discount_id)->first();
        }

        $user = session('store')->users()->where(function ($query) {
                        $query->join('users', 'users.id', '=', 'store_users.user_id')->where('users.master', 1);
                    })->first();

        $data = config('services.shopboxpay.key').config('services.shopboxpay.secret').$billing->id.$billing->total_payable * 100;
        $signature = base64_encode(pack('H*', sha1($data)));
        
    	return view('zpanel.checkout.index', compact('billing', 'discount', 'user', 'signature'));
    }

    public function update(Billing $billing)
    {
        $invoice = new BillingInvoice;
        $invoice->store()->associate(session('store'));
        $invoice->billing()->associate($billing);
        $invoice->number = generateInvoiceID();
        $invoice->amount = $billing->total_payable;
        $invoice->payment_method = 'Bank Transfer';
    	$invoice->save();

        $message = 'Order placed successfully. Email your credit slip to example@shopbox.lk.Shopbox will verify your payment to complete your purchase.';

    	return view('zpanel.checkout.success', compact('billing', 'message'));
    }

    public function placeOrder(Billing $billing)
    {   
        $payment_method = 'N/A';
        if($billing->discount_id) {
            $payment_method = 'coupon';
        }
        $invoice = new BillingInvoice;
        $invoice->store()->associate(session('store'));
        $invoice->billing()->associate($billing);
        $invoice->number = generateInvoiceID();
        $invoice->amount = $billing->total_payable;
        $invoice->payment_method = $payment_method;
        $invoice->state = 1;
        $invoice->save();

        $this->refreshStore($billing);

        $billing->state = 1;
        $billing->save();

        $message = 'Thank you for your purchase.';

        return view('zpanel.checkout.success', compact('billing', 'message'));
    }

    public function discount(Request $request, Billing $billing)
    {
        $this->validate($request, [
            'discount' => 'required|validate_discount:'.$billing->id
        ], [
            'discount.validate_discount' => 'discount code expired or invalid.'
        ]);

    	$discount = Discount::whereNull('store_id')->where('code', $request->discount)->first();
        $billing->discount()->associate($discount->id);
        $discount_amount = $this->calculateDiscount($discount, $billing->total_payable);
        $billing->discount_amount = $discount_amount;
        $billing->total_payable = $billing->total_payable - $discount_amount;
        $billing->save();

    	return redirect()->route('store.checkout.index', $billing);
 
    }

    public function destroy(Billing $billing)
    {
        $billing->discount_id = null;
        $billing->discount_amount = 0;
        $billing->total_payable = $billing->amount - 0 - $billing->reimburse;
        $billing->save();

        return redirect()->route('store.checkout.index', $billing);
    }

    public function response(Request $request)
    {
    	$response = $request->response;
    	$billing = session('store')->billings()->where('id', $response['data']['order_id'])->first();

        if(!$billing) {
            $response['data']['status'] = 0;
            $response['data']['status_desc'] = 'Billing id not found.';
        }

    	if($response['data']['status']) {

            $hash = config('services.shopboxpay.key').config('services.shopboxpay.secret').$response['data']['order_id'].$response['data']['transaction_id'];
            $signature = base64_encode(pack('H*', sha1($hash)));

            if($signature !== $response['data']['signature']) {
                $response['data']['status'] = 0;
                $response['data']['status_desc'] = 'Invalid signature.';

                return view('zpanel.checkout.response', compact('billing', 'response'));
            }

    	}

    	return view('zpanel.checkout.response', compact('billing', 'response'));
    }

    public function clientResponse(Request $request)
    {
        $response = $request->response;
        $billing = Billing::where('id', $response['data']['order_id'])->first();

        if(!$billing) {
            Log::critical('SB internal response: Billing id '.$response['data']['order_id'].' not found.');
            exit(0);
        }

        if($response['data']['status']) {

            $hash = config('services.shopboxpay.key').config('services.shopboxpay.secret').$response['data']['order_id'].$response['data']['transaction_id'];
            $signature = base64_encode(pack('H*', sha1($hash)));

            if($signature !== $response['data']['signature']) {
                Log::critical('SB internal response: Invalid signature.');
                exit(0);
            }

            $invoice = new BillingInvoice;
            $invoice->store()->associate($billing->store);
            $invoice->billing()->associate($billing);
            $invoice->number = generateInvoiceID();
            $invoice->amount = $billing->total_payable;
            $invoice->payment_method = 'shopbox pay';
            $invoice->state = 1;
            $invoice->save();
        
            $this->refreshStore($billing);

            $billing->state = 1;
            $billing->save();
        }
    }

    protected function refreshStore(Billing $billing)
    {

        foreach($billing->items as $item) {

            if($item->service->plan_id) {
                
                if($billing->type === 1) {
                    // Package renew
                    $billing->store->services()->where('state', 1)->whereNull('theme_id')->update([
                        'ends_at' =>  $item->ends_at
                    ]);

                    $this->updateStorePlan($billing->store, $item->ends_at, $item->service->plan_id);

                }  elseif($billing->type === 2) {
                    // Package change
                    $billing->store->services()->where('state', 1)->where('plan_id', $billing->store->plan_id)->update([
                        'ends_at' => Carbon::now()->toDateTimeString(),
                        'state' => 0,
                        'recurring' =>  0
                    ]);

                    $item->service()->update([
                        'state' => 1
                    ]);

                    $this->removeNonEligiblePlugins($billing->store, $item->service->plan);
                    $this->removeStaffAccess($billing->store, $item->service->plan);
                    $this->updateStorePlan($billing->store, $item->ends_at, $item->service->plan_id);
                    $this->installPlanPlugins($billing->store, $item->service->plan);
                    $this->updateIPGRates($billing->store, $item->service->plan);

                } else {

                    $item->service()->update([
                        'state' => 1
                    ]);

                    $this->updateStorePlan($billing->store, $item->ends_at, $item->service->plan_id);
                    $this->installPlanPlugins($billing->store, $item->service->plan);
                }

            } elseif($item->service->plugin_id) {

                $this->installPlugin($billing->store, $item->service->plugin);

                $item->service()->update([
                    'state' => 1
                ]);

            } elseif($item->service->theme_id) {

                $this->installTheme($billing->store, $item->service->theme);

                $item->service()->update([
                    'state' => 1
                ]);
            }

        }
    	
    }

    protected function updateIPGRates(Store $store, Plan $plan)
    {
        $shopbox = $store->payments()->where('shopbox_ipg', 1)->first();

        if($shopbox) {
            $shopbox->payments()->update([
                'tdr_rate' => $plan->tdr_rate
            ]);
        }

    }

    protected function removeNonEligiblePlugins(Store $store, Plan $plan)
    {
        $store_permissions = $store->plan->permissions()->whereNotNull('plugin_id')->get()->unique('plugin_id');
        $plan_permissions = $plan->permissions()->whereNotNull('plugin_id')->get()->unique('plugin_id');

        $permissions = $store_permissions->diff($plan_permissions);

        if($permissions->count()) {

            foreach($permissions as $permission) {

                if($permission->plugin->category && $permission->plugin->category->alias === 'payment') {

                    $store->payments()->where('plugin_id', $permission->plugin_id)->delete();

                } else {

                    $store->plugins()->where('plugin_id', $permission->plugin_id)->delete();

                }
            }   

        }

        $store_permissions = $store->plan->permissions->pluck('id');
        $plan_permissions = $plan->permissions->pluck('id');

        $permissions = $store_permissions->diff($plan_permissions);

        if($permissions->count()) {

            $store->permissions()->detach($permissions->all());

        }
        
    }

    protected function installPlanPlugins(Store $store, Plan $plan)
    {   
        $permissions = $plan->permissions()->whereNotNull('plugin_id')->get()->unique('plugin_id');
        foreach ($permissions as $permission) {
            
            if($permission->plugin->is_core === 0) {
                $this->installPlugin($store, $permission->plugin);
            }   

        }
    }

    protected function updateStorePlan(Store $store, $ends_at, $plan_id)
    {
		$store->expiry_date = $ends_at;
        $store->plan()->associate($plan_id);
        $store->active = 1;
		$store->save();
    }

    protected function removeStaffAccess(Store $store, Plan $plan)
    {

        $store_permissions = $store->plan->permissions->pluck('id');
        $plan_permissions = $plan->permissions->pluck('id');

        $permissions = $store_permissions->diff($plan_permissions);

        if($permissions->count()) {
            foreach($store->users as $user) {
                $user->permissions()->detach($permissions->all());
            }   
        }
    }

    protected function calculateDiscount(Discount $discount, $amount)
    {
    	$discount_amount = 0;

    	if($discount->reduction_percent != 0 && $discount->reduction_amount == 0) {

            $discount_amount = $amount / 100 * $discount->reduction_percent;

            if($discount_amount > $amount) {

                $discount_amount = $amount;

            }


        } else if($discount->reduction_amount != 0 && $discount->reduction_percent == 0) {

            $discount_amount = $discount->reduction_amount;

            if($discount_amount > $amount) {

                $discount_amount = $amount;

            }

        }

        return $discount_amount;
    }

}
