<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <!-- PAGE TITLE -->
  <title>{{ $store->setting->meta_title }}</title>

  <!-- META-DATA -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta http-equiv="content-type" content="text/html; charset=utf-8" >
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
  
  <!-- CSS:: FONTS -->  
  <link href="https://fonts.googleapis.com/css?family=Karla:400,400i,700,700i" rel="stylesheet"> 
  <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet"> 
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet"> 
  <link href="https://fonts.googleapis.com/css?family=Caveat:400,700" rel="stylesheet"> 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css" rel="stylesheet"> 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.theme.default.min.css" rel="stylesheet"> 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.0/slick.min.css" rel="stylesheet"> 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css" rel="stylesheet"> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flexslider/2.7.2/flexslider.min.css"> 
  <link rel="stylesheet" type="text/css" href="{{ asset('stores/'.$store->domain.'/themes/adler/assets/zoomit.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stores/'.$store->domain.'/themes/adler/assets/icons/style.css') }}">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- CSS:: MAIN -->
  <link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/adler/assets/theme.css') }}">

  <link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/adler/assets/customized.css') }}">

  @yield('styles')

  <script>
    
  var Shopbox = Shopbox || {};
  Shopbox.store = "{{ getStoreDomain($store)  }}"
  Shopbox.domain = "{{ $store->domain  }}"
  Shopbox.store_url = "{{ getStoreUrl($store) }}"
  Shopbox.theme = "{{ $store->template->slug }}"
  Shopbox.currency = "{{ $store->setting->currency->iso_code }}"

  </script>

  @include($theme_path.'.partials.google-fonts')

  @if($settings['custom_css'])
    <style>{{ $settings['custom_css'] }}</style>
  @endif
  
   @if($store->setting->facebook_pixel_id)
  <!-- Facebook Pixel -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', {{ $store->setting->facebook_pixel_id }});
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id={{ $store->setting->facebook_pixel_id }}&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Facebook Pixel -->
    @endif

    @if($store->setting->google_analytics)
    <!-- Google Analytics -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                                })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    
        ga('create', '{{ $store->setting->google_analytics }}', 'auto');
        ga('send', 'pageview');
    
    </script>
<!-- End Google Analytics -->
    @endif

</head>
  
<body>
  <div id="app">
    @include($theme_path.'.components.mobile-header', ['section' => $sections['header']])
    <mini-cart></mini-cart>
    <div class="b-wrapper">
      @include($theme_path.'.components.header', ['section' => $sections['header']])
     
     <router-view></router-view>
     
      @yield('content')

      @include($theme_path.'.components.footer', ['section' => $sections['footer']])

      <div class="adler-close-side"></div>

      <quickview></quickview>

      <a href="javascript:;" id="b-scrollToTop" class="b-scrollToTop b-show_scrollBut">
        <span class="basel-tooltip-label">Scroll To Top</span>Scroll To Top
      </a>
      <div class="b-search_popup">
        <form role="search" method="get" id="searchform" class="searchform  basel-ajax-search" action="/search" data-thumbnail="1" data-price="1" data-count="3">
          <div>
            <label class="screen-reader-text" for="s"></label>
            <input type="text" placeholder="Search for products" name="q" id="q" autocomplete="off">
            <button type="submit" class="b-searchsubmit" id="b-searchsubmit">Search</button>
          </div>
        </form>
        <span class="b-close_search" id="b-close_search">close</span>
      </div>
    </div>
  </div>

  <!-- JQUERY:: JQUERY.JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  
  <!-- JQUERY:: BOOTSTRAP.JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.4/js/tether.min.js"></script> 
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.0/slick.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.1/isotope.pkgd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flexslider/2.7.1/jquery.flexslider.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/accounting.js/0.4.1/accounting.min.js"></script>
  {{-- <script src="https://cdn.embedly.com/widgets/platform.js" charset="UTF-8"></script> --}}
  <script src="{{ asset('stores/'.$store->domain.'/themes/adler/assets/jquery.zoomit.min.js') }}" async="async"></script>
  <script src="{{ asset('stores/'.$store->domain.'/themes/adler/assets/stickyMojo.js') }}" async="async"></script>
  <script src="{{ asset('js/currencies.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stores/'.$store->domain.'/themes/adler/assets/theme.js') }}" async="async"></script>
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

  <script>
    window.Theme = {
      settings: {}
    }
    
    window.Theme.settings = {!! json_encode($settings) !!};
  </script>
</body>
</html>