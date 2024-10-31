@extends($theme_path.'.layouts.theme')

@section('content')

<div class="container margin_30">
  <div class="page_header">
    <div class="breadcrumbs">
      <ul>
        <li><a href="/">Home</a></li>
        <li>Password reset</li>
      </ul>
    </div>
    <h1 class="text-center">Reset your password</h1>
  </div>
  <div class="row justify-content-center">
    <div class="col-xl-6 col-lg-6 col-md-8">
      @if(session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
      @endif
      <div class="box_account">
        <form method="post" action="{{ route('password.request') }}">
          {{ csrf_field() }}
          <input type="hidden" name="token" value="{{ $token }}"> 
          <div class="form_container">
            <div class="form-group">
              <input type="email"  name="email" id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ $email or old('email') }}" required>
               @if($errors->has('email'))
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>
             <div class="form-group">
                <label for="password">Password <span class="b-required">*</span></label>
                <input type="password" name="password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required>
                @if($errors->has('password'))
                  <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <div class="form-group">
              <label for="password_confirmation">Confirm Password</label>
              <input type="password" name="password_confirmation" id="password_confirmation" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
              @if($errors->has('password_confirmation'))
                <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
              @endif
            </div>

            <div class="text-center"><input type="submit" value="Reset Password" class="btn_1"></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection