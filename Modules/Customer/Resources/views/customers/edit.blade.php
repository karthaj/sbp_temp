@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">customer</a></li>
</ol>
<!-- END BREADCRUMB --> 
<form id="shippingClassForm" action="{{ route('customers.update', $customer->id) }}" method="post" class="sodirty">
{{ csrf_field() }}
{{ method_field('PATCH') }}
  <div  class="card card-transparent">
    <div class="card-header ">
        <div>
            <h1 class="section-title">Edit Customer</h1>
        </div>
    </div>
    <div class="m-0 row card-block">
        <div class="col-lg-4 no-padding">
          <div class="p-r-30">
            <h6 class="ui-subheader mt-0">
              Customer overview
            </h6>
            <p>
             Complete the form below to update this customer.
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
                            <div class="form-group{{ $errors->has('firstname') ? ' has-danger' : '' }}">
                                <label for="fisrtname">First Name</label>
                                <input type="text" id="fisrtname" class="form-control{{ $errors->has('fisrtname') ? ' form-control-danger' : '' }}" name="firstname" value="{{ old('firstname', $customer->firstname) }}" required>

                                @if($errors->has('firstname'))
                                <div class="form-control-feedback">{{ $errors->first('firstname') }}</div>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('lastname') ? ' has-danger' : '' }}">
                                <label for="lastname">Last Name</label>
                                <input type="text" id="lastname" class="form-control{{ $errors->has('lastname') ? ' form-control-danger' : '' }}" name="lastname" value="{{ old('lastname', $customer->lastname) }}" required>

                                @if($errors->has('lastname'))
                                <div class="form-control-feedback">{{ $errors->first('lastname') }}</div>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" class="form-control{{ $errors->has('email') ? ' form-control-danger' : '' }}" name="email" value="{{ old('email', $customer->email) }}" required>

                                @if($errors->has('email'))
                                <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                                <label for="phone">Phone Number <span class="text-muted">(Optional)</span></label>
                                <input type="text" id="phone" class="form-control{{ $errors->has('phone') ? ' form-control-danger' : '' }}" name="phone" value="{{ old('phone', $customer->phone) }}">

                                @if($errors->has('phone'))
                                <div class="form-control-feedback">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('group') ? ' has-danger' : '' }}">
                                <label for="group">Customer Group <span class="text-muted">(Optional)</span></label>
                                <select name="group" id="group" class="form-control{{ $errors->has('group') ? ' form-control-danger' : '' }}">
                                  <option value="">-- Do not assign to a customer group --</option>
                                  @if($groups->count())
                                    @foreach($groups as $group)
                                      <option value="{{ $group->id }}" {{ $group->id == $customer->group_id ? 'selected' : '' }}> {{ $group->name }}</option>
                                    @endforeach
                                  @endif
                                </select>

                                @if($errors->has('group'))
                                <div class="form-control-feedback">{{ $errors->first('group') }}</div>
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
    @if(0)
    <div class="m-0 row card-block">
          <div class="col-lg-4 no-padding">
            <div class="p-r-30">
              <h6 class="ui-subheader mt-0">
               Address
              </h6>
              <p>The primary address of this customer.</p>
            </div>
          </div>
          <div class="col-lg-8 sm-no-padding">
            <div class="row">
                <div class="col-lg-12">
                   <div class="card card-transparent">
                      <div class="card-block no-padding">
                        <div id="card-advance" class="card card-default">
                          <div class="card-block">
    
                            <div class="form-group{{ $errors->has('address_company') ? ' has-danger' : '' }}">
                                <label for="address_company">Company <span class="text-muted">(Optional)</span></label>
                                <input type="text" id="address_company" class="form-control{{ $errors->has('address_company') ? ' form-control-danger' : '' }}" name="address_company" value="{{ old('address_company', $customer->addresses->where('default', 1)->first()->company) }}">

                                @if($errors->has('address_company'))
                                <div class="form-control-feedback">{{ $errors->first('address_company') }}</div>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('address_phone') ? ' has-danger' : '' }}">
                                <label for="address_phone">Phone Number <span class="text-muted">(Optional)</span></label>
                                <input type="text" id="address_phone" class="form-control{{ $errors->has('address_phone') ? ' form-control-danger' : '' }}" name="address_phone" value="{{ old('address_phone', $customer->addresses->where('default', 1)->first()->phone) }}">

                                @if($errors->has('address_phone'))
                                <div class="form-control-feedback">{{ $errors->first('address_phone') }}</div>
                                @endif
                            </div>

                            <div class="form-group{{ $errors->has('address1') ? ' has-danger' : '' }}">
                              <label for="address1">Address 1 </label>
                                <input id="address1" type="text" class="form-control{{ $errors->has('address1') ? ' is-invalid' : '' }}" name="address1" required value="{{ old('address1', $customer->addresses->where('default', 1)->first()->address) }}">

                                @if($errors->has('address1'))
                                <div class="form-control-feedback">{{ $errors->first('address1') }}</div>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('address2') ? ' has-danger' : '' }}">
                                <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                                <input id="address2" type="text" class="form-control" name="address2" value="{{ old('address2', $customer->addresses->where('default', 1)->first()->address2) }}">

                                @if($errors->has('address2'))
                                <div class="form-control-feedback">{{ $errors->first('address2') }}</div>
                                @endif
                            </div>
                    
                            <div class="form-group {{ $errors->has('city') ? ' has-danger' : '' }}">
                                <label for="city">Suburb/City</label>
                                <input id="city" type="text" class="form-control" name="city" required value="{{ old('city', $customer->addresses->where('default', 1)->first()->city) }}">

                                @if($errors->has('city'))
                                <div class="form-control-feedback">{{ $errors->first('city') }}</div>
                                @endif
                            </div>

                            <div class="row">
                              <div class="col-md-4">
                                <div class="form-group{{ $errors->has('country') ? ' has-danger' : '' }}">
                                    <label for="country">Country</label>
                                    <select name="country" id="country" class="form-control{{ $errors->has('country') ? ' form-control-danger' : '' }}" data-init-plugin="select2" required data-url="{{ route('customers.address.country.states') }}">
                                      <option value="">Choose a Country</option>
                                      @if($countries->count())
                                        @foreach($countries as $country)
                                          <option value="{{ $country->id }}" {{ $customer->addresses->where('default', 1)->first()->country_id == $country->id ? 'selected' : '' }}> {{ $country->name }}</option>
                                        @endforeach
                                      @endif
                                    </select>

                                    @if($errors->has('country'))
                                    <div class="form-control-feedback">{{ $errors->first('country') }}</div>
                                    @endif
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="state">State/Province</label>
                                  <select name="state" id="state" class="full-width form-control" data-init-plugin="select2">
                                    @foreach($customer->addresses->where('default', 1)->first()->country->states as $state)
                                    <option value="{{ $state->id }}" {{ $state->id == $customer->addresses->where('default', 1)->first()->state_id ? 'selected' : '' }}>{{ $state->name }}</option>
                                    @endforeach
                                  </select>
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group {{ $errors->has('postcode') ? ' has-danger' : '' }}">
                                    <label for="postcode">Zip/Postcode</label>
                                    <input id="postcode" type="text" class="form-control" name="postcode" value="{{ old('postcode', $customer->addresses->where('default', 1)->first()->zip_code) }}">

                                    @if($errors->has('postcode'))
                                    <div class="form-control-feedback">{{ $errors->first('postcode') }}</div>
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
          </div>
      </div>
    @endif
  </div>
  @include ('zpanel.partials._form_actions', ['path' => route('customers.index')])
</form>


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
