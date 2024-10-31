@extends('layouts.zpanel')

@section('styles')

<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />

@endsection

@section('content')

<!-- START BREADCRUMB -->
 <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
  <li class="breadcrumb-item"><a href="{{ route('account.users') }}">Users</a></li>
  <li class="breadcrumb-item"><a href="{{ route('account.users.create') }}">Add</a></li>
</ol>
<!-- END BREADCRUMB --> 

<!-- BEGIN PlACE PAGE CONTENT HERE -->
  <!-- start card -->
  <div id="app"></div>
  <div class="card card-transparent">
  <form id="new_staff"  action="{{ route('account.users.store') }}" method="post" autocomplete="off">
    {{ csrf_field() }}
    <div class="card-header">
        <div class="no-padding">
          <h2 class="section-title">Add staff account</h2>
        </div>
    </div>
    <div class="m-0 row card-block">
        <div class="col-lg-4 no-padding">
          <div class="p-r-30">
            <h6 class="ui-subheader">Staff account</h6>
            <p>Give your staff permissions to manage your store.</p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
            <div class="row">
              <div class="col-lg-12">
                <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div id="card-advance" class="card card-default mb-0">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group{{ $errors->has('firstname') ? ' has-danger' : '' }}">
                                        <label>First name</label>
                                        <input type="text" class="form-control{{ $errors->has('firstname') ? ' form-control-danger' : '' }}"  name="firstname" required>
                                        @if($errors->has('firstname'))
                                        <div class="form-control-feedback">{{ $errors->first('firstname') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group{{ $errors->has('lastname') ? ' has-danger' : '' }}">
                                        <label>Last name</label>
                                        <input type="text" class="form-control{{ $errors->has('lastname') ? ' form-control-danger' : '' }}"  name="lastname" required>
                                        @if($errors->has('lastname'))
                                        <div class="form-control-feedback">{{ $errors->first('lastname') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label>Email</label>
                                <input type="email" class="form-control{{ $errors->has('email') ? ' form-control-danger' : '' }}"  id="email" name="email" required>
                                @if($errors->has('email'))
                                <div class="form-control-feedback">{{ $errors->first('email') }}</div>
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
    <div class="m-0 row card-block">
        <div class="col-lg-4 no-padding">
          <div class="p-r-30">
            <h6 class="ui-subheader">Store access</h6>
            <p>Give your staff permissions to manage your store location.</p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
            <div class="row">
              <div class="col-lg-12">
                <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div class="card card-default mb-0">
                        <div class="card-header">
                            <div class="card-title">
                                <h6 class="ui-subheader">Store Locations</h6>
                            </div>
                        </div>
                        <div class="card-block">
                          @if($locations->count())
                            @foreach($locations as $index => $location)
                            <div class="form-group{{ $errors->has('locations.'.$index) ? ' has-danger' : '' }}">
                              <div class="checkbox check-info checkbox-circle">
                                <input type="checkbox" value="{{ $location->id }}" id="location_{{ $location->id }}" name="locations[]"> 
                                <label for="location_{{ $location->id }}">{{ $location->name }}</label>
                              </div>
                              @if($errors->has('locations.'.$index))
                                  <div class="form-control-feedback">{{ $errors->first('locations.'.$index) }}</div>
                              @endif
                            </div>
                            @endforeach 
                          @else
                          <p>No store locations found.</p>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div> 
              </div>
            </div>   
        </div>
    </div>
    <!-- START USER ACCESS SECTION -->
    <div class="m-0 row card-block">
          <div class="col-lg-4 no-padding">
            <div class="p-r-30">
              <h6 class="ui-subheader mt-0">
               User access
              </h6>
              <p>Enable or restrict access to various parts of this store.</p>
            </div>
          </div>
          <div class="col-lg-8 sm-no-padding">
            <div class="row">
                <div class="col-lg-12">
                   <div class="card card-transparent">
                      <div class="card-block no-padding">
                        <div id="card-advance" class="card card-default">
                          <div class="card-header">
                              <div class="card-title">
                                  <h6 class="ui-subheader">user permissions</h6>
                              </div>
                          </div>
                          <div class="card-block">
                            @if($permissions->count())
                            <div class="row">
                              @foreach($permissions as $label => $sub_permissions)
                              <div class="col-md-4">
                                <p><b>{{ $label }}</b></p>
                                @foreach($sub_permissions as $permission)
                                  <div class="checkbox check-info checkbox-circle">
                                    <input type="checkbox" value="{{ $permission->id }}" id="{{ $permission->name }}" name="permissions[]" checked>
                                    <label for="{{ $permission->name }}">{{ $permission->name }}</label>
                                  </div>
                                @endforeach
                              </div>
                              @endforeach
                            </div>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>
    <!-- END USER ACCESS SECTION -->
    @include ('zpanel.partials._form_actions', ['path' => route('account.users')])
  </form>
  </div>
    
  <!-- end card -->
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
@endsection
@section('page_scripts')

<script>
    $(document).ready(function() {
       //Shopbox.validateUserAccount()
    });
</script>

@endsection