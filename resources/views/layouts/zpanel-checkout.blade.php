<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Shopbox') }}</title>
    <link rel="apple-touch-icon" href="{{ asset('assets/img/favicon.ico') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}" />
    <!-- Styles -->
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/plugins/jquery/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
    @yield('styles')
    <link href="{{ asset('assets/css/pages-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/fonts/line-icons/style.css') }}" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="{{ asset('assets/css/pages.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/shopbox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app_custom.css') }}">
</head>
<body>
   
   <div id="app" class="container">
       @yield('content')
   </div>
    
    <!-- BEGIN VENDOR JS -->
    <script src="{{ asset('assets/plugins/modernizr.custom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/tether/js/tether.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/shopbox.js') }}"></script>
    @yield('scripts')
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="{{ asset('assets/js/pages.min.js') }}" type="text/javascript"></script>
    <!-- END CORE TEMPLATE JS -->
    @include('layouts.partials.alerts._alert')
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="{{ asset('assets/js/scripts.js') }}" type="text/javascript"></script>
    @yield('page_scripts')

    <!-- END PAGE LEVEL JS -->

</body>
</html>


