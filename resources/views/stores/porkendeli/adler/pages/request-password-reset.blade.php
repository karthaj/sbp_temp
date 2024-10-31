@extends($theme_path.'.layouts.theme')

@section('content')

	<section id="b-my_account">
        <div class="container b-my_account">
          <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
              <div class="b-auth_section b-auth_login">
                <form method="POST" action="{{ route('password.email') }}">
                	{{ csrf_field() }}
                    <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                      <label for="email">Email address <span class="b-required">*</span></label>
                      <input type="email" name="email" id="email" class="form-control{{ $errors->has('email') ? ' form-control-danger' : '' }}">
                      @if($errors->has('email'))
				        <div class="form-control-feedback">{{ $errors->first('email') }}</div>
				      @endif
                    </div>
                    <button type="submit" class="btn">submit</button>  
                </form>
              </div>
            </div>  
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
              <div class="b-auth_text text-center b-auth_text_login">
                <h3>Reset your password</h3>
                <p>We will send you an email to reset your password</p>
              </div>
            </div>  
        </div>
   	</div>

@endsection