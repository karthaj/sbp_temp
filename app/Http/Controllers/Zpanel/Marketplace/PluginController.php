<?php

namespace Shopbox\Http\Controllers\Zpanel\Marketplace;

use Shopbox\Traits\Install;
use Illuminate\Http\Request;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Zpanel\Plugin;
use Shopbox\Models\Zpanel\PluginCategory;
use Shopbox\Models\Zpanel\Service;
use Shopbox\Models\Zpanel\Billing;
use Shopbox\Models\Zpanel\BillingItem;
use Shopbox\Models\Zpanel\BillingInvoice;
use Shopbox\Transformers\Marketplace\PluginTransformer;
use Shopbox\Http\Controllers\Controller;

class PluginController extends Controller
{
    use Install;

    public function index(Request $request)
    {
        $plugins = Plugin::where('publish', 1)->filter($request)->paginate(15);

        $categories = PluginCategory::where('active', 1)->get();

    	return view('zpanel.marketplace.plugins.index', compact('plugins', 'categories'));
    }

    public function update(PluginCategory $category, Request $request)
    {
        $plugins = $category->plugins()->where('status', 1)->filter($request)->paginate(15);

        $browse_category = $category;

        $categories = PluginCategory::where('active', 1)->get();

        return view('zpanel.marketplace.plugins.index', compact('plugins', 'browse_category', 'categories'));
    }

    public function refine(Request $request)
    {
        return redirect($request->sort_by);
    }

    public function show(Request $request, Plugin $plugin)
    {
        $plugin  = fractal()
                    ->item($plugin)
                    ->transformWith(new PluginTransformer)
                    ->toArray()['data'];

        return view('zpanel.marketplace.plugins.show', compact('plugin'));
    }

    public function store(Request $request)
    {
        $plugin = Plugin::where('publish', 1)->where('id', $request->plugin_id)->first();

        if(!$plugin) {
            abort(404);
        }

        if($plugin->plans->count() && !$plugin->plans->contains('plan_id', session('store')->plan_id)) {
            abort(404);
        }

        if($plugin->category->alias === 'payment' && (session('store')->payments->contains('plugin_id', $plugin->id)) || (session('store')->plugins->contains('plugin_id', $plugin->id))) {
            abort(404);
        }

        $service = $this->createService($request->tenant(), $plugin);
        $billing = $this->generateBill($request->tenant(), $service, $plugin->price);

        if(!(float) $plugin->price) {
            $billing->state = 1;
            $billing->save();
            $service->state = 1;
            $service->save();
            $this->generateInvoice($billing);
            $this->installPlugin($request->tenant(), $plugin);

            return redirect()->route('plugin.show', $plugin)->withSuccess($plugin->plugin_name.' installed successfully.');
        }

        return redirect()->route('store.checkout.index', $billing);

    }

    protected function createService (Store $store, Plugin $plugin)
    {
        $service = new Service;
        $service->store()->associate($store);
        $service->plugin()->associate($plugin);
        $service->name = $plugin->plugin_name.' Plugin';
        $service->ends_at = $store->expiry_date;
        $service->state = 2;
        $service->save();

        return $service;
    }

    protected function generateBill(Store $store, Service $service, $price)
    {
        $billing = new Billing;
        $billing->reference = generateBillRef();
        $billing->store()->associate($store);
        $billing->amount = $price;
        $billing->total_payable = $price;
        $billing->state = 2;
        $billing->save();

        $this->storeBillingItems($billing, $service);
        $this->storeBillingAddress($billing);

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

    protected function storeBillingItems (Billing $billing, Service $service) 
    {
        $item = new BillingItem;
        $item->billing()->associate($billing);
        $item->service()->associate($service);
        $item->amount = $billing->amount;
        $item->ends_at = $service->ends_at;
        $item->save();
    }

    protected function generateInvoice(Billing $billing) 
    {
        $invoice = new BillingInvoice;
        $invoice->store()->associate(session('store'));
        $invoice->billing()->associate($billing);
        $invoice->number = generateInvoiceID();
        $invoice->amount = $billing->total_payable;
        $invoice->payment_method = 'gift';
        $invoice->state = 1;
        $invoice->save();
    }

}
