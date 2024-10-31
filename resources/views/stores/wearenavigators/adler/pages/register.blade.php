@extends($theme_path.'.layouts.theme')

@section('content')

	<section id="b-my_account">
        <div class="container b-my_account">
          <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
            	<div class="b-auth_section b-auth_register">
			        <h2><i class="icon-user icons"></i> Register</h2>
			        <form action="{{ route('register') }}" method="POST">
				        {{ csrf_field() }}
				        <div class="form-group{{ $errors->has('first_name') ? ' has-danger' : '' }}">
				        	<label for="first_name">First Name <span class="b-required">*</span></label>
	          				<input type="text" name="first_name" id="first_name" class="form-control{{ $errors->has('first_name') ? ' form-control-danger' : '' }}" value="{{ old('first_name') }}">
						    @if($errors->has('first_name'))
					          	<div class="form-control-feedback">{{ $errors->first('first_name') }}</div>
					        @endif
				        </div>
				        <div class="form-group{{ $errors->has('last_name') ? ' has-danger' : '' }}">
				        	<label for="last_name">Last Name <span class="b-required">*</span></label>
	          				<input type="text" name="last_name" id="last_name" class="form-control{{ $errors->has('last_name') ? ' form-control-danger' : '' }}" value="{{ old('last_name') }}">
						    @if($errors->has('last_name'))
					          	<div class="form-control-feedback">{{ $errors->first('last_name') }}</div>
					        @endif
				        </div>
			          	<div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
				            <label for="email">Email address <span class="b-required">*</span></label>
				            <input type="email" name="email" id="email" class="form-control{{ $errors->has('email') ? ' form-control-danger' : '' }}" value="{{ old('email') }}">
				            @if($errors->has('email'))
					          	<div class="form-control-feedback">{{ $errors->first('email') }}</div>
					        @endif
			          	</div>
			          	<div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
				            <label for="password">Password <span class="b-required">*</span></label>
				            <input type="password" name="password" id="password" class="form-control{{ $errors->has('password') ? ' has-danger' : '' }}">
				            @if($errors->has('password'))
					          	<div class="form-control-feedback">{{ $errors->first('password') }}</div>
					        @endif
			          	</div> 
			          	<div class="form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
				            <label for="password">Confirm password <span class="b-required">*</span></label>
				            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
				            @if($errors->has('password_confirmation'))
					          	<div class="form-control-feedback">{{ $errors->first('password_confirmation') }}</div>
					        @endif
			          	</div> 
			          	<button type="submit" class="btn">Register</button> 
			        </form>
			    </div>
            </div>  
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
              <div class="b-auth_text text-center b-auth_text_login">
                <h3>Login</h3>
                <p>Registering for this site allows you to access your order status and history. Just fill in the fields below, and we’ll get a new account set up for you in no time. We will only ask you for information necessary to make the purchase process faster and easier.</p>
                <a href="{{ route('login') }}" class="btn">Login</a>
              </div>
            </div>  
          </div>
        </div>
    </section>

@endsection