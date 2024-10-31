@extends($theme_path.'.layouts.theme')

@section('meta')

    <title>Login &ndash; {{ $store->formattedStoreTitle }}</title>
    <meta name="description" content="{{ $store->setting->meta_description }}">
    <meta name="keywords" content="{{ $store->setting->meta_keywords }}">
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ $store->store_name }}"/>
    <meta property="og:title" content="Account"/>
    <meta property="og:description" content="{{ $store->setting->meta_description ?: $store->store_name.' store powered by ShopBox.' }}"/>
    @if($store->setting->logo)
    <meta property="og:image" content="{{ asset('stores').'/'.$store->domain.'/img/'.$store->setting->logo }}"/>
    <meta property="og:image:alt" content="{{ $store->store_name }}"/>
    @endif
    <meta property="og:url" content="{{ getStoreUrl($store).'/login' }}"/>
    <meta property="twitter:card" content="summary_large_image"/>
    <meta property="twitter:site" content="{{ '@'.$store->domain }}"/>
    <meta property="twitter:title" content="Account"/>
    <meta property="twitter:description" content="{{ $store->setting->meta_description ?: $store->store_name.' store powered by ShopBox.' }}"/>
    @if($store->setting->logo)
    <meta property="twitter:image" content="{{ asset('stores').'/'.$store->domain.'/img/'.$store->setting->logo }}"/>
    <meta property="twitter:image:alt" content="{{ $store->store_name }}"/>
    @endif

@endsection

@section('content')

<div class="container margin_30">
	<div class="page_header">
		<div class="breadcrumbs">
			<ul>
				<li><a href="/">Home</a></li>
				<li>Login</li>
			</ul>
		</div>
		<h1 class="text-center">Sign In</h1>
	</div>
	<div class="row justify-content-center">
		<div class="col-xl-6 col-lg-6 col-md-8">
			<div class="box_account">
				<h3 class="client">Exsisting ShopBox Customer</h3> <small class="float-right pt-2">* Required Fields</small>
				<div class="form_container">
					@if(session()->has('success'))
					    <div class="alert alert-primary">
					      {{ session('success') }}
					    </div>
					@endif
					@if(session()->has('error'))
					    <div class="alert alert-danger">
					      {{ session('error') }}
					    </div>
					@endif
					<form action="{{ route('login') }}" method="post">
						{{ csrf_field() }}
						<div class="form-group">
							<input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email" value="{{ old('email') }}" placeholder="Email*">
							@if($errors->has('email'))
								<div class="invalid-feedback">{{ $errors->first('email') }}</div>
							@endif
						</div>
						<div class="form-group">
							<input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password" placeholder="Password*">
						 	@if($errors->has('password'))
					          	<div class="invalid-feedback">{{ $errors->first('password') }}</div>
				          	@endif
						</div>
						<div class="clearfix add_bottom_15">
							<div class="float-right"><a id="forgot" href="{{ route('password.request') }}">Lost Password?</a></div>
						</div>
						<div class="text-center"><input type="submit" value="Log In" class="btn_1 full-width"></div>
					</form>
					<div class="mt-3">
						<a id="register-link" href="{{ route('register') }}">No account? Create one here</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection