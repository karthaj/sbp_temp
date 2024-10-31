@extends($theme_path.'.layouts.theme')

@section('content')
	@if(session()->has('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger">
          {{ session('error') }}
        </div>
    @endif
    
	<section id="b-my_account">
        <div class="container b-my_account">
          <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
            	<div class="b-auth_section b-auth_login">
				    <h2><i class="icon-login icons"></i> Login</h2>
				    <form method="post" action="{{ route('login') }}">
				    	{{ csrf_field() }}
				        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
				          <label for="auth_email">Email address <span class="b-required">*</span></label>
				          <input type="email" name="email" id="email" class="form-control{{ $errors->has('email') ? ' form-control-danger' : '' }}" value="{{ old('email') }}">
				          @if($errors->has('email'))
				          	<div class="form-control-feedback">{{ $errors->first('email') }}</div>
				          @endif
				        </div>
				        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
				          <label for="password">Password <span class="b-required">*</span></label>
				          <input type="password" name="password" id="password" class="form-control{{ $errors->has('password') ? ' form-control-danger' : '' }}">
				          @if($errors->has('password'))
				          	<div class="form-control-feedback">{{ $errors->first('password') }}</div>
				          @endif
				        </div>
				        <div class="form-group">
				          <a href="{{ route('password.request') }}" class="b-lost_password"><i class="icon-support icons"></i>  Lost your password?</a>
				        </div>
				        <button type="submit" class="btn">Login</button>  
				    </form>
			  	</div>
            </div>  
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
              <div class="b-auth_text text-center b-auth_text_register">
                <h3>Register</h3>
                <p>Registering for this site allows you to access your order status and history. Just fill in the fields below, and we’ll get a new account set up for you in no time. We will only ask you for information necessary to make the purchase process faster and easier.</p>
                <a href="{{ route('register') }}" class="btn">Register</a>
              </div>
            </div>  
          </div>
        </div>
    </section>

@endsection