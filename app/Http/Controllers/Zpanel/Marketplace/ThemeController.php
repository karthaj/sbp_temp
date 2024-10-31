<?php

namespace Shopbox\Http\Controllers\Zpanel\Marketplace;

use Shopbox\Traits\Install;
use Illuminate\Http\Request;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Front\Theme;
use Shopbox\Models\Front\Industry;
use Shopbox\Models\Zpanel\Service;
use Shopbox\Models\Zpanel\Billing;
use Shopbox\Models\Zpanel\BillingItem;
use Shopbox\Models\Zpanel\BillingInvoice;
use Shopbox\Transformers\Marketplace\ThemeTransformer;
use Shopbox\Http\Controllers\Controller;

class ThemeController extends Controller
{
    use Install;

    public function index(Request $request)
    {
        $paginated_themes = Theme::where('status', 1)->with(['industries'])->filter($request)->paginate(15);
        $themes =  fractal()
                    ->collection($paginated_themes->getCollection())
                    ->transformWith(new ThemeTransformer)
                    ->toArray()['data'];

        if($request->ajax()) {
            return view('zpanel.marketplace.themes._themes', compact('paginated_themes', 'themes'));
        }

        $industries = Industry::where('status', 1)->get();

    	return view('zpanel.marketplace.themes.index', compact('paginated_themes', 'themes', 'industries'));
    }

    public function show(Request $request, Theme $theme)
    {

        $theme  = fractal()
                    ->item($theme)
                    ->transformWith(new ThemeTransformer)
                    ->toArray()['data'];

        return view('zpanel.marketplace.themes.show', compact('theme'));
    }

    public function store(Request $request) 
    {

        $theme = Theme::where('alias', $request->theme_id)->first();

        if(!$theme) {
            return;
        }

        if($request->tenant()->themes->contains('theme_id', $theme->id)) {
            return redirect()->route('theme.index');
        }

        $service = $this->createService($request->tenant(), $theme);

        $billing = $this->generateBill($request->tenant(), $service, $theme->price);

        if(!(float) $theme->price) {
            $billing->state = 1;
            $billing->save();
            $service->state = 1;
            $service->save();
            $this->generateInvoice($billing);
            $this->installTheme($request->tenant(), $theme);

            return redirect()->route('theme.index')->withSuccess($theme->theme_name.' added successfully.');
        }

        return redirect()->route('store.checkout.index', $billing);
    }

    protected function createService (Store $store, Theme $theme)
    {        
        $service = new Service;
        $service->store()->associate($store);
        $service->theme()->associate($theme);
        $service->name = $theme->theme_name.' Template';
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
