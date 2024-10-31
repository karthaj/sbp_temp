
<!doctype html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>{{ $store->store_name }}</title>
    <meta name="author" content="aidantz.com">
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
	<meta property="og:type" content="website" />
  	<meta property="og:site_name" content="{{ $store->store_name }}" />
  	<meta property="og:title" content="{{ $store->setting->meta_title }}" />
  	<meta property="og:description" content="{{ $store->setting->meta_description ?: $store->store_name.' store powered by ShopBox.' }}" />
  	@if($store->setting->logo)
	    <meta property="og:image" content="{{ asset('stores').'/'.$store->domain.'/img/'.$store->setting->logo }}" />
	    <meta property="og:image:alt" content="{{ $store->store_name }}" />
  	@endif
  	<meta property="og:url" content="{{ getStoreUrl($store) }}" />
  	<meta property="twitter:card" content="summary_large_image" />
  	<meta property="twitter:site" content="{{ '@'.$store->domain }}" />
  	<meta property="twitter:title" content="{{ $store->setting->meta_title }}" />
  	<meta property="twitter:description" content="{{ $store->setting->meta_description ?: $store->store_name.' store powered by ShopBox.' }}" />
  	@if($store->setting->logo)
	    <meta property="twitter:image" content="{{ asset('stores').'/'.$store->domain.'/img/'.$store->setting->logo }}" />
	    <meta property="twitter:image:alt" content="{{ $store->store_name }}" />
  	@endif

	@yield('meta') 
    
    
	<!-- Material Design Iconic Font CSS-->
    <link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/material-design-iconic-font.min.css') }}">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/font-awesome.min.css') }}">
	<!-- Animate CSS-->
	<link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/animate.css') }}">
	<!-- UI CSS-->
	<link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/jquery-ui.min.css') }}">
	<!-- Owl Carousel CSS-->
	<link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/owl.carousel.min.css') }}">
	<!-- Slick CSS-->
	<link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/slick.css') }}">
	<!-- Nice Select CSS-->
	<link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/nice-select.css') }}">
	<!-- Meanmenu CSS -->
	<link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/meanmenu.min.css') }}">
	<!-- Venobox CSS-->
	<link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/venobox.css') }}">
	<!-- Bootstrap CSS-->
	<link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/bootstrap.min.css') }}">
	<!-- Style CSS -->
	<link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/style.css') }}">
	<!-- Responsive CSS -->
	<link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/responsive.css') }}">

	<link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/css/gijgo.min.css') }}">
	<!-- Modernizr Js -->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/modernizr-2.8.3.min.js') }}"></script>

	<link rel="stylesheet" href="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/customized.css') }}">

	@yield('styles')
	@yield('header_scripts')

	<script>
	    
	var Shopbox = Shopbox || {};
	Shopbox.store = "{{ $store->store_name  }}"
	Shopbox.domain = "{{ $store->domain  }}"
	Shopbox.store_url = "{{ getStoreUrl($store) }}"
	Shopbox.theme = "{{ $store->template->slug }}"
	Shopbox.currency = "{{ $store->setting->currency->iso_code }}"

	</script>

	@include($theme_path.'.partials.google-fonts')

	@if($settings['custom_css'])
    	<style>{{ $settings['custom_css'] }}</style>
	@endif
	 

	<style>
		
	.powered {
		display: block !important;
		color: {{  $settings['accent_color'] }};
		font-size: 13px; 
	}
	.powered a {
		color: {{  $settings['accent_color'] }};
	}

	</style>

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
		<div class="wrapper {{ $settings['box_view'] ? 'wrapper-box' : '' }}">
			@include($theme_path.'.components.header')
			@yield('breadcrum')
			<router-view></router-view>
			@yield('content')
			@include($theme_path.'.components.footer', ['section' => $sections['footer']])
		</div>
		<quickview add-to-cart-endpoint="{{ route('cart.add') }}"></quickview>
		<vue-snotify></vue-snotify>
	</div>

	<!--Jquery 1.12.4-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/jquery-1.12.4.min.js') }}"></script>
	<!--Imagesloaded-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/imagesloaded.pkgd.min.js') }}"></script> 
	<!--Isotope-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/isotope.pkgd.min.js') }}"></script>       
	<!--Ui js-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/jquery-ui.min.js') }}"></script>
	<!--Waypoints-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/waypoints.min.js') }}"></script>
	<!--Carousel-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/owl.carousel.min.js') }}"></script>
	<!--Slick-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/slick.min.js') }}"></script>
	<!--Nice Select-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/jquery.nice-select.min.js') }}"></script>
	<!--Meanmenu-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/jquery.meanmenu.min.js') }}"></script>
	<!--Instafeed-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/instafeed.min.js') }}"></script>
	<!--Instafeed-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/jquery.scrollUp.min.js') }}"></script>
	<!--Wow-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/wow.min.js') }}"></script>
	<!--Venobox-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/venobox.min.js') }}"></script>
	<!--Popper-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/popper.min.js') }}"></script>
	<!--Bootstrap-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/bootstrap.min.js') }}"></script>
	<!--Plugins-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/plugins.js') }}"></script>
	
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/accounting.min.js') }}"></script>

	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/gijgo.min.js') }}"></script>

	<script src="{{ asset('js/currencies.js') }}" type="text/javascript"></script>

	<script>
		var Theme = Theme || {};
	    Theme.settings = {!! json_encode($settings) !!};
  	</script>	

	<!--Main Js-->
	<script src="{{ asset('stores/'.$store->domain.'/themes/basswood/assets/js/main.js') }}"></script>
	
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
	
	@yield('scripts')
</body>
</html>
