@extends($theme_path.'.layouts.theme')

@section('breadcrum')
<div class="breadcrumb-area pt-20 pb-20">
     <div class="container">
         <div class="row">
             <div class="col-md-12">
                 <div class="breadcrumb-content">
                     <ul>
                         <li><a href="{{ getStoreUrl($store) }}">Home</a></li>
                         <li class="active"><a href="{{ route('password.request') }}">Request Password Reset</a></li>
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
        @if(session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
        @endif
        <div class="login">
          <div class="login-form-container">
            <div class="login-form">
              <form method="post" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div class="form-group">
                  <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}">
                    @if($errors->has('email'))
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <div class="button-box">
                  <div class="login-toggle-btn">
                      <span>We will send you an email to reset your password</span>
                  </div>
                  <button type="submit" class="default-btn">submit</button>
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