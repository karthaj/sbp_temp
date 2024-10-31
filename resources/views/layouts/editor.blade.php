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
    <link href="{{ asset('assets/fonts/line-icons/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/plugins/jquery-scrollbar/jquery.scrollbar.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ asset('assets/plugins/jquery-nouislider/jquery.nouislider.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ asset('assets/css/pages-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.9.1/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('assets/plugins/redactor/redactor.min.css') }}" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="{{ asset('assets/css/pages.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/shopbox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app_custom.css') }}">
    <script src="{{ asset('assets/plugins/jquery/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <style>
       
.current-color {
    display: inline-block;
    width: 40px;
    height: 16px;
    background-color: #000;
    cursor: pointer;
}
    </style>

        <script>
    
          var Shopbox = Shopbox || {};
          Shopbox.store = "{{ session('store')->store_name  }}"
          Shopbox.domain = "{{ session('store')->domain  }}"
          Shopbox.store_url = "{{ getStoreUrl(session('store')) }}"
          Shopbox.theme = {
            handle: "{{ $theme->slug }}",
            name: "{{ $theme->theme_name }}"
          }

        </script>
</head>
<body class="fixed-header">

    <div id="app">
        <template-editor placeholder="{{ asset('assets/img/image-placeholder.png') }}" config-url="{{ route('theme.customize', $theme->alias) }}"
            upload-url="{{ route('theme.uploads', $theme->alias) }}"
            image-path="{{ $image_path }}"
            theme="{{ $theme->alias }}"
            reset-url="{{ route('theme.config.reset', $theme->alias) }}"
            override-url="{{ route('theme.override', $theme->alias) }}"
            ></template-editor>
    </div>  
    <div class="page-container">
        @include('layouts.partials._preview_header')
        <div class="page-content-wrapper preview"> 
            <div class="preview-content">
                <iframe id="tpframe" data-src="{{ $store_url.'?theme_id='.$theme->alias }}&preview=true" class="template-frame preview-iframe--desktop" scrolling="yes" sandbox="allow-same-origin allow-forms allow-popups allow-scripts allow-modals" tabindex="-1" src="{{ $store_url.'?theme_id='.$theme->alias }}&preview=true">
                </iframe>
            </div>
            <div class="container-fluid  container-fixed-lg footer theme-editor-footer hidden-xs-up">
              <a class="btn btn-action-add quickview-toggle" data-toggle-element="#quickview" data-toggle="quickview">customize</a>
            </div>
        </div>
    </div>

    <script type="text/javascript">
         window.Shopbox = window.Shopbox || {};
        Shopbox.domain = "{{ session('store')->domain  }}"
        Shopbox.store_url = "{{ getStoreUrl(session('store')) }}"
        Shopbox.themeSettings = {!! $generalSettings !!}
        Shopbox.sectionSettings = []
        Shopbox.config = {!! $config !!}
        Shopbox.sections = {!! $sections !!}
    </script>
    
    <!-- BEGIN VENDOR JS -->
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
    <script src="{{ asset('assets/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
{{--     <script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.9.1/js/froala_editor.pkgd.min.js" type="text/javascript"></script> --}}
    <script src="{{ asset('assets/plugins/redactor/redactor3.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.dirtyforms/2.0.0/jquery.dirtyforms.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.12/handlebars.min.js"></script>
    <script src="{{ asset('assets/plugins/bootstrap-typehead/typeahead.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-typehead/typeahead.jquery.min.js') }}"></script>
  
    <script src="{{ asset('assets/js/shopbox.js') }}"></script>
    
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="{{ asset('assets/js/pages.min.js') }}" type="text/javascript"></script>
    <!-- END CORE TEMPLATE JS -->
    @include('layouts.partials.alerts._alert')
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="{{ asset('assets/js/scripts.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/theme_editor.js') }}" type="text/javascript"></script>

    <!-- END PAGE LEVEL JS -->

</body>
</html>


