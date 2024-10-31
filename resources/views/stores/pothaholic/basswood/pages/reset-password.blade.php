@extends($theme_path.'.layouts.theme')

@section('breadcrum')
<div class="breadcrumb-area pt-20 pb-20">
     <div class="container">
         <div class="row">
             <div class="col-md-12">
                 <div class="breadcrumb-content">
                     <ul>
                         <li><a href="{{ getStoreUrl($store) }}">Home</a></li>
                         <li class="active"><a href="{{ route('password.reset', $token) }}">Reset Password</a></li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>
</div>
@endsection

@section('content')

<div class="login-area pt-90">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-12 col-lg-6 col-xl-6 ml-auto mr-auto">
        <div class="login">
          <div class="login-form-container">
            <div class="login-form">
              <form method="post" action="{{ route('password.request') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}"> 
                <div class="form-group">
                  <label for="email">Email address</label>
                  <input type="email" name="email" id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ $email or old('email') }}" required>
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

                <div class="button-box">
                  <button type="submit" class="default-btn">Reset Password</button>
                </div>
              </form>
             </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection