<?php

namespace Shopbox\Http\Controllers\Zpanel\Setting;

use Illuminate\Http\Request;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Http\Requests\Store\ServiceRequest;
use Shopbox\Models\Zpanel\Service;
use Shopbox\Transformers\ServiceCollectionTransformer;
use Shopbox\Transformers\BillTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Barryvdh\DomPDF\PDF;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Zpanel\Billing;

class BillingController extends Controller
{
    protected $pdf;

    public function __construct(PDF $pdf)
    {
        $this->pdf = $pdf;
    }

    public function index(Request $request)
    {
        $store = $request->tenant();
        $services = $request->tenant()->services()->where('state', 1)->get();

        $records = fractal()
                        ->collection($services)
                        ->transformWith(new ServiceCollectionTransformer)
                        //->paginateWith(new IlluminatePaginatorAdapter($services))
                        ->toArray();

        $services = $records['data'];
    
        return view('zpanel.settings.billing.index', compact('store', 'services'));
    }

    public function getBills(Request $request)
    {
        if(!$request->ajax()) {
            return abort('404');
        }

        $records = $request->tenant()->billings()->where('state', 1)->orWhere('state', 0)->orderBy('created_at', 'desc')->paginate(10);

        $bills = fractal()
                        ->collection($records->getCollection())
                        ->transformWith(new BillTransformer)
                        ->paginateWith(new IlluminatePaginatorAdapter($records))
                        ->toArray();
        

        return response()->json(compact('bills'));
    }

    public function update(ServiceRequest $request) 
    {
        $service = $request->tenant()->services()->where('id', $request->id)->first();

        if(!$service) {
            return;
        }

        $service->recurring = 0;
        $service->save();
    }

    public function downloadBill(Billing $billing)
    {
        $this->pdf->loadView('zpanel.bills.pdf', compact('billing'));
        return $this->pdf->download('invoice.pdf');
    }

}
