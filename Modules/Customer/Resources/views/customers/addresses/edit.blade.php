@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">customers</li>
  <li class="breadcrumb-item"><a href="{{ route('customers.addresses.index') }}">addresses</a></li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header ">
      <div>
          <h1 class="section-title">Edit Address</h1>
      </div>
  </div>
  <div class="m-0 row card-block">
      <div class="col-lg-4 no-padding">
        <div class="p-r-30">
          <p>
           Edit {{ $address->customer->firstname.' '.$address->customer->lastname."'s address."  }}
          </p>
        </div>
      </div>
      <div class="col-lg-8 sm-no-padding">
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default">
                      <div class="card-block">
                        <form id="shippingClassForm" action="{{ route('customers.addresses.update', $address->id) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                          <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                              <label for="email">Customer Email</label>
                              <input type="email" id="email" class="form-control{{ $errors->has('email') ? ' form-control-danger' : '' }}" name="email" value="{{ old('email', $address->customer->email) }}" required readonly>

                              @if($errors->has('email'))
                              <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                              @endif
                          </div>
                          
                          <div class="form-group{{ $errors->has('firstname') ? ' has-danger' : '' }}">
                              <label for="firstname">First Name</label>
                              <input type="text" id="fistname" class="form-control{{ $errors->has('firstname') ? ' form-control-danger' : '' }}" name="firstname" value="{{ old('firstname', $address->firstname) }}" required>

                              @if($errors->has('firstname'))
                              <div class="form-control-feedback">{{ $errors->first('firstname') }}</div>
                              @endif
                          </div>

                          <div class="form-group{{ $errors->has('lastname') ? ' has-danger' : '' }}">
                              <label for="lastname">Last Name</label>
                              <input type="text" id="lastname" class="form-control{{ $errors->has('lastname') ? ' form-control-danger' : '' }}" name="lastname" value="{{ old('lastname', $address->lastname) }}" required>

                              @if($errors->has('lastname'))
                              <div class="form-control-feedback">{{ $errors->first('lastname') }}</div>
                              @endif
                          </div>

                          <div class="form-group{{ $errors->has('company') ? ' has-danger' : '' }}">
                              <label for="company">Company <span class="text-muted">(Optional)</span></label>
                              <input type="text" id="company" class="form-control{{ $errors->has('company') ? ' form-control-danger' : '' }}" name="company" value="{{ old('company', $address->company) }}">

                              @if($errors->has('company'))
                              <div class="form-control-feedback">{{ $errors->first('company') }}</div>
                              @endif
                          </div>

                          <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                              <label for="phone">Phone Number <span class="text-muted">(Optional)</span></label>
                              <input type="text" id="phone" class="form-control{{ $errors->has('phone') ? ' form-control-danger' : '' }}" name="phone" value="{{ old('phone', $address->phone) }}">

                              @if($errors->has('phone'))
                              <div class="form-control-feedback">{{ $errors->first('phone') }}</div>
                              @endif
                          </div>

                          <div class="form-group{{ $errors->has('address1') ? ' has-danger' : '' }}">
                            <label for="address1">Address 1 </label>
                              <input id="address1" type="text" class="form-control{{ $errors->has('address1') ? ' is-invalid' : '' }}" name="address1" required value="{{ old('address1', $address->address1) }}">

                              @if($errors->has('address1'))
                              <div class="form-control-feedback">{{ $errors->first('address1') }}</div>
                              @endif
                          </div>

                          <div class="form-group {{ $errors->has('address2') ? ' has-danger' : '' }}">
                              <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                              <input id="address2" type="text" class="form-control" name="address2" value="{{ old('address2', $address->phone) }}">

                              @if($errors->has('address2'))
                              <div class="form-control-feedback">{{ $errors->first('address2') }}</div>
                              @endif
                          </div>
                  
                          <div class="form-group {{ $errors->has('city') ? ' has-danger' : '' }}">
                              <label for="city">Suburb/City</label>
                              <input id="city" type="text" class="form-control" name="city" required value="{{ old('phone', $address->city) }}">

                              @if($errors->has('city'))
                              <div class="form-control-feedback">{{ $errors->first('city') }}</div>
                              @endif
                          </div>

                          <div class="form-group{{ $errors->has('country') ? ' has-danger' : '' }}">
                              <label for="country">Country</label>
                              <select name="country" id="country" class="form-control{{ $errors->has('country') ? ' form-control-danger' : '' }}" data-init-plugin="select2">
                                <option value="">Choose a Country</option>
                                @if($countries->count())
                                  @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ $country->id == $address->country_id ? 'selected' : '' }}> {{ $country->name }}</option>
                                  @endforeach
                                @endif
                              </select>

                              @if($errors->has('country'))
                              <div class="form-control-feedback">{{ $errors->first('country') }}</div>
                              @endif
                          </div>

                          <div class="form-group">
                            @if($address->province)
                              <div id="stateProvince">
                                <label for="province">State/Province</label>
                                <input type="text" name="province" id="province" class="form-control" value="{{ $address->province }}" required>
                                @if($errors->has('province'))
                                  <div class="form-control-feedback" for="province">{{ $errors->first('province') }}</div>
                                @endif
                              </div>
                            @elseif($address->state_id)
                              <label for="state">State/Province</label>
                              <select name="state" id="state" class="full-width form-control" data-init-plugin="select2" required>
                                @foreach($address->country->states as $state)
                                <option value="{{ $state->id }}" {{ $state->id == $address->state_id ? 'selected' : '' }}>{{ $state->name }}</option>
                                @endforeach
                              </select>
                            @endif
                        
                            
                            @if($errors->has('state'))
                            <div class="form-control-feedback" for="state">{{ $errors->first('state') }}</div>
                            @endif
                          </div>

                          <div class="form-group {{ $errors->has('postcode') ? ' has-danger' : '' }}">
                              <label for="postcode">Zip/Postcode</label>
                              <input id="postcode" type="text" class="form-control" name="postcode" required value="{{ old('postcode', $address->postcode) }}">

                              @if($errors->has('postcode'))
                              <div class="form-control-feedback">{{ $errors->first('postcode') }}</div>
                              @endif
                          </div>
          
                          <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                            <div class="checkbox check-success">
                              <input type="checkbox" {{ $address->active ? 'checked' : '' }} value="1" id="status" name="status">
                              <label for="status" class="form-check-label">Active</label>
                            </div>
                          </div>
                          
                           @include ('zpanel.partials._form_actions', ['path' => route('customers.addresses.index')])
                        </form>
                      </div>
                    </div>
                  </div>
                </div>    
            </div>
        </div>
      </div>
  </div>
</div>
<!-- end shipping -->


@endsection

@section('scripts') 
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/customer.js') }}"></script>
@endsection

@section('page_scripts')
<script>
  $(document).ready(function() {
    //form.init();
  });
</script>
@endsection
