<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>

    <table width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%"> 
        <tbody>
            <tr> 
                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 25px 0; text-align: center">         
                    <a href="{{ getStoreUrl($order->store) }}" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(187, 191, 195); font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white" target="_blank">
                        @if($order->store->setting->logo)
                        <img src="{{ getStoreUrl($order->store).'/stores/'.$order->store->domain.'/img/'.$order->store->setting->logo }}" width="100" alt="{{ $order->store->store_name }}" />
                        @else
                        {{ $order->store->store_name }} 
                        @endif       
                    </a>
                </td> 
            </tr> 
            <tr> 
                <td width="100%" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: rgb(255, 255, 255); border-bottom: 1px solid rgb(237, 239, 242); border-top: 1px solid rgb(237, 239, 242); margin: 0; padding: 0; width: 100%">
                    <table  align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: rgb(255, 255, 255); margin: 0 auto; padding: 0; width: 570px">
                        <tbody>
                            <tr> 
                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px">                                         
                                    <h1 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(47, 49, 51); font-size: 19px; font-weight: bold; margin-top: 0; text-align: left">Order Confirmation</h1>
                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">{{ $order->store->emails->where('slug', 'order-confirmation')->first()->email_body }}</p> 
                                    <h2 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(47, 49, 51); font-size: 16px; font-weight: bold; margin-top: 0; text-align: left">Order No: {{ $order->store->id }}-{{ $order->order_id }}</h2> 
                                    <h2 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(47, 49, 51); font-size: 16px; font-weight: bold; margin-top: 0; text-align: left">Order Date: {{ $order->created_at_tz->toFormattedDateString() }}</h2> 
                                    <div style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
                                        <table style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; width: 100%"> 
                                            <thead style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
                                                <tr> 
                                                    <th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px; text-align: left">Image</th> 
                                                    <th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px; text-align: left">Items</th> 
                                                    <th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px; text-align: right">Price</th> 
                                                </tr>
                                            </thead> 
                                            <tbody style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
                                            @foreach($order->details as $item)
                                            	<tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 18px; padding: 10px 0; text-align: left; border-bottom: 1px solid rgb(237, 239, 242);">
                                                        <img src="{{ getProductImage($item) }}" alt="{{ $item->product_name }}"  width="50" />
                                                    </td> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 18px; padding: 10px 0; text-align: left; border-bottom: 1px solid rgb(237, 239, 242);">
                                                        <strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">{{ $item->product_name }} × {{ $item->product_quantity }}</strong>
                                                    </td> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 18px; padding: 10px 0; text-align: right; border-bottom: 1px solid rgb(237, 239, 242);">
                                                        <strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">{{ $order->currency->iso_code }} {{ number_format($item->total_price, 2) }}</strong>
                                                    </td> 
                                                </tr> 
                                            @endforeach 
                                            </tbody> 
                                        </table> 
                                    </div> 

                                    <div style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
                                        <table style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; width: 100%">    <thead style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
                                                <tr> 
                                                    <th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px; text-align: right"></th> 
                                                    <th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px; text-align: right"></th> 
                                                </tr>
                                            </thead> 
                                            <tbody style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: right">Subtotal</td> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: right"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">{{ $order->currency->iso_code }} {{ number_format($order->total_products, 2) }}</strong>
                                                    </td> 
                                                </tr> 

                                                @if($order->total_discounts > 0)
                                                    <tr> 
                                                        <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: right">Discount</td> 
                                                        <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.1em; padding: 5px 0; text-align: right"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">({{ $order->currency->iso_code }} {{ number_format($order->total_discounts,2) }})</strong>
                                                        </td> 
                                                    </tr> 
                                                @endif
                                                @if($order->shipper)
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: right">Shipping</td> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: right"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
                                                        @if($order->total_shipping > 0)
                                                            {{ $order->currency->iso_code }} {{ number_format($order->total_shipping,2) }}
                                                        @else
                                                            Free
                                                        @endif
                                                    </strong>
                                                    </td> 
                                                </tr> 
                                                @endif
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: right">Tax</td> <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: right"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
                                                    	{{ $order->currency->iso_code }} {{ number_format($order->total_products_wt - $order->total_products, 2) }}
                                                    </strong>
                                                    </td> 
                                                </tr> 

                                                @if($order->surcharge > 0)
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: right">Surcharge</td> <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: right"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
                                                        {{ $order->currency->iso_code }} {{ number_format($order->surcharge, 2) }}
                                                    </strong>
                                                    </td> 
                                                </tr>
                                                @endif

                                                @if($order->store_credits > 0)
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: right">Store Credit</td> <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: right"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
                                                        ({{ $order->currency->iso_code }} {{ number_format($order->store_credits, 2) }})
                                                    </strong>
                                                    </td> 
                                                </tr>
                                                @endif
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 10px 0; text-align: right">Grandtotal
                                                    </td> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 10px 0; text-align: right"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">{{ $order->currency->iso_code }} {{ number_format($order->total_paid,2) }}</strong>
                                                    </td> 
                                                </tr> 
                                            </tbody> 
                                        </table> 
                                    </div> 
                                    <div style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
                                        <table style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; width: 100%">  <thead style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
                                            <tr> 
                                                <th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px; text-align: left"></th> 
                                                <th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px; text-align: left"></th> 
                                            </tr>
                                          </thead> 
                                          <tbody style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
                                            <tr> 
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 10px 0; text-align: left"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">Shipping address</strong></td> 
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 10px 0; text-align: left"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">Billing address</strong></td> 
                                            </tr> 
                                             
                                            <tr> 
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->shipping_address->firstname }} {{ $order->shipping_address->lastname }}</td> 
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->billing_address->firstname }} {{ $order->billing_address->lastname }}</td> 
                                            </tr> 
                                            <tr>
                                            	@if($order->shipping_address->company)
                                            		<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->shipping_address->company }}</td>
                                            	@endif 
                                                
                                                @if($order->billing_address->company)
                                                	<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->billing_address->company }}</td>
                                                @endif
                                            </tr> 
                                            <tr> 
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->shipping_address->address }}</td> 
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->billing_address->address }}</td> 
                                            </tr>
                                            <tr> 
                                            	@if($order->shipping_address->address2)
                                            		<td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->shipping_address->address2 }}</td> 
                                            	@endif

                                            	@if($order->billing_address->address2)
                                            		 <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->billing_address->address2 }}</td> 
                                            	@endif
                                            </tr> 
                                            <tr> 
                                            <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->shipping_address->city }} @if($order->shipping_address->state) {{ $order->shipping_address->state->name }} @endif @if($order->shipping_address->zip_code) {{ $order->shipping_address->zip_code }} @endif</td> 
                                            <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->billing_address->city }} @if($order->billing_address->state) {{ $order->billing_address->state->name }} @endif @if($order->billing_address->zip_code) {{ $order->billing_address->zip_code }} @endif</td> 
                                            </tr> 
                                            <tr> 
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->shipping_address->country->name }}</td> 
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->billing_address->country->name }}</td> 
                                            </tr> 
                                            <tr>
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; font-weight: bold; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->shipping_address->phone }}</td> 
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; font-weight: bold; line-height: 1.2em; padding: 5px 0; text-align: left">{{ $order->billing_address->phone }}</td> 
                                            </tr>
                                        </tbody> 
                                        </table> 
                                    </div> 
                                    <div  style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
                                        <table style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; width: 100%"> 
                                            <thead style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
                                                <tr> 
                                                    <th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px; text-align: left"></th> 
                                                    <th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px; text-align: left"></th> 
                                                </tr>
                                            </thead> 
                                            <tbody style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">Shipping method</strong></td> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">Payment method</strong></td> 
                                                </tr> 
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">
                                                    @if($order->shipper)
                                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">{{ $order->shipper->name }}</p>
                                                    @else
                                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">n/a</p>
                                                    @endif
                                                       
                                                    </td> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0; text-align: left">
                                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">{{ $order->payment_method }}</p>
                                                    </td> 
                                                </tr> 
                                            </tbody> 
                                        </table> 
                                    </div> 
                                    @if($order->payment_plugin === 'cashondelivery' && $order->shipper->carrier->shippingZone->cod)
                                    <div  style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
                                        <table style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; width: 100%"> 
                                            <thead style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
                                                <tr> 
                                                    <th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px"></th> 
                                                </tr>
                                            </thead> 
                                            <tbody style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">{{ $order->payment_method }}</strong></td> 
                                                </tr> 
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0"><p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">{{ $order->shipper->carrier->shippingZone->cod->remark }}</p></td> 
                                                </tr> 
                                            </tbody> 
                                        </table> 
                                    </div>
                                    @elseif($order->payment_plugin === 'banktransfer')
									<div  style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
                                        <table style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; width: 100%"> <thead style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
                                                <tr> 
                                                    <th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px"></th> 
                                                </tr>
                                            </thead> 
                                            <tbody style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">{{ $order->payment_method }}</strong></td> 
                                                </tr> 
                                                <tr> <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0">Account name: {{ array_has($config, 'BANK_TRANSFER_ACCOUNT_NAME') ? $config['BANK_TRANSFER_ACCOUNT_NAME'] : 'N/A' }}</td> 
                                                </tr> 
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0">Account number: {{ array_has($config, 'BANK_TRANSFER_ACCOUNT_NUMBER') ? $config['BANK_TRANSFER_ACCOUNT_NUMBER'] : 'N/A' }}</td> 
                                                </tr> 
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0">Bank name: {{ array_has($config, 'BANK_TRANSFER_BANK_NAME') ? $config['BANK_TRANSFER_BANK_NAME'] : 'N/A' }}</td> 
                                                </tr> 
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0">Bank branch: {{ array_has($config, 'BANK_TRANSFER_BANK_BRANCH') ? $config['BANK_TRANSFER_BANK_BRANCH'] : 'N/A' }}</td> 
                                                </tr> 
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0">Swift code: {{ array_has($config, 'BANK_TRANSFER_SWIFT_CODE') ? $config['BANK_TRANSFER_SWIFT_CODE'] : 'N/A' }}</td> 
                                                </tr> 
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0"></td> 
                                                </tr> 
                                            </tbody> 
                                        </table> 
                                    </div>
                                    @elseif($order->payment_plugin === 'payinstore')
                                    <div  style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
                                        <table style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto; width: 100%"> 
                                            <thead style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">
                                                <tr> 
                                                    <th style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-bottom: 1px solid rgb(237, 239, 242); padding-bottom: 8px"></th> 
                                                </tr>
                                            </thead> 
                                            <tbody style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box"> 
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0"><strong style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">{{ $order->payment_method }}</strong></td> 
                                                </tr> 
                                                @if(array_has($config, 'PAYINSTORE_PAYMENT_INSTRUCTION'))
                                                <tr> 
                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 15px; line-height: 1.2em; padding: 5px 0"><p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">{{ $config['PAYINSTORE_PAYMENT_INSTRUCTION'] }}</p></td> 
                                                </tr> 
                                                @endif
                                            </tbody> 
                                        </table> 
                                    </div>
                                    @endif 
 
                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: rgb(116, 120, 126); font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left">Thanks,<br> {{ $order->store->store_name }}</p>                                                                               
                                </td>                                 
                            </tr> 
                        </tbody>
                    </table> 
                </td>                     
            </tr> 
            <tr> 
                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box">         
                    <table align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto; padding: 0; text-align: center; width: 570px">
                        <tbody>
                            <tr> 
                                <td  align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px">                     
                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: rgb(174, 174, 174); font-size: 12px; text-align: center">© {{ date('Y') }} Shopbox. All rights reserved.</p> 
                                </td>             
                            </tr>
                        </tbody>
                    </table> 
                </td> 
            </tr> 
        </tbody>
    </table>
</body>
</html>
