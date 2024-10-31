<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Jenssegers\Agent\Agent;
use Shopbox\Models\Zpanel\Track;
use Modules\Product\Traits\StockTrait;
use Modules\Order\Http\Requests\Order\ReturnFormRequest;
use Modules\Order\Transformers\ReturnInvoiceTransformer;
use Shopbox\Models\Zpanel\StoreCredit;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderReturn;
use Modules\Order\Entities\OrderReturnState;
use Modules\Order\Entities\OrderReturnDetail;
use Modules\Discount\Entities\Discount;
use Modules\Product\Entities\StockTransferReason;
use Modules\Order\Emails\ReturnStatusEmail;
use Illuminate\Support\Facades\Mail;

class ReturnController extends Controller
{
    use StockTrait;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('order::orders.returns.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('order::orders.returns.create');
    }


    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request)
    {
        $order = $request->tenant()->orders()->where('invoice_number', $request->invoice_no)->first();

        if($order) {
            return  fractal()
                ->collection($order->details)
                ->transformWith(new ReturnInvoiceTransformer)
                ->toArray();
        }
        
        return response()->json([
            'data' => []
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(OrderReturn $return)
    {
        $states = OrderReturnState::all();
        $amount = 0;

        foreach($return->details as $item) {
            $amount += $item->orderDetail->product_price * $item->quantity;
        }

        $refundable_amount = $this->getRefundableAmount($return);

        return view('order::orders.returns.edit', compact('return', 'states', 'amount', 'refundable_amount'));
    }

    public function store(Request $request)
    {

        $order = $request->tenant()->orders()->where('invoice_number', $request->invoice_no)->first();

        if(!$order) {
            abort(450, 'Invalid invoice.');
        }

        $return = new OrderReturn;
        $return->return_id = OrderReturn::count() + 1;
        $return->store()->associate($request->tenant());
        $return->customer()->associate($order->customer);
        $return->order()->associate($order);
        $return->status()->associate(OrderReturnState::where('name', 'Pending')->first());
        $return->reason = 'instore return';
        $return->save();

        foreach ($request->selected as $value) {

            $detail = new OrderReturnDetail;
            $detail->orderReturn()->associate($return);
            $detail->orderDetail()->associate($value['id']);
            $detail->quantity = $value['qty_to_return'];
            $detail->save();
                
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(OrderReturn $return, ReturnFormRequest $request)
    {
        $amount = $request->amount;
        $return->status()->associate($request->status);
        $return->note = $request->seller_note;

        if(!empty($request->refund_amount)) {

            if($request->refund_amount) {
                $amount = $request->refund_amount;
            } 
        } 

        if($request->refund_method == 1) {
            $return->refund_method = 'store credit';
            $this->issueStoreCredit($return, $amount);
            $this->restockProducts($return->order);

        } else if($request->refund_method == 2) {
            $return->refund_method = 'discount code';
            $this->generateDiscountCode($return, $amount);
            $this->restockProducts($return->order);

        } else if($request->refund_method == 3) {
            $return->refund_method = 'credit cash';
            $this->restockProducts($return->order);

        }

        $return->save();

        Mail::to(session('store')->trans_email)->queue(new ReturnStatusEmail($return));
        Mail::to($return->customer)->queue(new ReturnStatusEmail($return));

        return redirect()->route('orders.return.index')->withSuccess('Return updated successfully!');
    }

    protected function restockProducts(Order $order)
    {
        $params['reason'] = StockTransferReason::where('name', 'Product return')->first();
        $params['entity'] = $order->store->onlineStore;
        $params['order_id'] = $order->id;
        $params['system_remark'] = $order->store->onlineStore->name.' <-';

        foreach($order->details as $item) {
            $stock = $item->product->stock;

            if($item->product_attribute) {
                $stock = $item->product_attribute->stock;
            }

            $balance = $this->incrementStock($stock, $item->product_quantity_refunded, $order->store->onlineStore);

            $this->saveTransfer($stock, $item->product_quantity_refunded, $balance, $params, true);
        }
    }

    protected function generateDiscountCode(OrderReturn $return, $amount)
    {
        $discount = new Discount;
        $discount->name = 'discount code for order '.$return->order->order_id;
        $discount->code = sprintf('DIS%1$dO%2$d', $return->order->customer_id, $return->order->order_id);
        $discount->customer()->associate($return->order->customer);
        $discount->reduction_amount = $amount;
        $discount->quantity = 1;
        $discount->quantity_per_user = 1;
        $discount->entire_order = 1;
        $discount->expires_at = date('Y-m-d H:i:s', strtotime('+1 year'));
        $discount->active = 1;
        $discount->save();

        $this->markOrderAsRefunded($return);
    }

    protected function issueStoreCredit(OrderReturn $return, $amount)
    {
        $available_credit = $return->customer->credits()->orderBy('id', 'desc')->first();

        $store_credit = new StoreCredit;
        $store_credit->store()->associate($return->store);
        $store_credit->customer()->associate($return->customer);
        $store_credit->invoice_number = $return->order->invoice->number;
        $store_credit->invoice_number = $return->order->invoice->number;
        $store_credit->credit = $amount;

        if($available_credit) {
            $store_credit->balance = $store_credit->credit + $available_credit->balance;
        } else {
            $store_credit->balance = $amount;
        }

        $store_credit->save();

        $this->markOrderAsRefunded($return);
    }

    protected function markOrderAsRefunded(OrderReturn $return)
    {   
        foreach ($return->details as $item) {
            $item->orderDetail()->update([
                'product_quantity_refunded' => $item->quantity
            ]);
        }
    }

    protected function getReturnedProductsWorth(OrderReturn $return) 
    {
        $total = 0;

        foreach($return->details as $item) {
            $total += $item->orderDetail->product_price * $item->quantity;
        }

        return $total;
    }

    protected function getRefundableAmount(OrderReturn $return)
    {
        $amount = 0;

        if($return->order->cart_discount) {

            $worth = $this->getReturnedProductsWorth($return);
            $total = $return->order->total_products - $worth;

            if($total >= $return->order->cart_discount->discount->minimum_amount) {

                foreach($return->details as $item) {
                
                    if($item->orderDetail->discounted_price > 0) {

                        $discount = $item->orderDetail->discount_amount / $item->orderDetail->product_quantity;
                        $amount += $item->orderDetail->product_price - $discount;

                    } else {

                        $amount += $item->orderDetail->product_price * $item->quantity;

                    }

                }

            } else {

                $amount += $worth - $return->order->total_discounts;
            }

        } else {

            foreach($return->details as $item) {
                $amount += $item->orderDetail->product_price * $item->quantity;
            }
        }

        return $amount;
    }
}
