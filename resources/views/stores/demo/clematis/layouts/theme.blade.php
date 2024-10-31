<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <!-- PAGE TITLE -->
  <title>{{ $store->setting->meta_title }}</title>

  <!-- META-DATA -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta http-equiv="content-type" content="text/html; charset=utf-8" >
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="{{ $store->setting->meta_description }}" >
  <meta name="keywords" content="{{ $store->setting->meta_keywords }}" >
  @if($store->setting->favicon)
    <link rel="apple-touch-icon" href="{{ asset('stores').'/'.session('store')->domain.'/img/'.session('store')->setting->favicon }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('stores').'/'.session('store')->domain.'/img/'.session('store')->setting->favicon }}" />
    <link rel="shortcut icon" href="{{ asset('stores').'/'.session('store')->domain.'/img/'.session('store')->setting->favicon }}">
  @else
    <link rel="apple-touch-icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}" />        
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.ico') }}" />  
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">
  @endif
  
  @yield('meta')  
  
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/customized.css') }}">

  @yield('styles')
  @yield('header_scripts')

  <script>
    var Shopbox = Shopbox || {};
    Shopbox.store = "{{ getStoreDomain($store)  }}"
    Shopbox.domain = "{{ $store->domain  }}"
    Shopbox.store_url = "{{ getStoreUrl($store) }}"
    Shopbox.theme = "{{ $store->template->slug }}"
    Shopbox.currency = "{{ $store->setting->currency->iso_code }}"
  </script>

  @if($settings['custom_css'])
    <style>{{ $settings['custom_css'] }}</style>
  @endif
  @yield('content_for_header')
</head>
  
<body>
  <div id="app">
    @include($theme_path.'.components.header', ['section' => $sections['header']])
    @include($theme_path.'.components.top_panel')
    <main>
      @yield('content')
      <router-view></router-view>
    </main>
    @include($theme_path.'.components.footer', ['section' => $sections['footer']])
    <div id="toTop"></div>
  </div>

  <script src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/js/common_scripts.min.js') }}"></script>
  <script src="{{ asset('js/currencies.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/js/accounting.min.js') }}"></script>
  <script>
    var Theme = Theme || {};
    Theme.settings = {!! json_encode($settings) !!};
  </script> 
  <script src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/js/main.js') }}"></script>
  @yield('scripts')

  @php
    if(request()->preview && request()->theme_id) {
      echo '<script id="DesignModeThemeSections" type="application/json">'.json_encode(session('schema')).'</script>';
    }
  @endphp

  @if(!request()->preview)
  <script>

    $.ajax({url: `{{ route('track.visit') }}`, type: 'GET', data: { store_id: `{{ $store->id }}`} });

  </script>
  @endif
  @yield('content_for_footer')
</body>
</html>