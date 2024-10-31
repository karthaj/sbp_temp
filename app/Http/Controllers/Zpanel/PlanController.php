<?php

namespace Shopbox\Http\Controllers\Zpanel;

use Carbon\Carbon;
use Shopbox\Models\Zpanel\Billing;
use Shopbox\Models\Zpanel\BillingItem;
use Shopbox\Models\Zpanel\Service;
use Shopbox\Models\Zpanel\BillingInvoice;
use Shopbox\Models\Zpanel\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Shopbox\Http\Controllers\Controller;

class PlanController extends Controller
{
    public function index()
    {
    	$plans = Plan::where('active', 1)->where('slug', '<>', 'trial')->where('type', session('store')->plan->type)->get();

    	return view('zpanel.checkout.plans.index', compact('plans'));
    }

    protected function createService($plan_id, $period)
    {
        $duration = explode('|', $period);
        $dt = Carbon::now();
        $plan = Plan::find($plan_id);

        if(!$plan) {
            return;
        }
        
        $service = new Service;
        $service->store()->associate(session('store'));
        $service->plan()->associate($plan);
        $service->name = $plan->name.' plan';
        $service->ends_at = $dt->addMonths($duration[1]);
        $service->recurring = 1; 
        $service->state = 2;
        $service->save(); 

        return $service;
    }

    protected function generateBill($service, $period)
    {
        $billing = new Billing;
        $duration = explode('|', $period);
        $billing->reference = generateBillRef();
        $billing->store()->associate(session('store'));

        if($duration[1] == 1) {
            $billing->amount = $service->plan->monthly;
            $billing->total_payable = $service->plan->monthly;
        } else if($duration[1] == 3) {
            $billing->amount = $service->plan->quaterly;
            $billing->total_payable = $service->plan->quaterly;
        } else if($duration[1] == 6) {
            $billing->amount = $service->plan->half_monthly;
            $billing->total_payable = $service->plan->half_monthly;
        } else if($duration[1] == 12) {
            $billing->amount = $service->plan->yearly;
            $billing->total_payable = $service->plan->yearly;
        }

        $billing->state = 2;
        $billing->save();

        $this->storeBillingAddress($billing);

        return $billing;
    }

    protected function storeBillingAddress(Billing $billing)
    {
        $billing->address()->create([
            'company' => $billing->store->company ?: $billing->store->store_name,
            'address1' => $billing->store->address1,
            'address2' => $billing->store->address2,
            'country' => $billing->store->country->name,
            'state' => $billing->store->state ? $billing->store->state->iso_code : null,
            'city' => $billing->store->city,
            'postcode' => $billing->store->postcode,
            'phone' => $billing->store->phone
        ]);
    }

    protected function storeBillingItems(Billing $billing, Service $service, $duration)
    {
        $duration = explode('|', $duration);
        $item = new BillingItem;
        $item->billing()->associate($billing);
        $item->service()->associate($service);
        $item->quantity = $duration[1];
        $item->amount = $billing->amount;
        $item->ends_at = $service->ends_at;
        $item->save();
    }

    public function store(Request $request)
    {
    	$dt = Carbon::now();

        $service = $this->createService($request->plan, $request->period);

        if(!$service) {
            return;
        }

        $billing = $this->generateBill($service, $request->period);

        if($billing->items->count()) {

            $billing->items()->delete();
        }

        $this->storeBillingItems($billing, $service, $request->period);

    	return redirect()->route('store.checkout.index', $billing);
    }

    public function show()
    {
        $duration = 1;
        $plan = session('store')->plan;

        $service = session('store')->services()->where('plan_id', session('store')->plan_id)->where('state', 1)->first();

        if($service)
        {
            $duration = getFrequency($service->updated_at, $service->ends_at);
        }

        return view('zpanel.checkout.plans.renew', compact('plan', 'duration'));
    }

    public function update(Plan $plan, Request $request)
    {
        $this->validate($request, [
            'duration' => 'required|'.Rule::in([12, 6, 3, 1]),
        ],[
            'duration.in' => 'Invalid duration selected. Reload the page and try again.'
        ]);

        $dt = Carbon::now();

        $billing = session('store')->billings->where('state', 0)->first();

        if($billing) {
            if($billing->created_at->diffInDays(Carbon::now()) <= 7) {
                $dt = session('store')->expiry_date;
            } 
            if($billing->created_at->subDay()->toDateString() != session('store')->expiry_date) {
                $billing->delete();
            } else {
                $billing = new Billing;
                $billing->reference = generateBillRef();
                $billing->state = 2;
                $billing->type = 1;
            }
        } else {
            $billing = new Billing;
            $billing->reference = generateBillRef();
            $billing->state = 2;
            $billing->type = 1;
        }

        $service = session('store')->services()->where('plan_id', $plan->id)->where('state', 1)->first();

        if(!$service && session('store')->services()->where('plan_id', $plan->id)->latest()->count()) {
            $service = session('store')->services()->where('plan_id', $plan->id)->latest()->first();
            $service->update([
                'name' => $plan->name
            ]);
        } else if($service) {
            $service->timestamps = false;
            $service->update([
                'name' => $plan->name.' plan'
            ]);
        }

        $billing->store()->associate(session('store'));

        if($request->duration == 1) {
            $billing->amount = $plan->monthly;
            $billing->total_payable = $plan->monthly;
        } else if($request->duration == 3) {
            $billing->amount = $plan->quaterly;
            $billing->total_payable = $plan->quaterly;
        } else if($request->duration == 6) {
            $billing->amount = $plan->half_monthly;
            $billing->total_payable = $plan->half_monthly;
        } else if($request->duration == 12) {
            $billing->amount = $plan->yearly;
            $billing->total_payable = $plan->yearly;
        }

        $billing->save();

        $this->storeBillingAddress($billing);

        if($billing->items->count()) {
            $billing->items()->delete();
        }

        $item = new BillingItem;
        $item->billing()->associate($billing);
        $item->service()->associate($service);
        $item->quantity = $request->duration;
        $item->amount = $billing->amount;
        $item->ends_at = $dt->addMonths($request->duration);
        $item->save();

        return redirect()->route('store.checkout.index', $billing);

    }
    
}
