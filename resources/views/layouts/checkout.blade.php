<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, shrink-to-fit=no">
    <meta name="description" content="">
    @if(session('store')->setting->favicon)
    <link rel="apple-touch-icon" href="{{ asset('stores').'/'.session('store')->domain.'/img/'.session('store')->setting->favicon }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('stores').'/'.session('store')->domain.'/img/'.session('store')->setting->favicon }}" />
    @endif
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />

    <title>{{ title_case($store->store_name) }} - Checkout</title>
    <!-- Bootstrap core CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/fonts/line-icons/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
    <link class="main-stylesheet" href="{{ asset('assets/css/pages.css') }}" rel="stylesheet" type="text/css" />  
    <!-- Custom styles for this template -->
    <link href="{{ asset('assets/css/checkout.css') }}" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="container-fluid py-4">
       <div class="container">
            <div class="row">
                <div class="col-md-4">
                    @if($store->setting->logo) 
                        <a href="{{ getStoreUrl($store) }}"><img src="{{ asset('stores/'.$store->domain.'/img/'.$store->setting->logo) }}" alt="{{ $store->store_name }}" height="50"></a>
                    @else
                        <a href="{{ getStoreUrl($store) }}"><h1>{{ title_case($store->store_name) }}</h1></a>
                    @endif
                </div>
            </div>
       </div>
    </div>

    <div id="app" class="container">
        @if(!$store->payments()->where('active', 1)->count())
        <div class="row text-center">
            <div class="col-md-12">
                <div class="alert alert-warning">
                    No payment options available currently, cannot place orders. Please contact the store for more details.
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-lg-7 col-md-12 col-sm-12">

                <order-summary></order-summary>

                <mobile-cart></mobile-cart>
                
                <checkout checkout-id="{{ $checkout }}" currency="{{ session('store')->setting->currency->iso_code }}" :checkout-session="{{ $checkout_session }}"/>


            </div>
            <!-- cart -->
            <cart :enable-discount="true"/>
            <!-- cart -->
        </div>
    </div>
    
    <script src="{{ asset('assets/plugins/jquery/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="{{ asset('js/currencies.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/accounting.js/0.4.1/accounting.min.js"></script>
    <script src="{{ asset('assets/js/checkout.js') }}" type="text/javascript"></script>
</body>
</html>