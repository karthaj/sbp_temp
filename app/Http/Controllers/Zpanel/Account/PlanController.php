<?php

namespace Shopbox\Http\Controllers\Zpanel\Account;

use Carbon\Carbon;
use Shopbox\Models\Zpanel\Service;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Zpanel\Billing;
use Shopbox\Models\Zpanel\BillingItem;
use Shopbox\Models\Zpanel\BillingInvoice;
use Shopbox\Models\Zpanel\Plan;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function index()
    {
    	$plans = Plan::where('active', 1)->where('slug', '<>', 'trial')->where('type', session('store')->plan->type)->get();

        return view('zpanel.checkout.plans.show', compact('plans'));
    }

    public function show(Plan $plan, Request $request)
    {
        $current_plan = $request->tenant()->plan;
        if($current_plan->id === $plan->id) {
            return redirect()->route('plan.change.index');
        }
        $errors = $this->validatePlanChecklist($request->tenant(), $plan);

    	return view('zpanel.checkout.plans.compare', compact('plan', 'current_plan', 'errors'));
    }

    public function store(Request $request, Plan $plan)
    {
        if($request->tenant()->plan->slug !== 'trial') {

            $errors = $this->validatePlanChecklist($request->tenant(), $plan);

            if($errors->count()) {
                return back();
            }


        }
        
        $service = $this->createService($request->tenant(), $plan, $request->duration);

        if($request->tenant()->plan->slug !== 'trial') {

            $billing = $this->generateBill($request->tenant(), $service, $plan);

        } else {

            $billing = $this->generateNewBill($request->tenant(), $service, $request->duration);

        }

        return redirect()->route('store.checkout.index', $billing);

    }

    protected function generateNewBill(Store $store, Service $service, $duration) 
    {
        $billing = new Billing;
        $billing->reference = generateBillRef();
        $billing->store()->associate($store);

        if($duration == 1) {
            $billing->amount = $service->plan->monthly;
            $billing->total_payable = $service->plan->monthly;
        } else if($duration == 3) {
            $billing->amount = $service->plan->quaterly;
            $billing->total_payable = $service->plan->quaterly;
        } else if($duration == 6) {
            $billing->amount = $service->plan->half_monthly;
            $billing->total_payable = $service->plan->half_monthly;
        } else if($duration == 12) {
            $billing->amount = $service->plan->yearly;
            $billing->total_payable = $service->plan->yearly;
        }

        $billing->state = 2;
        $billing->type = 2;
        $billing->save();

        $this->storeBillingAddress($billing);

        if($billing->items->count()) {
            $billing->items()->delete();
        }

        $this->storeBillingItems($billing, $service);

        return $billing;
    }

    protected function storeBillingAddress(Billing $billing)
    {
        $billing->address()->create([
            'company' => $billing->store->company,
            'address1' => $billing->store->address1,
            'address2' => $billing->store->address2,
            'country' => $billing->store->country->name,
            'state' => $billing->store->state ? $billing->store->state->iso_code : null,
            'city' => $billing->store->city,
            'postcode' => $billing->store->postcode,
            'phone' => $billing->store->phone
        ]);
    }

    protected function validatePlanChecklist(Store $store, Plan $plan)
    {

        $errors = collect([]);
        $products_count = $store->products->count();
        $plan_products_limit = $plan->products_limit;
        $staff_accounts = $store->users->count();
        $plan_accounts_limit = $plan->accounts_limit;

        if($plan_products_limit != 0 && $products_count > $plan_products_limit) {

            $errors->push('You have '.$products_count.' products in your store. Plan '.$plan->name.' has a product limit of '.$plan_products_limit.'.');

        }

        if($staff_accounts > $plan_accounts_limit) {

            $errors->push('You have '.$staff_accounts.' staff accounts in your store. Plan '.$plan->name.' has a account limit of '.$plan_accounts_limit.'.');

        }

       return $errors;

    }

    protected function generateBill(Store $store, Service $service, Plan $plan)
    {
        $current_plan = $store->services()->where('plan_id', $store->plan_id)->where('state', 1)->first();
        
        if(!$current_plan) {
            return;
        }

        $paid = BillingItem::where('service_id', $current_plan->id)->latest()->first();

        $current_plan_amount = $paid->amount;
        //$current_paid_amount = $current_plan->amount;
        $current_plan_start_date = $current_plan->updated_at;
        $current_plan_end_date = $current_plan->ends_at;

        $frequency = getFrequency($current_plan_start_date, $current_plan_end_date);

        $full_duration = $current_plan_start_date->diffInSeconds($current_plan_end_date);
        $current_plan_per_second_rate = $current_plan_amount / $full_duration;
        $new_date = Carbon::now();
        $current_used_duration = $current_plan_start_date->diffInSeconds($new_date);
        $current_unused_duration = $full_duration - $current_used_duration;
        $current_unused_amount = $current_plan_per_second_rate * $current_unused_duration;
        $new_plan_amount = $this->getAmount($plan, $frequency);
        $new_plan_per_second_rate = $new_plan_amount / $full_duration;
        $new_plan_unused_amount = $new_plan_per_second_rate * $current_unused_duration;
        $reimburse = 0;
        if($new_plan_unused_amount > $current_unused_amount) {
            $reimburse = $current_unused_amount;
        } else if($new_plan_unused_amount < $current_unused_amount) {
            $reimburse = $new_plan_unused_amount;
        }
		

        $billing = new Billing;
        $billing->reference = generateBillRef();
        $billing->store()->associate($store);
        $billing->amount = $new_plan_unused_amount;
        $billing->reimburse = $reimburse;
        $billing->total_payable = $new_plan_unused_amount - $reimburse;
        $billing->state = 2;
        $billing->type = 2;
        $billing->save();

        $this->storeBillingAddress($billing);

        if($billing->items->count()) {
            $billing->items()->delete();
        }

        $this->storeBillingItems($billing, $service);

        return $billing;
    }

    protected function storeBillingItems (Billing $billing, Service $service) 
    {
        $item = new BillingItem;
        $item->billing()->associate($billing);
        $item->service()->associate($service);
        $item->amount = $billing->amount;
        $item->ends_at = $service->ends_at;
        $item->save();

    }

    protected function createService (Store $store, Plan $plan, $duration = null)
    {
        $dt = Carbon::now();
        $frequency = 1;

        if($duration) {
            $frequency = $duration;
        } else {
            $current_plan = $store->services()->where('plan_id', $store->plan_id)->where('state', 1)->first();
            if($current_plan) {
                $frequency = getFrequency($current_plan->updated_at, $current_plan->ends_at);
            }
        }

        $service = new Service;
        $service->store()->associate($store);
        $service->plan()->associate($plan);
        $service->name = $store->plan->name.' plan to '.$plan->name.' plan';
        $service->ends_at =  $dt->addMonths($frequency);
        $service->recurring = 1;
        $service->state = 2;
        $service->save();

        return $service;
    }

    protected function getAmount(Plan $plan, $frequency)
    {
        if($frequency === 0 || $frequency === 1) {
            return $plan->monthly;
        } else if($frequency === 3) {
            return $plan->quaterly;
        } else if($frequency === 6) {
            return $plan->half_monthly;
        } else if($frequency === 12) {
            return $plan->yealy;
        }
    }

}
