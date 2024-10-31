<?php

namespace Shopbox\Http\Controllers\Front;

use Cookie;
use Module;
use Modules\Customer\Entities\Customer;
use Shopbox\Events\Auth\CustomerSignedUp;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Zpanel\Track;
use Shopbox\Models\Zpanel\StoreCredit;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Mail;
use Modules\Product\Traits\StockTrait;
use Shopbox\Traits\Discountable;
use Shopbox\Models\Zpanel\Plugin;
use Shopbox\Models\Zpanel\PluginCategory;
use Shopbox\Mail\Order\OrderConfirmation;
use Modules\Product\Entities\Cart;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderDetail;
use Modules\Order\Entities\OrderState;
use Modules\Order\Entities\OrderHistory;
use Modules\Order\Entities\OrderDetailTax;
use Modules\Order\Entities\OrderCarrier;
use Modules\Order\Entities\OrderCartDiscount;
use Modules\Order\Entities\OrderInvoice;
use Modules\Order\Entities\OrderPayment;
use Modules\Order\Entities\OrderInvoiceTax;
use Modules\Order\Entities\OrderMessage;
use Modules\Product\Entities\StockTransferReason;
use Shopbox\Traits\Taxable;
use Shopbox\Traits\Stock;
use Shopbox\Transformers\CheckoutTransformer;
use Shopbox\Transformers\Cart\ProductTransformer;
use Shopbox\Http\Requests\Order\OrderFormRequest;
use Intervention\Image\ImageManager;

class OrderController extends Controller
{
    use Taxable, StockTrait, Discountable, Stock;

    protected $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
        $this->agent = new Agent();
    }

    public function createOrder(Cart $cart, Request $request)
    {
        $checkout = fractal()
                    ->item($cart)
                    ->transformWith(new CheckoutTransformer)
                    ->toArray()['data'];

        $order = $this->store($cart, $request, $checkout);

        $url = $this->processPayment($order);

        if(!$url) {
            $cookie = Cookie::forget('cart', '/', config('session.domain')); 
            $url = route('checkout.order.confirmation', $order);
            return response()->json(compact('url'))->cookie($cookie);
        }

        return response()->json(compact('url'));
        
        
    }

    protected function processPayment(Order $order)
    {
        $payment = PluginCategory::where('alias', 'payment')->first();

        $url = '';

        if($payment->plugins->where('manual_payment', 0)->contains('alias', $order->payment_plugin)) {
            
            if($order->payment_plugin === 'shopboxpay' || $order->payment_plugin === 'hnb') {

                $url = route('connect.'.$order->payment_plugin, $order);
            } 

        } else {
            Mail::to(session('store')->trans_email)->queue(new OrderConfirmation($order));
            Mail::to($order->customer->customerEmail)->queue(new OrderConfirmation($order));
        
            $checkout_session = json_decode($order->cart->checkout_session_data, true);
            $checkout_session['checkout-payment-step']['step_is_complete'] = true;
            $checkout_session['checkout-payment-step']['step_is_reachable'] = true;
            $order->cart->checkout_session_data = json_encode($checkout_session);
            $order->cart->stock_reserved = 0;
            $order->cart->save();

            $status = OrderState::where('slug', 'pending')->first();
            $order->state()->associate($status);
            $order->status = 1;
            $order->save();

            $this->saveOrderStatus($order, $status);
            $this->restock($order->cart);
            $this->adjustStocks($order);
            
        }

        return $url;
    }

    protected function store(Cart $cart, Request $request, $checkout)
    {
        if($checkout['order_id'] && session('store')->orders()->where('status', 0)->where('order_id', $checkout['order_id'])->count()) {
            $order = session('store')->orders()->where('status', 0)->where('order_id', $checkout['order_id'])->first();
        } else {
            $order = new Order;
            $order->order_id = generateOrderID(session('store'));
            $order->reference = str_random(32);
        }

        $order->store()->associate(session('store'));
        $order->customer()->associate($cart->customer);
        $order->cart()->associate($cart);
        $order->currency()->associate($cart->currency);
        $order->shipping_address()->associate($cart->delivery_address);
        $order->billing_address()->associate($cart->invoice_address);
        $order->state()->associate(OrderState::where('slug', 'incomplete')->first());
        $order->order_source = 'online';
        $order->total_discounts = $checkout['cart']['total_discount'];
        $order->total_paid = $checkout['grand_total'];
        $order->total_paid_tax_incl = $checkout['subtotal'] + $checkout['tax']['amount'];
        $order->total_paid_tax_excl = $checkout['subtotal'];
        $order->total_products = $checkout['subtotal'];
        $order->total_products_wt = $checkout['subtotal'] + $checkout['tax']['amount'];

        if(array_has($checkout['consignment'], 'rate')) {
            $order->total_shipping = $checkout['consignment']['rate'];
            $order->total_shipping_tax_incl = $checkout['consignment']['rate'];
            $order->total_shipping_tax_excl = $checkout['consignment']['rate'];
        }

        $order->surcharge = $checkout['surcharge'];

        if($request->payment_method && session('store')->payments()->where('alias', $request->payment_method)->count()) {
            $payment = session('store')->payments()->where('alias', $request->payment_method)->first();
            $order->payment_plugin = $payment->plugin->alias;
            $order->payment_method = $payment->display_name;
        } else if($order->store_credits === $order->total_paid) {
            $order->payment_plugin = 'storecredit';
            $order->payment_method = 'Store Credit';
        } elseif($order->total_discounts === $order->total_paid) {
            $order->payment_plugin = 'discount';
            $order->payment_method = 'Discount';
        }

        $order->created_at_tz = $order->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
        $order->updated_at_tz = $order->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
        $order->save();

        $this->generateOrderInvoice($order);

        if(auth()->check() && $request->store_credits > 0 && $request->store_credits <= $checkout['customer']['store_credits']) {
            $order->store_credits = $request->store_credits;
            $this->saveCreditStatement($order);
        }
        $this->saveOrderDiscount($cart, $order);
        $this->saveOrderDetail($cart, $order, $checkout['tax']['amount']);
        $this->saveOrderCarrier($order, $cart);
        $this->saveOrderMessage($order);
        
        
        $plugin = Plugin::where('alias', $request->payment_method)->first();
        
        $this->saveOrderPayment($order);

        return $order;

    }

    protected function saveCreditStatement(Order $order)
    {   
        $available_credit = $order->customer->credits()->orderBy('id', 'desc')->first();

        if(!$available_credit) {
            return;
        }

        $credit = new StoreCredit;
        $credit->store()->associate($order->store);
        $credit->customer()->associate($order->customer);
        $credit->invoice_number = $order->invoice_number;
        $credit->debit = $order->store_credits;
        $credit->balance = $available_credit->balance - $credit->debit;
        $credit->remarks = 'Redeemed for order '.$order->order_id;
        $credit->created_by = 'system';
        $credit->updated_by = 'system';
        $credit->browser =  $this->agent->browser();
        $credit->platform = $this->agent->platform();
        $credit->ip_address = request()->ip();
        $credit->created_at_tz = $credit->freshTimestamp()->timezone($order->store->setting->timezone->timezone);
        $credit->updated_at_tz = $credit->freshTimestamp()->timezone($order->store->setting->timezone->timezone);
        $credit->save();
    }

    protected function saveOrderPayment(Order $order)
    {
        if(OrderPayment::where('order_id', $order->id)->count()) {
            $payment = OrderPayment::where('order_id', $order->id)->first();
        } else {
            $payment = new OrderPayment;
        }

        $payment->order()->associate($order);
        $payment->trans_currency = $order->currency->iso_code;
        $payment->trans_amount = $order->total_paid;
        $payment->order_currency = $order->currency->iso_code;
        $payment->order_amount = $order->total_paid;
        $payment->payment_method = $order->payment_method;
        $payment->created_at_tz = $order->freshTimestamp()->timezone($order->store->setting->timezone->timezone);
        $payment->created_at = $order->freshTimestamp();
        $payment->save();
    }

    protected function generateOrderInvoice(Order $order)
    {
        if(OrderInvoice::where('order_id', $order->id)->count()) {
            $invoice = OrderInvoice::where('order_id', $order->id)->first();
        } else {
            $invoice = new OrderInvoice;
        }

        $invoice->order()->associate($order);
        if($order->total_discounts) {
            $invoice->total_discount = $order->total_discounts;
        }
        $invoice->total_paid_tax_excl = $order->total_paid_tax_excl;
        $invoice->total_paid_tax_incl = $order->total_paid_tax_incl;
        $invoice->total_products = $order->total_products;
        $invoice->total_products_wt = $order->total_products_wt;
        $invoice->total_shipping_tax_excl = $order->total_shipping_tax_excl;
        $invoice->total_shipping_tax_incl = $order->total_shipping_tax_incl;
        $invoice->created_at_tz = $invoice->freshTimestamp()->timezone($order->store->setting->timezone->timezone);
        $invoice->created_at = $invoice->freshTimestamp();
        $invoice->save();

        $invoice->number = $invoice->id;
        $invoice->save();

        $order->invoice_number = $invoice->number;
        $order->invoice_date = $invoice->created_at_tz;
        $order->save();

        $invoice->taxes()->delete();
        $this->saveOrderInvoiceTax($order, $invoice);
    }

    protected function saveOrderDiscount(Cart $cart, Order $order)
    {
    
        if($cart->discounts->count()) {

            foreach($cart->discounts as $discount) {

                if(OrderCartDiscount::where('order_id', $order->id)->where('discount_id', $discount->discount_id)->count()) {
                    $cart_discount = OrderCartDiscount::where('order_id', $order->id)->where('discount_id', $discount->discount_id)->first();
                } else {
                    $cart_discount = new OrderCartDiscount;
                }

                $cart_discount->order()->associate($order);
                $cart_discount->discount()->associate($discount->discount_id);
                $cart_discount->invoice()->associate($order->invoice);
                $cart_discount->name = $discount->discount->name;
                $cart_discount->value = $order->total_discounts;
                $cart_discount->save();
            } 
           
        }
    }

    protected function saveOrderCarrier(Order $order, Cart $cart)
    {
        if($cart->carrier) {

            if(OrderCarrier::where('order_id', $order->id)->count()) {
                $order_carrier = OrderCarrier::where('order_id', $order->id)->first();
            } else {
                $order_carrier = new OrderCarrier;
            }
            
            $order_carrier->order()->associate($order);
            $order_carrier->carrier()->associate($cart->carrier->carrier_id);
            $order_carrier->name = $cart->carrier->name;
            $order_carrier->weight = $order->details->sum('product_weight');
            $order_carrier->shipping_cost_tax_excl = $order->total_shipping_tax_excl;
            $order_carrier->shipping_cost_tax_incl = $order->total_shipping_tax_incl;
            $order_carrier->created_at_tz = $order_carrier->freshTimestamp()->timezone($order->store->setting->timezone->timezone);
            $order_carrier->updated_at_tz = $order_carrier->freshTimestamp()->timezone($order->store->setting->timezone->timezone);
            $order_carrier->save();

        } else if(OrderCarrier::where('order_id', $order->id)->count()) {
            OrderCarrier::where('order_id', $order->id)->delete();
        }
    }

    protected function saveOrderMessage(Order $order)
    {
        if($order->cart->message) {

            if(OrderMessage::where('order_id', $order->id)->count()) {
                $message = OrderMessage::where('order_id', $order->id)->first();
            } else {
                $message = new OrderMessage;
            }

            $message->customer()->associate($order->customer);
            $message->order()->associate($order);
            $message->message = $order->cart->message;
            $message->created_at_tz = $message->freshTimestamp()->timezone($order->store->setting->timezone->timezone);
            $message->created_at = $message->freshTimestamp();
            $message->save();
        } else {
            OrderMessage::where('order_id', $order->id)->delete();
        }
    }

    protected function saveOrderStatus(Order $order, OrderState $status) 
    {
        $order_history = new OrderHistory;
        $order_history->order()->associate($order);
        $order_history->state()->associate($status);
        $order_history->created_at_tz = $order_history->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
        $order_history->updated_at_tz = $order_history->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
        $order_history->save();
    }

    protected function saveOrderDetail(Cart $cart, Order $order, $tax)
    {
        $items = fractal()->collection($cart->items)->transformWith(new ProductTransformer)->toArray()['data'];

        OrderDetail::where('order_id', $order->id)->delete();
    
        foreach ($items as $item) {

            $order_detail = new OrderDetail;
            $order_detail->order()->associate($order);
            $order_detail->invoice()->associate($order->invoice);
            $order_detail->store()->associate($order->store);
            try {
              $order_detail->image = (string) $this->imageManager->make($item['image'])->encode('data-url');
            }
            catch(Exception $e) {
              echo 'Message: ' .$e->getMessage();
            }
            $order_detail->product()->associate($item['product_id']);
            $order_detail->product_attribute()->associate($item['variant_id']);
            $order_detail->product_name = $item['name'];
            $order_detail->product_quantity = $item['quantity'];
            $order_detail->product_price = $item['sale_price'] ?: $item['selling_price'];
            $order_detail->product_sku = $item['sku'];
            $order_detail->product_barcode = $order_detail->product->barcode;
            $order_detail->product_isbn = $order_detail->product->isbn;
            $order_detail->product_upc = $order_detail->product->upc;
            $order_detail->product_weight = $item['weight'];
            $order_detail->unit_price = $item['sale_price'] ?: $item['selling_price'];
            $order_detail->total_price = $item['line_price'];
            $order_detail->original_product_price = $item['selling_price'];
            $order_detail->original_cost_price = $order_detail->product->cost_price;
            $order_detail->save();

            // if($tax != 0) {
            //     $this->saveOrderTaxDetails($order_detail, $item['quantity'], $tax);
            // }
        }
    }

    protected function saveOrderTaxDetails(OrderDetail $order_detail, $product_quantity, $sales_tax) 
    {
        $tax_rule = $this->getTaxRule($order_detail->order->cart);
        if($tax_rule->taxZone->taxes->count()) {
            foreach($tax_rule->taxZone->taxes as $tax) {
                $order_detail_tax = new OrderDetailTax;
                $order_detail_tax->order()->associate($order_detail);
                $order_detail_tax->tax()->associate($tax);
                $order_detail_tax->rate = $tax->rates()->where('tax_class_id', $order_detail->product->tax_class_id)->first()->rate * 100;
                $order_detail_tax->unit_amount = $sales_tax / $product_quantity;
                $order_detail_tax->total_amount = $sales_tax;
                $order_detail_tax->save();
            }
        }
    }

    protected function saveOrderInvoiceTax(Order $order, OrderInvoice $invoice)
    {
        $taxes = $this->getTaxes($this->getTaxRule($order->cart), $order->cart);
        
        if(count($taxes)) {
            foreach($taxes as $tax) {
                $order_invoice_tax = new OrderInvoiceTax;
                $order_invoice_tax->invoice()->associate($invoice);
                $order_invoice_tax->name = $tax['name'];
                $order_invoice_tax->amount = $tax['amount'];
                $order_invoice_tax->save();
            }
        }
    }


    public function show(Request $request, Order $order)
    {
        $configs = session('store')->configurations;
        $configs = $configs->pluck('value', 'name');
        $configs = $configs->all();
        $currency = $order->currency->iso_code;

        return view('layouts.order-confirmation', compact('order', 'configs', 'currency'));
    }

    public function createCustomer(Order $order, Request $request)
    {
        $this->validate($request, [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if(Customer::where('is_guest', 0)->where('email', $order->customerEmail)->count()) {
            return back()->withError('Email already taken.');
        }

        $order->customer->update([
            'password' => bcrypt($request->password),
            'is_guest' => 0
        ]);

        event(new CustomerSignedUp($user, getStoreUrl($order->store)));

        return back()->withSuccess('Your account has been created! Please check your email for an activation link.');

    }

}
