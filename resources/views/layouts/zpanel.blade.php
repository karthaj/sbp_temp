<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Shopbox') }}</title>
    @if(session('store')->setting->favicon)
    <link rel="apple-touch-icon" href="{{ asset('stores').'/'.session('store')->domain.'/img/'.session('store')->setting->favicon }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('stores').'/'.session('store')->domain.'/img/'.session('store')->setting->favicon }}" />
    @endif
    <!-- Styles -->
    <link href="{{ asset('assets/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/jquery-scrollbar/jquery.scrollbar.css') }}" rel="stylesheet" type="text/css" media="screen" />
    @yield('styles')
    <link href="{{ asset('assets/css/pages-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/fonts/line-icons/style.css') }}" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="{{ asset('assets/css/pages.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/shopbox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app_custom.css') }}">
    <script type="text/json" data-serialized-id="store-details">{!! json_encode([
          'store' => [
            'storeId' => session('store')->id,
            'storeName' => session('store')->store_name,
            'storeEmail' => session('store')->store_email,
          ]
        ]) !!}
    </script>
</head>
<body class="fixed-header menu-pin">
   
    <!-- BEGIN SIDEBAR -->
    <!-- BEGIN SIDEBPANEL-->
    @include('layouts.partials._sidebar')
    <!-- END SIDEBAR -->
    <!-- END SIDEBAR -->
    <div id="btnSave"></div>
    <!-- START PAGE-CONTAINER -->
    <div class="page-container">
        <!-- START PAGE HEADER WRAPPER -->
        <!-- START HEADER -->
        @include('layouts.partials._header')
        <!-- END HEADER -->
        <!-- END PAGE HEADER WRAPPER -->
        
        <!-- START PAGE CONTENT WRAPPER -->
        <div  class="page-content-wrapper">
             <!-- START PAGE CONTENT -->
             <div class="content">
                <!-- START CONTAINER FLUID -->
                <div id="app" class="container-fluid container-fixed-lg">
                    @include('layouts.partials._plan_duration')
                    @yield('content')
                </div>
             </div>
             <!-- END PAGE CONTENT -->
             <!-- START FOOTER -->
             @yield('action')
            @include('layouts.partials._footer')
            <!-- END FOOTER -->
        </div>
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTAINER -->
    
    <!-- START QUICKVIEW -->
    <!-- END QUICKVIEW -->
    
    
    <!-- BEGIN VENDOR JS -->
    <script src="{{ asset('assets/plugins/jquery/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/modernizr.custom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/tether/js/tether.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery-easy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-unveil/jquery.unveil.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-bez/jquery.bez.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-ios-list/jquery.ioslist.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-actual/jquery.actual.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.dirtyforms/2.0.0/jquery.dirtyforms.min.js"></script>

    
    @yield('scripts')
    <script src="{{ asset('assets/js/shopbox.js') }}"></script>
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="{{ asset('assets/js/pages.min.js') }}" type="text/javascript"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- END VENDOR JS -->
    @include('layouts.partials.alerts._alert')
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="{{ asset('assets/js/scripts.js') }}" type="text/javascript"></script>
    

    @yield('page_scripts')

    <!-- END PAGE LEVEL JS -->

    <script>
    
    @if($theme_version !== session('store')->template->version)
        $('.page-content-wrapper').pgNotification({
            style: 'simple',
            message: '{{ session('store')->template->theme->theme_name }} theme update available <a href="{{ route('theme.index') }}" class="text-complete">Click here</a>',
            position: 'top-right',
            timeout: 0,
            type: "info"
        }).show();
    @endif

        $(document).on('click', '.guide-me', function(e){  
            var intro = introJs();
            intro.setOptions({
              showStepNumbers: false,
              exitOnEsc: false,
              @if(request()->is('merchant/dashboard'))
              highlightClass: 'highlightClass'
              @endif
            });
            intro.start();
        });

    </script>

</body>
</html>


