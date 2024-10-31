<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', session('store')->store_name) }}</title>
    <link rel="apple-touch-icon" href="{{ asset('stores').'/'.session('store')->domain.'/img/'.session('store')->setting->favicon }}">
    @if(session('store')->setting->favicon)
    <link rel="icon" type="image/x-icon" href="{{ asset('stores').'/'.session('store')->domain.'/img/'.session('store')->setting->favicon }}" />
    @endif
    <!-- Styles -->
    <link href="{{ asset('assets/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/jquery-scrollbar/jquery.scrollbar.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <script src="{{ asset('assets/plugins/jquery/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('assets/css/pages-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/fonts/line-icons/style.css') }}" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="{{ asset('assets/css/pages.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/shopbox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app_custom.css') }}">
	
	<style>
		.cDiv {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			text-align: center;
			min-height: 100vh;
		}
	</style>
	
	
</head>
<body class="fixed-header menu-pin">

    <div id="btnSave"></div>
    <!-- START PAGE-CONTAINER -->
    <div class="page-container">
        <!-- START PAGE CONTENT WRAPPER -->
        <div  class="page-content-wrapper">
            <!-- START CONTAINER FLUID -->
            <div id="app" class="container-fluid container-fixed-lg">
				
				<div class="row py-3 px-3 text-center cDiv">
					<div class="col-md-12">
						<img class="img-fluid" src="https://myshopbox.lk/assets/img/shopbox.svg" width="200">
					</div>
					<div class="col-md-12">
						<h1>Your Subscription has come to End!</h1>
					</div>
					<div class="col-md-12">
						
						<p>Your account has been temporarily suspended. </p><hr>
						<img class="img-fluid" src="{{ asset('assets/img/plan_expired.png') }}" width="400"><hr>
						<p>Don't worry, your store data has not been lost. <br>
							You will be able to re-activate your account by selecting a plan within three (03) months of your subscription ending.</p>
						<p>If your plan has not been renewed after this period has lapsed, your account will be deactivated permanently and all your data cannot be recovered.</p>
						<p>
							Renew your subscription or
							<a href="mailto:support@shopbox.lk?Subject=Plan%20Renewal" target="_top">Contact Support</a>
							if you have any questions.
						</p><br>
						<a class="btn btn-action-add" href="{{ route('plan.renew') }}">Renew Plan</a>
                        <a href="{{ route('admin.logout') }}" class="mt-3 block" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                        </form>
					</div>
				</div>
            </div>
        </div>
        <!-- END PAGE CONTENT WRAPPER -->
    </div>
    <!-- END PAGE CONTAINER -->
    
    <!-- START QUICKVIEW -->
    <!-- END QUICKVIEW -->
    
    
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
    <script src="{{ asset('assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.dirtyforms/2.0.0/jquery.dirtyforms.min.js"></script>
    <script src="{{ asset('assets/js/shopbox.js') }}"></script>
    <!-- END VENDOR JS -->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="{{ asset('assets/js/pages.min.js') }}" type="text/javascript"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="{{ asset('assets/js/scripts.js') }}" type="text/javascript"></script>

    <!-- END PAGE LEVEL JS -->

</body>
</html>











					
					
					
					
					
					
					
					
					
			