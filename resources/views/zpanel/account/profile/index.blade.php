@extends('layouts.zpanel')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.css') }}">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')

<!-- START BREADCRUMB -->
 <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item">Account</li>
  <li class="breadcrumb-item"><a href="{{ route('account.profile.index') }}">Profile</a></li>
</ol>
<div id="app"></div>
<!-- END BREADCRUMB --> 
<form action="{{ route('account.profile.store') }}" autocomplete="off" method="post" enctype="multipart/form-data">
{{ csrf_field() }}
<div class="card card-transparent mb-0">
  <div class="card-header  ">
    <div class="card-title">
        <h2 class="section-title">{{ auth()->user()->first_name.' '.auth()->user()->last_name }}</h2>
    </div>
  </div>
  <div class="m-0 row card-block pb-0">
    <div class="col-lg-4 no-padding">
      <div class="p-r-30">
        <h6 class="ui-subheader">Profile</h6>    
      </div>
    </div>
    <div class="col-lg-8 sm-no-padding">
      <div class="card card-transparent mb-0">
        <div class="card-block no-padding">
          <div class="card card-default mb-0">
            <div class="card-header">
              <div class="card-title">
                  <h6 class="ui-subheader">Profile</h6>
              </div>
              <div class="card-controls">
                <ul>
                  <li><a href="#" class="card-collapse" data-toggle="collapse"><i class="pg-arrow_maximize"></i></a>
                  </li>

                </ul>
              </div>
            </div>
            <div class="card-block">
              <div class="row mb-3">
                  <div class="user-avatar--main">
                    <span class="user-avatar{{ auth()->user()->avatar ? '' : ' user-avatar--style-2' }}">
                        @if(auth()->user()->avatar)
                          <img class="user-avatar__gravatar-image" alt="" src="{{ asset('stores').'/'.session('store')->domain.'/img/'.auth()->user()->avatar }}">
                        @else
                            <img class="user-avatar__gravatar-image" alt="" src="">
                            <span class="user-avatar__initials text-uppercase">{{ substr(auth()->user()->first_name,0,1).substr(auth()->user()->last_name,0,1) }}</span>
                        @endif
                    </span>
                  </div>
              
              <div class="styled-file-input col-3" id="user-image-drop">
                <div class="btn button">
                  <input type="file" id="user-upload-image" accept="image/*" class="users-file-input{{ $errors->has('avatar') ? ' error' : '' }}" onchange="Shopbox.fileInputChanged(this)" name="avatar">
                  <label class="mb-0">Upload photo</label>
                   @if($errors->has('avatar'))
                    <label id="avatar-error" class="error" for="avatar">{{ $errors->first('avatar') }}</label>
                   @endif
                </div>
              </div>
              <div>
                  <button id="{{ auth()->user()->avatar ? 'btnDeleteUploadedPhoto' : 'btnDeletePhoto' }}" class="btn btn-default btn-default-custom" type="button" {{ auth()->user()->avatar ? '' : 'disabled' }}>Delete photo</button>
              </div>
              </div>
              <!-- START PROFILE INFO -->
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                    <label>First name</label>
                    <input type="text" class="form-control{{ $errors->has('first_name') ? ' error' : '' }}" required name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}">
                    @if($errors->has('firstname'))
                    <label id="first_name-error" class="error" for="first_name">{{ $errors->first('first_name') }}</label>
                    @endif
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                    <label>Last name</label>
                    <input type="text" class="form-control{{ $errors->has('lastname') ? ' error' : '' }}" required name="last_name" value="{{ old('first_name', auth()->user()->last_name) }}">
                    @if($errors->has('last_name'))
                    <label id="last_name-error" class="error" for="last_name">{{ $errors->first('last_name') }}</label>
                    @endif
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                    <label>Email</label>
                    <input type="email" class="form-control{{ $errors->has('email') ? ' error' : '' }}" required id="editEmail" name="email" value="{{ old('email', auth()->user()->email) }}">
                    @if($errors->has('email'))
                    <label id="email-error" class="error" for="email">{{ $errors->first('email') }}</label>
                    @endif
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                    <label>Phone</label>
                    <input type="text" class="form-control{{ $errors->has('phone') ? ' error' : '' }}" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                    @if($errors->has('phone'))
                    <label id="phone-error" class="error" for="phone">{{ $errors->first('phone') }}</label>
                    @endif
                </div>
              </div>
              <!-- END PROFILE INFO -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="m-0 row card-block pb-0">
    <div class="col-lg-4 no-padding">
      <div class="p-r-30">
        <h6 class="ui-subheader">Change your password</h6>    
      </div>
    </div>
    <div class="col-lg-8 sm-no-padding">
      <div class="card card-transparent mb-0">
        <div class="card-block no-padding">
          <div class="card card-default mb-0">
            <div class="card-header">
              <div class="card-title">
                  <h6 class="ui-subheader">password reset</h6>
              </div>
              <div class="card-controls">
                <ul>
                  <li><a href="#" class="card-collapse" data-toggle="collapse"><i class="pg-arrow_maximize"></i></a>
                  </li>

                </ul>
              </div>
            </div>
            <div class="card-block">
              <!--<div class="form-group{{ $errors->has('current_password') ? ' has-danger' : '' }}">
                  <label for="current_password">Current password</label>
                  <input type="password" id="current_password" class="form-control{{ $errors->has('current_password') ? ' form-control-danger' : '' }}" name="current_password">
                  @if($errors->has('current_password'))
                  <div class="form-control-feedback">{{ $errors->first('current_password') }}</div>
                  @endif
              </div>-->
              <div class="row">
                <div class="col-sm-6 form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                    <label for="password">New password</label>
                    <input type="password" id="password" class="form-control{{ $errors->has('password') ? ' form-control-danger' : '' }}" name="password">
                    @if($errors->has('password'))
                    <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                    @endif
                </div>
                <div class="col-sm-6 form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
                    <label for="password_confirmation">Confirm password</label>
                    <input type="password" id="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' form-control-danger' : '' }}" name="password_confirmation">
                    @if($errors->has('password_confirmation'))
                    <div class="form-control-feedback">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<hr>
<!-- END REMOVE STAFF SECTION -->
@include ('zpanel.partials._form_actions', ['path' => route('dashboard')])
</form>


@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
@endsection

@section('page_scripts')

<script>
    $(document).ready(function() {
       //Shopbox.validateUserAccountUpdate();
    });
</script>

@endsection