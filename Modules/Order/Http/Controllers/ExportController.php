<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Shopbox\Traits\OrderExport;
use Modules\Order\Entities\Order;
use Illuminate\Routing\Controller;
use Modules\Order\Events\Export\StartExport;

class ExportController extends Controller
{
    use OrderExport;

    public function export(Request $request)
    {
        $orders = Order::where('store_id', $request->tenant()->id)
                        ->where('status', 1)
                        ->with(['customer', 'state', 'shipper', 'cart_discount.discount', 'payment', 'cart', 'billing_address', 'shipping_address', 'details.taxes.tax'])
                        ->filterBy($request)->get();

        if(!$orders->count()) {
            return response()->json([
                'status' => 'failed'
            ], 422);
        }

        if($orders->count() > 50) {
            event(new StartExport($request->tenant(), $orders));
            return response()->json([
                'status' => 'success'
            ]);
        }

        $this->startExport($orders, $request->tenant()->id);

    }

    public function download(Request $request)
    {
        return response()->download(storage_path('exports/'.$request->tenant()->id.'/orders.csv'))->deleteFileAfterSend(true);
    }

}
