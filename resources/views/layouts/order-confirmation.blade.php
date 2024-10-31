<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    @if($order->store->setting->favicon)
    <link rel="apple-touch-icon" href="{{ asset('stores').'/'.$order->store->domain.'/img/'.$order->store->setting->favicon }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('stores').'/'.$order->store->domain.'/img/'.$order->store->setting->favicon }}" />
    @endif
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />

    <title>{{ $order->store->store_name }} - Order Confirmation</title>
    <!-- Bootstrap core CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link class="main-stylesheet" href="{{ asset('assets/css/pages.css') }}" rel="stylesheet" type="text/css" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('assets/css/checkout.css') }}" rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="app" class="container mt-5">
        <div class="row">
            <div class="col-lg-7 col-md-12 col-sm-12">
                @if($order->store->setting->logo)
                <div class="col-4 mb-4 mx-auto">
                    <a href="{{ getStoreUrl($order->store) }}">
                        <img src="{{ asset('stores/'.$order->store->domain.'/img/'.$order->store->setting->logo) }}" alt="{{ $order->store->store_name }}" class="img-fluid">
                    </a>
                </div>
                    
                @else
                <a href="{{ getStoreUrl($order->store) }}">
                    <h3 class="font-weight-normal text-center">{{ $order->store->store_name }}</h3>
                </a>
                @endif
                <hr>
                <ul v-if="checkout.cart" class="list-group d-md-block d-lg-none">
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                       <i class="fa fa-shopping-cart fa-2x pr-3"></i>
                       <a href="#" class="order-summary-toggle" data-toggle="collapse" data-target="#orderSummary" aria-expanded="false" aria-controls="orderSummary">Order summary</a> 
                    </span>
                    <span class="font-weight-bold">{{ $currency }} {{ number_format($order->total_paid, 2) }}</span>
                  </li>
                </ul>

                @include('layouts.partials.order._cart_sm')

                <div>
                   <span>Order No:{{ $order->store_id }}-{{ $order->order_id }}</span> 
                    <h3 class="my-0 font-weight-normal">Thank you {{ $order->customer->firstname.' '.$order->customer->lastname }}!</h3>
                </div>

                <div class="list-group mb-3">
                    <div class="list-group-item">
                        <h4 class="m-0 font-weight-normal">Your order is confirmed</h4>
                        <p>
                        We've accepted your order, and we're getting it ready. An email has been sent to your mail address {{ $order->customer->email }} containing information about your purchase.
                      </p>
                    </div>
                </div>
                @if($order->payment_plugin === 'cashondelivery' && $order->shipper->carrier->shippingZone->cod)
                <div class="list-group mb-3">
                    <div class="list-group-item">
                        <h5 class="font-weight-normal">{{ $order->payment_method }}</h5>
                        <p>{{ $order->shipper->carrier->shippingZone->cod->remark }} </p>
                    </div>
                </div>
                @elseif($order->payment_plugin === 'banktransfer')
                <div class="list-group mb-3">
                    <div class="list-group-item">
                        <h5 class="font-weight-normal">{{ $order->payment_method }}</h5>
                        <p>
                            Account Name: {{ array_has($configs, 'BANK_TRANSFER_ACCOUNT_NAME') ? $configs['BANK_TRANSFER_ACCOUNT_NAME'] : 'N/A' }} <br>
                            Account Number: {{ array_has($configs, 'BANK_TRANSFER_ACCOUNT_NUMBER') ? $configs['BANK_TRANSFER_ACCOUNT_NUMBER'] : 'N/A' }} <br>
                            Bank Name: {{ array_has($configs, 'BANK_TRANSFER_BANK_NAME') ? $configs['BANK_TRANSFER_BANK_NAME'] : 'N/A' }} <br>
                            Bank Branch: {{ array_has($configs, 'BANK_TRANSFER_BANK_BRANCH') ? $configs['BANK_TRANSFER_BANK_BRANCH'] : 'N/A' }} <br>
                            @if(array_has($configs, 'BANK_TRANSFER_SWIFT_CODE') && $configs['BANK_TRANSFER_SWIFT_CODE'] != '')
                            Swift Code: {{ $configs['BANK_TRANSFER_SWIFT_CODE'] }}
                            @endif
                        </p>
                    </div>
                </div>
                @elseif($order->payment_plugin === 'payinstore' && array_has($configs, 'PAYINSTORE_PAYMENT_INSTRUCTION'))
                <div class="list-group mb-3">
                    <div class="list-group-item">
                        <h5 class="font-weight-normal">{{ $order->payment_method }}</h5>
                        @if(array_has($configs, 'PAYINSTORE_PAYMENT_INSTRUCTION'))
                        <p>{{  $configs['PAYINSTORE_PAYMENT_INSTRUCTION'] }} </p>
                        @endif
                    </div>
                </div>
                @endif

                <div class="list-group mb-3">
                    <div class="list-group-item">
                        <h4 class="m-0 font-weight-normal">Customer information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="font-weight-normal">Shipping address</h5>
                                <address class="address">{{ $order->shipping_address->firstname.' '.$order->shipping_address->lastname }}
                                    <br>{{ $order->shipping_address->address }} <br>
                                    @if($order->shipping_address->address2)
                                        {{ $order->shipping_address->address2 }} <br>
                                    @endif
                                    {{ $order->shipping_address->city }} <br>
                                    @if($order->shipping_address->zip_code)
                                        {{ $order->shipping_address->zip_code }} <br>
                                    @endif
                                    {{ $order->shipping_address->country->name }} <br>
                                    {{ $order->shipping_address->phone }}
                                </address>
                                <h5 class="font-weight-normal">Shipping method</h5>
                                @if($order->shipper)
                                    <p>{{ title_case($order->shipper->name) }}</p>
                                @else
                                    <p>n/a</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="font-weight-normal">Billing address</h5>
                                <address class="address">{{ $order->billing_address->firstname.' '.$order->billing_address->lastname }}
                                <br>{{ $order->billing_address->address }} <br>
                                @if($order->billing_address->address2)
                                    {{ $order->billing_address->address2 }} <br>
                                @endif
                                {{ $order->billing_address->city }} <br>
                                @if($order->billing_address->zip_code)
                                    {{ $order->billing_address->zip_code }} <br>
                                @endif
                                {{ $order->billing_address->country->name }} <br>
                                {{ $order->billing_address->phone }}
                                </address>
                                <h5 class="font-weight-normal">Payment method</h5>
                                <p>{{ $order->payment_method }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.partials.order._create_account')
                <a href="/" class="float-md-right mt-3 mb-5 btn btn-info" role="button">
                  Continue shopping
                </a>
            </div>
            
             @include('layouts.partials.order._cart')

        </div>
    </div>

    <script src="{{ asset('assets/plugins/jquery/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>