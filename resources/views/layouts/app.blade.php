<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Shopbox') }}</title>
    <meta name="keywords" content="e-commerce, web, site, hosting, deals, responsive, mobile, optimized, payment, gateway" />
    <meta name="description" content="ShopBox is an easy-to-use, online-based website builder which can be used by anyone to create their own online store! ShopBox is geared to help small to medium sized businesses who desire an online presence but cannot afford the associated development and running costs." />

    <link rel="apple-touch-icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}" />        
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}" />        
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    @yield('app.styles')
</head>
<body class="gradient">
    <div id="app">
         
        <main role="main" class="container cDiv">
            @include('layouts.partials.alerts._notify')
            @yield('content')
        </main>

    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('app.scripts')
</body>
</html>
