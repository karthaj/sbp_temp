@extends($theme_path.'.layouts.theme')

@section('content')

<div class="container margin_30">
  <div class="page_header">
    <div class="breadcrumbs">
      <ul>
        <li><a href="/">Home</a></li>
        <li>Request password reset</li>
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
        <form method="post" action="{{ route('password.email') }}">
          {{ csrf_field() }}
          <div class="form_container">
            <div class="form-group">
              <input type="email"  name="email" id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="Type your email">
               @if($errors->has('email'))
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>
            <p>We will send you an email to reset your password</p>
            <div class="text-center"><input type="submit" value="Submit" class="btn_1"></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection