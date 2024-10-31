<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>{{ config('app.name') }} - Create Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link href="{{ asset('assets/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link class="main-stylesheet" href="{{ asset('assets/css/pages.css') }}" rel="stylesheet" type="text/css" />
  </head>
  <body class="fixed-header ">
    <div class="register-container full-height sm-p-t-30">
      <div class="d-flex justify-content-center flex-column full-height ">
        <h3>{{ $store }}</h3>
        <p>
          Create a new password. 
        </p>
        <form id="form-active" class="p-t-15" role="form" action="{{ route('activation.user.password.store', $token) }}" method="post">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-12">
              <div class="form-group form-group-default">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Minimum of 6 Charactors" class="form-control{{ $errors->has('password') ? ' error' : '' }}">
              </div>
              @if($errors->has('password'))
              <label id="password-error" class="error" for="password">{{ $errors->first('password') }}</label>
              @endif
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group form-group-default">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" class="form-control">
              </div>
            </div>
          </div>
          <button class="btn btn-info btn-cons m-t-10" type="submit">Create</button>
        </form>
      </div>
    </div>

    <!-- BEGIN VENDOR JS -->
    <script src="{{ asset('assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/modernizr.custom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/tether/js/tether.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <!-- END VENDOR JS -->
    <script src="{{ asset('assets/js/pages.min.js') }}" type="text/javascript"></script>
    <script>
    $(function()
    {
      $('#form-active').validate({
        rules: {
          password: {
            required: true,
          },
          password_confirmation: {
            equalTo: "#password"
          }
        }
      })
    })
    </script>
  </body>
</html>