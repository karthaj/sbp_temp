@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{ asset('assets/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')

<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('shipping.index') }}">shipping</a></li>
  <li class="breadcrumb-item">zone</li>
  <li class="breadcrumb-item">edit</li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent mb-0">
  <div class="card-header ">
    <h1 class="section-title">Edit {{ $shipping_zone->zone_name }}</h1>
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
                        <form id="shippingZoneForm" action="{{ route('shipping.update', $shipping_zone->alias) }}" method="post" autocomplete="off" class="sodirty">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <input type="hidden" name="shipping_zone" value="{{ $shipping_zone->id }}">
                          <div class="row">
                              <div class="col-sm-6 form-group">
                                  <label for="zone_name">Zone Name</label>
                                  <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="Enter a name that describes this shipping zone (such as Sri Lanka, or Australia). This is for your reference only and will not be visible to customers.">
                                    <i class="fa fa-question-circle"></i>
                                  </span>
                                  <input type="text" id="zone_name" class="form-control{{ $errors->has('zone_name') ? ' error' : '' }}" name="zone_name" value="{{ $shipping_zone->zone_name ? $shipping_zone->zone_name : old('zone_name') }}" required>
                                  @if($errors->has('zone_name'))
                                  <label id="name-error" class="error" for="zone_name">{{ $errors->first('zone_name') }}</label>
                                  @endif
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-6 form-group">
                              <label for="zone_type">Zone Type</label>
                              <select name="zone_type" id="zone_type" class="form-control{{ $errors->has('zone_type') ? ' error' : '' }}">
                                  <option value="country" {{ $shipping_zone->zone_type == 'country' ? 'selected' : old('zone_type') == 'country' ? 'selected' : '' }}>Selection of countries</option>
                                  <option value="state" {{ $shipping_zone->zone_type == 'state' ? 'selected' : old('zone_type') == 'state' ? 'selected' : '' }}>Selection of states</option>
                                  <option value="zip_code" {{ $shipping_zone->zone_type == 'zip_code' ? 'selected' : old('zone_type') == 'zip_code' ? 'selected' : '' }}>Selection city or postal/zip codes</option>
                              </select>
                              @if($errors->has('zone_type'))
                              <label id="zone_type-error" class="error" for="zone_type">{{ $errors->first('zone_type') }}</label>
                              @endif
                            </div>
                          </div>
                          <div id="zoneCountry" class="row" style="display: {{ $shipping_zone->zone_type == 'country' ? 'block' : old('zone_type') == 'country' ? 'block' : 'none' }}">
                            <div class="col-sm-6 form-group">
                              <label for="zone_country">Country</label>
                              <select id="zone_country" class="full-width form-control{{ $errors->has('zone_country') ? ' error' : '' }}" name="zone_country[]" multiple>
                              @if($countries->count())
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}" 
                                {{ $shipping_zone->locations->contains('country_id', $country->id) ? 'selected' : '' }}>
                                  {{ $country->name }}
                                </option>
                                @endforeach
                              @endif
                              </select>
                              @if($errors->has('zone_country'))
                              <label id="zone_country-error" class="error" for="zone_country">{{ $errors->first('zone_country') }}</label>
                              @endif
                            </div>
                          </div>
                          <div id="zipCountry" class="row" style="display: {{ $shipping_zone->zone_type == 'zip_code' ? 'block' : old('zone_type') == 'zip_code' ? 'block' : 'none' }}">
                            <div class="col-sm-6 form-group">
                              <label for="zip_country">Country</label>
                              <select id="zip_country" class="full-width form-control{{ $errors->has('zip_country') ? ' error' : '' }}" name="zip_country">
                              @if($countries->count())
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ $country->shippingZone ? 'disabled' : '' }}
                                  {{ $shipping_zone->locations->contains('country_id', $country->id) ? 'selected' : '' }}>
                                  {{ $country->name }}
                                </option>
                                @endforeach
                              @endif
                              </select>
                              @if($errors->has('zip_country'))
                              <label id="zip_country-error" class="error" for="zip_country">{{ $errors->first('zip_country') }}</label>
                              @endif
                            </div>
                          </div>
                          <div id="stateCountry" class="row" style="display: {{ $shipping_zone->zone_type == 'state' ? 'block' : old('zone_type') == 'state' ? 'block' : 'none' }}">
                            <div class="col-sm-6 form-group">
                              <label for="state_country">Countries</label>
                              <select id="state_country" class="full-width form-control{{ $errors->has('state_country') ? ' error' : '' }}" name="state_country" multiple>
                              @if($country_states->count())
                                @foreach($country_states as $country_state)
                                <option value="{{ $country_state->id }}" {{ $country_state->shippingZone ? 'disabled' : '' }} {{ $shipping_zone->locations->contains('country_id', $country_state->id) ? 'selected' : '' }}>
                                  {{ $country_state->name }}
                                </option>
                                @endforeach
                              @endif
                              </select>
                              @if($errors->has('state_country'))
                              <label id="state_country-error" class="error" for="state_country">{{ $errors->first('state_country') }}</label>
                              @endif
                            </div>
                          </div>
                          <div id="stateList" class="row" style="display: {{ $shipping_zone->zone_type == 'state' ? 'block' : old('zone_type') == 'state' ? 'block' : 'none' }}">
                            <div class="col-sm-6 form-group">
                              <label for="states">States</label>
                              <select id="states" class="full-width form-control{{ $errors->has('states') ? ' error' : '' }}" name="states[]" multiple>
                              @foreach($shipping_zone->locations as $location)
                                  <optgroup label="{{ $location->country->name }}">
                                    @foreach($location->country->states as $state)
                                    <option value="{{ $location->country->id.'-'.$state->id }}" {{ $location->state_id === $state->id ? 'selected' : '' }}>
                                      {{ $state->name }}
                                    </option>                                   
                                    @endforeach
                                  </optgroup>
                              @endforeach
                              </select>
                              @if($errors->has('states'))
                              <label id="states-error" class="error" for="states">{{ $errors->first('states') }}</label>
                              @endif
                            </div>
                          </div>
                          <div id="zipCode" class="row" style="display: {{ $shipping_zone->zone_type == 'zip_code' ? 'block' : old('zone_type') == 'zip_code' ? 'block' : 'none' }}">
                            <div class="col-sm-12 form-group">
                              <label for="zip_code">Zip / Postal codes</label>
                              @if($shipping_zone->locations->first()->country_id != 197)
                              <input class="tagsinput form-control{{ $errors->has('zip_code') ? ' error' : '' }}" type="text" id="zip_code" name="zip_code" value="{{ $shipping_zone->locations->first()->zip_codes }}">
                                @if($errors->has('zip_code'))
                                <label id="zip_code-error" class="error" for="zip_code">{{ $errors->first('zip_code') }}</label>
                                @endif
                              @elseif($shipping_zone->locations->first()->country_id == 197)
                                <select class="full-width form-control" name="zip_code[]" data-init-plugin="select2" multiple>
                                  @if($shipping_zone->locations->first()->country->cities->count())
                                    @foreach($shipping_zone->locations->first()->country->cities as $city)
                                      <option value="{{ $city->zip_code }}" @if(str_contains($shipping_zone->locations->first()->zip_codes, $city->zip_code)) selected @endif>{{ $city->city_name }} | {{ $city->zip_code}}</option>
                                    @endforeach
                                  @endif
                                </select>
                              @endif
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12 form-group">
                              <div class="checkbox check-info">
                                <input type="checkbox" {{ $shipping_zone->status ? 'checked' : '' }} value="1" id="zone_status" name="zone_status">
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
  <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
        <div class="p-r-30">
          <h6 class="ui-subheader">Shipping Methods</h6>
        </div>
      </div>
      <div class="col-lg-8 sm-no-padding mb-0">
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent mb-0">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default mb-0">
                      <div class="card-block">
                          @if($shipping_zone->shippingMethods->count())
                            @foreach($shipping_zone->shippingMethods as $method)
                              <div class="row align-items-center">
                                <div class="col-sm-4">
                                  <strong>{{ $method->shippingMethod->name }}</strong>
                                </div>
                                <div class="col-sm-5">
                                  <p>{{ $method->shippingMethod->description }}</p>
                                </div>
                                @if($method->shipping_method_id == 1)
                                @include ('product::partials._shippingactions', ['id' => 'free_shipping', 'name' => 'free_shipping', 'modal' => 'modalFreeShipping', 'checked' => $shipping_zone->shippingMethods->where('shipping_method_id',1)->first()->status, 'value' => $method->id])
                                @elseif ($method->shipping_method_id == 2)
                                @include ('product::partials._shippingactions', ['id' => 'flat_rate', 'name' => 'flat_rate', 'modal' => 'modalFlatRate', 'checked' => $shipping_zone->shippingMethods->where('shipping_method_id',2)->first()->status, 'value' => $method->id])
                                @elseif ($method->shipping_method_id == 3)
                                @include ('product::partials._shippingactions', ['id' => 'ship_weight_order', 'name' => 'ship_weight_order', 'modal' => 'modalWeightOrder', 'checked' => $shipping_zone->shippingMethods->where('shipping_method_id',3)->first()->status, 'value' => $method->id])
                                @endif
                              </div>
                              @if (!$loop->last)
                                <hr>
                              @endif
                            @endforeach
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

<!-- start free shipping modal -->
<div class="modal fade slide-up disable-scroll" id="modalFreeShipping" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog ">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5>Free Shipping <span class="semi-bold">Settings</span></h5>
          <p class="p-b-10">You are able to limit free shipping to be active only when a certain order total amount is exceeded.</p>
        </div>
        <div class="modal-body">
          <form id="formFreeShip" action="{{ route('shipping.free_shipping.update', $shipping_zone->shippingMethods->where('shipping_method_id',1)->first()->id) }}" method="post" autocomplete="off">
            <div class="form-group">
              <label for="email">shipper email</label>
              <input type="email" name="email" id="email" class="form-control" value="{{ $shipping_zone->shippingMethods->where('shipping_method_id',1)->first()->email }}">
            </div>
            <div class="form-group">
              <div class="checkbox check-info">
                <input type="checkbox" value="1" id="limit_order" name="limit_order" {{ $shipping_zone->shippingMethods->where('shipping_method_id',1)->first()->min_order ? 'checked' : '' }}>
                <label for="limit_order">Limit to order over</label>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group transparent">
                <span class="input-group-addon">
                    Rs
                </span>
                <input type="text" class="form-control" id="amount" name="amount" value="{{ $shipping_zone->shippingMethods->where('shipping_method_id',1)->first()->min_order }}" {{ $shipping_zone->shippingMethods->where('shipping_method_id',1)->first()->min_order ? '' : 'disabled' }}>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <div class="form-group">
            <button type="button" class="btn btn-default btn-action-cancel"  data-dismiss="modal">Cancel</button>
            <button type="submit" id="btnFreeShip" class="btn btn-action-save">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end free shipping modal -->

<!-- start flat rate modal -->
<div class="modal fade slide-up disable-scroll" id="modalFlatRate" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog ">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5>Flat Rate <span class="semi-bold">Settings</span></h5>
        </div>
        <div class="modal-body">
          <form id="formFlatRate" action="{{ route('shipping.flat_rate.update', $shipping_zone->shippingMethods->where('shipping_method_id',2)->first()->id) }}" method="post" autocomplete="off">
            <div class="form-group">
              <label for="flat_rate_email">shipper email</label>
              <input type="email" name="flat_rate_email" id="flat_rate_email" class="form-control" value="{{ $shipping_zone->shippingMethods->where('shipping_method_id',2)->first()->email }}">
            </div>
            <div class="form-group">
              <label for="display_name">Display Name</label>
              <input type="text" name="display_name" id="flat_rate_display_name" class="form-control" value="{{ $shipping_zone->shippingMethods->where('shipping_method_id',2)->first()->display_name }}">
            </div>
            <div class="row">
              <div class="col-sm-6 form-group">
                <label for="shipping_rate">Cost</label>
                <div class="input-group transparent">
                  <span class="input-group-addon">
                      Rs
                  </span>
                  <input type="text" class="form-control autonumeric" id="shipping_rate" name="shipping_rate" value="{{ $shipping_zone->shippingMethods->where('shipping_method_id',2)->first()->rate }}">
                </div>
              </div>
              <div class="col-sm-6 form-group">
                <label for="charge_type">Charge</label>
                <select name="charge_type" id="charge_type" class="form-control">
                  <option value="0" {{ $shipping_zone->shippingMethods->where('shipping_method_id',2)->first()->eligible_type == 0 ? 'selected' : '' }}>Per Order</option>
                  <option value="1" {{ $shipping_zone->shippingMethods->where('shipping_method_id',2)->first()->eligible_type == 1 ? 'selected' : '' }}>Per Item</option>
                </select>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <div class="form-group">
            <button type="button" class="btn btn-action-cancel"  data-dismiss="modal">Cancel</button>
            <button type="button" id="btnFlatRate" class="btn btn-action-save">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end flat rate modal -->

<div id="app">
  
  <!-- start weight modal -->
  <modal-weight-order currency="{{ session('store')->setting->currency->iso_code }}"
                      weight-unit="{{ session('store')->setting->weight->weight_code }}"
                      decimal="{{ session('store')->setting->currency->decimal }}"
                      endpoint="{{ route('shipping.delivery.rates.update', $shipping_zone->shippingMethods->where('shipping_method_id',3)->first()->id) }}"
                      :data="{{ $data }}"></modal-weight-order>
  <!-- end  weight modal -->

</div>


<!-- END PLACE PAGE CONTENT HERE -->
@endsection

@section('scripts') 
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/plugins/switchery/js/switchery.min.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/accounting.js/0.4.1/accounting.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-autonumeric/autoNumeric.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/shipping_form.js') }}"></script>

@yield('module_scripts')

@endsection

@section('page_scripts')
<script>
  $(document).ready(function() {
    $("#states").select2();
    form.init();
  });
</script>
@endsection





