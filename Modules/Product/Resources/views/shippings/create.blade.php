@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{ asset('assets/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('shipping.index') }}">shipping</a></li>
  <li class="breadcrumb-item">zone</li>
  <li class="breadcrumb-item">add</li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent mb-0">
  <div class="card-header ">
    <h1 class="section-title">Add a Shipping Zone</h1>
  </div>
  <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
        <div class="p-r-30">
          <p>
           Create your delivery zones. Each zone can be configured with their own rates. E.g. Colombo city limits can be set up as a free delivery, but outstation cities can configured to charge a rate based on the weight of the order.
          </p>
        </div>
      </div>
      <div class="col-lg-8 sm-no-padding mb-0">
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent mb-0">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default mb-0">
                      <div class="card-block">
                        <form id="shippingZoneForm" action="{{ route('shipping.zones.store') }}" method="post" autocomplete="off" class="sodirty">
                        {{ csrf_field() }}
                          <div class="row">
                              <div class="col-sm-6 form-group{{ $errors->has('zone_name') ? ' has-danger' : '' }}">
                                  <label for="zone_name">Zone Name</label>
                                  <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="Enter a name that describes this shipping zone (such as Sri Lanka, or Australia). This is for your reference only and will not be visible to customers.">
                                    <i class="fa fa-question-circle"></i>
                                  </span>
                                  <input type="text" id="zone_name" class="form-control{{ $errors->has('zone_name') ? ' form-control-danger' : '' }}" name="zone_name" value="{{ old('zone_name') }}" required>
                                  @if($errors->has('zone_name'))
                                    <div class="form-control-feedback">{{ $errors->first('zone_name') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-6 form-group">
                              <label for="zone_type">Zone Type</label>
                              <select name="zone_type" id="zone_type" class="form-control{{ $errors->has('zone_type') ? ' error' : '' }}">
                                <option value="zip_code" {{ old('zone_type') == 'zip_code' ? 'selected' : '' }}>Selection city or postal/zip codes</option>
                                <option value="country" {{ old('zone_type') == 'country' ? 'selected' : '' }}>Selection of countries</option>
                                <option value="state" {{ old('zone_type') == 'state' ? 'selected' : '' }}>Selection of states</option>
                              </select>
                              @if($errors->has('zone_type'))
                              <label id="zone_type-error" class="error" for="zone_type">{{ $errors->first('zone_type') }}</label>
                              @endif
                            </div>
                          </div>
                          <div id="zoneCountry" class="row" style="display: {{ old('zone_type') == 'country' ? 'block' : 'none' }}">
                            <div class="col-sm-6 form-group">
                              <label for="zone_country">Country</label>
                              <select id="zone_country" class="full-width form-control{{ $errors->has('zone_country') ? ' error' : '' }}" name="zone_country[]" multiple>
                              @if($countries->count())
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                              @endif
                              </select>
                              @if($errors->has('zone_country'))
                              <label id="zone_country-error" class="error" for="zone_country">{{ $errors->first('zone_country') }}</label>
                              @endif
                            </div>
                          </div>
                          <div id="zipCountry" class="row" style="display: {{ old('zone_type') ? old('zone_type') == 'zip_code' ? 'block' : 'none' : 'block' }}">
                            <div class="col-sm-6 form-group">
                              <label for="zip_country">Country</label>
                              <select id="zip_country" class="full-width form-control{{ $errors->has('zip_country') ? ' error' : '' }}" name="zip_country">
                              <option value="">Select a country</option>
                              @if($countries->count())
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                              @endif
                              </select>
                              @if($errors->has('zip_country'))
                              <label id="zip_country-error" class="error" for="zip_country">{{ $errors->first('zip_country') }}</label>
                              @endif
                            </div>
                          </div>
                          <div id="stateCountry" class="row" style="display: {{ old('zone_type') == 'state' ? 'block' : 'none' }}">
                            <div class="col-sm-6 form-group">
                              <label for="state_country">Countries</label>
                              <select id="state_country" class="full-width form-control{{ $errors->has('state_country') ? ' error' : '' }}" name="state_country" multiple>
                              @if($country_states->count())
                                @foreach($country_states as $country_state)
                                <option value="{{ $country_state->id }}">{{ $country_state->name }}</option>
                                @endforeach
                              @endif
                              </select>
                              @if($errors->has('state_country'))
                              <label id="state_country-error" class="error" for="state_country">{{ $errors->first('state_country') }}</label>
                              @endif
                            </div>
                          </div>
                          <div id="stateList" class="row" style="display: {{ old('zone_type') == 'state' ? 'block' : 'none' }}">
                            <div class="col-sm-6 form-group">
                              <label for="states">States</label>
                              <select id="states" class="full-width form-control{{ $errors->has('states') ? ' error' : '' }}" name="states[]" multiple>
                              </select>
                              @if($errors->has('states'))
                              <label id="states-error" class="error" for="states">{{ $errors->first('states') }}</label>
                              @endif
                            </div>
                          </div>
                          <div id="zipCode" class="row" style="display: {{ old('zone_type') ? old('zone_type') == 'zip_code' ? 'block' : 'none' : 'block' }}">
                            <div class="col-sm-12 form-group">
                              <label for="zip_code">Zip / Postal codes</label>
                              <input class="tagsinput form-control{{ $errors->has('zip_code') ? ' error' : '' }}" type="text" id="zip_code" name="zip_code">
                              @if($errors->has('zip_code'))
                              <label id="zip_code-error" class="error" for="zip_code">{{ $errors->first('zip_code') }}</label>
                              @endif
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12 form-group">
                              <div class="checkbox check-info">
                                <input type="checkbox" checked="checked" value="1" id="zone_status" name="zone_status">
                                <label for="zone_status">Active</label>
                              </div>
                            </div>
                          </div>
                           @include ('zpanel.partials._form_actions', ['path' => route('shipping.index')])
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
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/plugins/switchery/js/switchery.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/js/shipping_form.js') }}"></script>
@endsection

@section('page_scripts')
<script>
  $(document).ready(function() {
    $("#states").select2();
    form.init();
  });
</script>
@endsection





