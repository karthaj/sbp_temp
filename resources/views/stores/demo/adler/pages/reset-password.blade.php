@extends($theme_path.'.layouts.theme')

@section('content')

<section>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
        	<div class="mt-3 mb-5">
			    <h2>Reset Password</h2>
			    <form method="post" action="{{ route('password.request') }}">
			    	{{ csrf_field() }}
					<input type="hidden" name="token" value="{{ $token }}">									
			        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
			          <label for="email">Email address <span class="b-required">*</span></label>
			          <input type="email" name="email" id="email" class="form-control{{ $errors->has('email') ? ' form-control-danger' : '' }}" value="{{ $email or old('email') }}" required>
			          @if($errors->has('email'))
			          	<div class="form-control-feedback">{{ $errors->first('email') }}</div>
			          @endif
			        </div>

			        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
			          <label for="password">Password <span class="b-required">*</span></label>
			          <input type="password" name="password" id="password" class="form-control{{ $errors->has('password') ? ' form-control-danger' : '' }}" required>
			          @if($errors->has('password'))
			          	<div class="form-control-feedback">{{ $errors->first('password') }}</div>
			          @endif
			        </div>

			        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
			          <label for="password_confirmation">Confirm Password</label>
			          <input type="password" name="password_confirmation" id="password_confirmation" class="form-control{{ $errors->has('password') ? ' form-control-danger' : '' }}">
			          @if($errors->has('password_confirmation'))
			          	<div class="form-control-feedback">{{ $errors->first('password_confirmation') }}</div>
			          @endif
			        </div>

			        <button type="submit" class="btn">Reset Password</button>  
			    </form>
		  	</div>
        </div>   
      </div>
    </div>
</section>

@endsection