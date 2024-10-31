@extends($theme_path.'.layouts.theme')

@section('meta')

    <title>Register &ndash; {{ $store->formattedStoreTitle }}</title>
    <meta name="description" content="{{ $store->setting->meta_description }}">
    <meta name="keywords" content="{{ $store->setting->meta_keywords }}">
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ $store->store_name }}"/>
    <meta property="og:title" content="Create Account"/>
    <meta property="og:description" content="{{ $store->setting->meta_description ?: $store->store_name.' store powered by ShopBox.' }}"/>
    @if($store->setting->logo)
    <meta property="og:image" content="{{ asset('stores').'/'.$store->domain.'/img/'.$store->setting->logo }}"/>
    <meta property="og:image:alt" content="{{ $store->store_name }}"/>
    @endif
    <meta property="og:url" content="{{ getStoreUrl($store).'/login' }}"/>
    <meta property="twitter:card" content="summary_large_image"/>
    <meta property="twitter:site" content="{{ '@'.$store->domain }}"/>
    <meta property="twitter:title" content="Create Account"/>
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
				<li>Register</li>
			</ul>
		</div>
		<h1 class="text-center">Sign Up</h1>
	</div>
	<div class="row justify-content-center">
		<div class="col-xl-6 col-lg-6 col-md-8">
			<div class="box_account">
				<h3 class="new_client">New ShopBox Customer</h3> <small class="float-right pt-2">* Required Fields</small>
				<div class="form_container">
					<form action="{{ route('register') }}" method="POST">
						{{ csrf_field() }}
						<div class="private box">
							<div class="row no-gutters">
								<div class="col-6 pr-1">
									<div class="form-group">
										<input type="text" name="first_name" id="first_name" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" value="{{ old('first_name') }}" placeholder="First Name*">
										@if($errors->has('first_name'))
	                                        <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
	                                    @endif
									</div>
								</div>
								<div class="col-6 pl-1">
									<div class="form-group">
										<input type="text" name="last_name" id="last_name" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" value="{{ old('last_name') }}" placeholder="Last Name*">
										@if($errors->has('last_name'))
	                                        <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
	                                    @endif
									</div>
								</div>
								<div class="col-12">
									<div class="form-group">
										<input type="email" name="email" id="email"  class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="Email*">
										@if($errors->has('email'))
	                                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
	                                    @endif
									</div>
								</div>
							</div>
							<div class="row no-gutters">
								<div class="col-6 pr-1">
									<div class="form-group">
										<input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password" placeholder="Password*">
										@if($errors->has('password'))
	                                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
	                                    @endif
									</div>
								</div>
								<div class="col-6 pl-1">
									<div class="form-group">
										<input type="password" name="password_confirmation" id="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" placeholder="Confirm password*">
										@if($errors->has('password_confirmation'))
	                                        <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
	                                    @endif
									</div>
								</div>
							</div>	
						</div>
						<hr>
						<div class="text-center"><input type="submit" value="Register" class="btn_1 full-width"></div>
					</form>
					<div class="mt-3">
						<a href="/login" id="register-link">Already have an account? Login</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection