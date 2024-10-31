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
  <li class="breadcrumb-item">store</li>
  <li class="breadcrumb-item">tax</li>
  <li class="breadcrumb-item"><a href="{{ route('tax.zones.index') }}">tax zone</a></li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header ">
    <a href="{{ route('tax.zones.index') }}" class="bold"><i class="pg-arrow_left"></i>Tax zones</a>
    <h1 class="section-title">Add a Tax Zone</h1>
  </div>
  <div class="m-0 row card-block">
      <div class="col-lg-4 no-padding">
        <div class="p-r-30">
          <p>
           Tax zones let you specify different tax rates depending on your customer's locations.
          </p>
        </div>
      </div>
      <div class="col-lg-8 sm-no-padding">
        <form id="zoneForm" action="{{ route('tax.zone.store') }}" method="post" autocomplete="off" class="sodirty">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default">
                      <div class="card-block">
                          <div class="row">
                              <div class="col-sm-6 form-group{{ $errors->has('zone_name') ? ' has-danger' : '' }}">
                                  <label for="zone_name">Zone Name</label>
                                  <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="Enter a name that describes this tax zone (such as Sri Lanka, or Australia). This is for your reference only and will not be visible to customers.">
                                    <i class="fa fa-question-circle"></i>
                                  </span>
                                  <input type="text" id="zone_name" class="form-control{{ $errors->has('zone_name') ? ' form-control-danger' : '' }}" name="zone_name" value="{{ old('zone_name') }}" required>
                                  @if($errors->has('zone_name'))
                                  <div class="form-control-feedback">{{ $errors->first('zone_name') }}</div>
                                  @endif
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12 form-group">
                              <label>Zone Type</label>
                              <div class="radio radio-success">
                                <input type="radio" value="country" name="zone_type" id="country_type" checked>
                                <label for="country_type" class="text-normal">This tax zone is based on one or more countries</label>
                                <input type="radio" value="state" name="zone_type" id="state">
                                <label for="state" class="text-normal">This tax zone is based on one or more states</label>
                                <input type="radio" class="text-normal"  value="zip" name="zone_type" id="zip">
                                <label for="zip" class="text-normal">This tax zone is based on one or more postal or ZIP codes</label>
                              </div>
                            </div>
                          </div>
                          <div id="zoneCountry" class="row">
                            <div class="col-sm-6 form-group{{ $errors->has('zone_country') ? ' has-danger' : '' }}">
                              <label for="zone_country">Country</label>
                              <select id="zone_country" class="full-width{{ $errors->has('zone_country') ? ' form-control-danger' : '' }}" name="zone_country[]" multiple>
                              @if($countries->count())
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}" @if($country->taxRule) disabled @endif>{{ $country->name }}
                                @if($country->taxRule)
                                - assigned to zone {{ $country->taxRule->taxZone->name }}
                                @endif
                                </option>
                                @endforeach
                              @endif
                              </select>
                              @if($errors->has('zone_country'))
                                <div class="form-control-feedback">{{ $errors->first('zone_country') }}</div>
                              @endif
                            </div>
                          </div>
                          <div id="zipCountry" class="row" style="display: none;">
                            <div class="col-sm-6 form-group{{ $errors->has('zip_country') ? ' has-danger' : '' }}">
                              <label for="zip_country">Country</label>
                              <select id="zip_country" class="full-width{{ $errors->has('zip_country') ? ' form-control-danger' : '' }}" name="zip_country">
                              @if($countries->count())
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}"  @if($country->taxRule) disabled @endif>{{ $country->name }}
                                @if($country->taxRule)
                                - assigned to zone {{ $country->taxRule->taxZone->name }}
                                @endif
                                </option>
                                @endforeach
                              @endif
                              </select>
                              @if($errors->has('zip_country'))
                                <div class="form-control-feedback">{{ $errors->first('zip_country') }}</div>
                              @endif
                            </div>
                          </div>
                          <div id="stateCountry" class="row" style="display:none;">
                            <div class="col-sm-6 form-group{{ $errors->has('state_country') ? ' has-danger' : '' }}">
                              <label for="state_country">Countries</label>
                              <select id="state_country" class="full-width{{ $errors->has('state_country') ? ' form-control-danger' : '' }}" name="state_country" multiple>
                              @if($country_states->count())
                                @foreach($country_states as $country_state)
                                <option value="{{ $country_state->id }}" @if($country_state->taxRule) disabled @endif>{{ $country_state->name }}
                                  @if($country_state->taxRule)
                                  - assigned to zone {{ $country_state->taxRule->taxZone->name }}
                                  @endif
                                </option>
                                @endforeach
                              @endif
                              </select>
                              @if($errors->has('state_country'))
                                <div class="form-control-feedback">{{ $errors->first('state_country') }}</div>
                              @endif
                            </div>
                          </div>
                          <div id="stateList" class="row" style="display:none;">
                            <div class="col-sm-6 form-group{{ $errors->has('states') ? ' has-danger' : '' }}">
                              <label for="states">States</label>
                              <select id="states" class="full-width{{ $errors->has('states') ? ' form-control-danger' : '' }}" name="states[]" multiple>
                              </select>
                              @if($errors->has('states'))
                                <div class="form-control-feedback">{{ $errors->first('states') }}</div>
                              @endif
                            </div>
                          </div>
                          <div id="zipCode" class="row" style="display: none;">
                            <div class="col-sm-12 form-group{{ $errors->has('zip_code') ? ' has-danger' : '' }}">
                              <label for="zip_code">Zip / Postal codes</label>
                              <input class="tagsinput{{ $errors->has('zip_code') ? ' form-control-danger' : '' }}" type="text" id="zip_code" name="zip_code">
                              @if($errors->has('zip_code'))
                                <div class="form-control-feedback">{{ $errors->first('zip_code') }}</div>
                              @endif
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12 form-group{{ $errors->has('zone_status') ? ' has-danger' : '' }}">
                              <div class="checkbox check-success">
                                <input type="checkbox" checked="checked" value="1" id="zone_status" name="zone_status" class="{{ $errors->has('zone_status') ? ' form-control-danger' : '' }}">
                                <label for="zone_status">Active</label>
                              </div>
                              @if($errors->has('zone_status'))
                                <div class="form-control-feedback">{{ $errors->first('zone_status') }}</div>
                              @endif
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>    
            </div>
        </div>
          @include ('zpanel.partials._form_actions', ['path' => route('tax.zones.index')])
        </form>
      </div>
  </div>
</div>

<!-- END PLACE PAGE CONTENT HERE -->


@endsection

@section('scripts') 
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/tax_form.js') }}"></script>
@endsection

@section('page_scripts')
<script>
  $(document).ready(function() {
    $("#states").select2();
    form.init();
  });
</script>
@endsection





