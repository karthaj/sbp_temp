<?php

namespace Modules\Order\Http\Controllers;

use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderState;
use Modules\Order\Entities\OrderHistory;
use Shopbox\Models\Zpanel\Currency;
use Shopbox\Models\Zpanel\Plugin;
use Modules\Product\Traits\StockTrait;
use Modules\Order\Http\Requests\Order\OrderRequest;
use Modules\Order\Http\Requests\Order\OrderStatusRequest;
use Modules\Order\Emails\OrderStatusEmail;
use Modules\Order\Events\Shipment\ReadyForShipment;

class OrderController extends Controller
{
    use StockTrait;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function index()
    {
        $states = OrderState::get(['id', 'name', 'slug']);
        $payments = $this->getStorePayments();
        return view('order::orders.index', compact('states', 'payments'));
    }

    protected function getStorePayments()
    {
        $data = [];

        foreach (session('store')->payments as $key => $payment) {
            $data[$key]['id'] = $payment->id;
            $data[$key]['alias'] = $payment->alias;
            $data[$key]['name'] = $payment->display_name;
        }

        return json_encode($data);
    }
  
    public function updateStatus(OrderStatusRequest $request, Order $order)
    {
        if($order->archived) {
            return $this->edit($order);
        }

        $status = OrderState::where('slug', $request->status)->first();

        if($order->state->slug === 'cancelled' && $status->slug === 'completed') {
            $this->restockOrder($order);
            $order->archived = 1;
        } else if(($order->state->slug !== 'cancelled' && $status->slug === 'refund') || $status->slug === 'cancelled') {
            $this->unstockOrder($order);
        } else if($status->slug === 'completed') {
            $order->archived = 1;
        }

        $order->state()->associate($status);
        $order->save();

        $order_history = new OrderHistory;
        $order_history->user()->associate($request->user());
        $order_history->state()->associate($order->state);
        $order_history->order()->associate($order);
        $order_history->save();

        if($status->send_email) {
            Mail::to($order->customer->customerEmail)->queue(new OrderStatusEmail($order));
        }

        if($order->payment_plugin == 'shopboxpay' && $status->slug === 'refund') {
            Mail::to('refunds@shopbox.lk')->queue(new OrderStatusEmail($order));
        }

        if($status->slug === 'ready-for-shipment' && $order->shipper->carrier && $order->shipper->carrier->email) {
            event(new ReadyForShipment($order, $order->shipper->carrier->email));
        }

        return $this->edit($order);
    }   

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Order $order)
    {
        $state = OrderState::where('slug', 'processing')->first();

        if($order->state->slug === 'pending' && $state) {

            $order->state()->associate($state);
            $order->save();

            $order_history = new OrderHistory;
            $order_history->user()->associate(auth()->user());
            $order_history->state()->associate($order->state);
            $order_history->order()->associate($order);
            $order_history->save();

            if($state->send_email) {
                Mail::to($order->customer->customerEmail)->queue(new OrderStatusEmail($order));
            }
            
        }

        if($order->shipper) {
            $statuses = OrderState::whereNotIn('slug', ['incomplete', 'payment-error'])->get();
        } else {
            $statuses = OrderState::whereNotIn('slug', ['ready-for-shipment', 'shipped', 'payment-error', 'incomplete'])->get();
        }

        $currencies = Currency::where('status', 1)->get();
        $manual_payment = 0;

        if(Plugin::where('alias', $order->payment_plugin)->count()) {
            $manual_payment = (bool) Plugin::where('alias', $order->payment_plugin)->first()->manual_payment;
        }

        return view('order::orders.edit', compact('order', 'statuses', 'currencies', 'manual_payment'));
    }

 
    public function update(Request $request, Order $order)
    {
        if(empty($request->track_no)) {
            return;
        }
        
        $order->shipper()->update([
            'tracking_number' => $request->track_no
        ]);

        return response()->json([
            'data' => 'Tracking number added successfully.'
        ]);
        
    }

  
    public function updatePayment(OrderRequest $request, Order $order)
    {
        if(!$order) {
            return;
        }

        $order->payment()->update([
            'trans_currency' => $request->currency,
            'trans_amount' => $request->amount,
            'transaction_id' => $request->transaction_id,
        ]);

    
        $order->total_real_paid = $request->amount;
        $order->save();

        return response()->json([
            'data' => 'Payment added successfully.',
            'order_amt' => $request->currency.' '.$request->amount,
            'status' => 'received'
        ]);
    }
}
