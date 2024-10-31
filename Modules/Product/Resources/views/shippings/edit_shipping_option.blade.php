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
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item">store</li>
  <li class="breadcrumb-item">shipping</li>
  <li class="breadcrumb-item active"><a href="{{ route('shipping.index') }}">zone</a></li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent mb-0">
  <div class="card-header ">
    <a href="{{ route('shipping.index') }}" class="bold"><i class="pg-arrow_left"></i>Shipping zones</a>
    <h1 class="section-title">{{ $shipping_zone->zone_name }}</h1>
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
                                @elseif ($method->shipping_method_id == 4)
                                @include ('product::partials._shippingactions', ['id' => 'store_pickup', 'name' => 'store_pickup', 'modal' => 'modalStorePickUp', 'checked' => $shipping_zone->shippingMethods->where('shipping_method_id',4)->first()->status, 'value' => $method->id])
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
<!-- end shipping -->
<!-- END PLACE PAGE CONTENT HERE -->
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
              <label for="free_ship_email">shipper email</label>
              <input type="email" name="free_ship_email" id="free_ship_email" class="form-control">
            </div>
            <div class="form-group">
              <div class="checkbox check-success  ">
                <input type="checkbox" value="1" id="limit_order" name="limit_order" {{ $shipping_zone->shippingMethods->where('shipping_method_id',1)->first()->rate ? 'checked' : '' }}>
                <label for="limit_order">Limit to order over</label>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group transparent">
                <span class="input-group-addon">
                    Rs
                </span>
                <input type="text" class="form-control" id="amount" name="amount" value="{{ $shipping_zone->shippingMethods->where('shipping_method_id',1)->first()->rate }}" {{ $shipping_zone->shippingMethods->where('shipping_method_id',1)->first()->rate ? '' : 'disabled' }}>
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
              <input type="email" name="flat_rate_email" id="flat_rate_email" class="form-control">
            </div>
            <div class="form-group">
              <label for="display_name">Display Name</label>
              <input type="text" name="display_name" id="display_name" class="form-control" value="{{ $shipping_zone->shippingMethods->where('shipping_method_id',2)->first()->display_name }}">
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

<!-- start weight modal -->
<div class="modal fade slide-up" id="modalWeightOrder" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5>Ship by Weight / Order Total <span class="semi-bold">Settings</span></h5>
        </div>
        <div class="modal-body">
          <form id="formWeightOrder" action="{{ route('shipping.delivery.rates.update', $shipping_zone->shippingMethods->where('shipping_method_id',3)->first()->id) }}" method="post" autocomplete="off">
            <div class="form-group">
              <label for="ship_by_weight_email">shipper email</label>
			  <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="This would allow the system to automatically send an email to the shipper when the 'notified shipper' status is set in order management.">
                <i class="fa fa-question-circle"></i>
              </span>
              <input type="email" name="ship_by_weight_email" id="ship_by_weight_email" class="form-control">
            </div>
            <div class="form-group">
              <label for="display_weight">Display Name</label>
              <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="This is the name your customers will see for each shipping method on check out. Please enter your name for this shipping method E.g. 'Express shipping' or 'Standard shipping (5-7 days)'">
                <i class="fa fa-question-circle"></i>
              </span>
              <input type="text" name="display_weight" id="display_weight" class="form-control" value="{{ $shipping_zone->shippingMethods->where('shipping_method_id',3)->first()->display_name }}">
            </div>
            <div class="form-group">
              <label for="charge_shipping">Charge Shipping</label>
              <select name="charge_shipping" id="charge_shipping" class="form-control">
                <option value="1" {{ $shipping_zone->shippingMethods->where('shipping_method_id',3)->first()->restriction_type == 1 ? 'selected' : '' }}>By weight</option>
                <option value="0" {{ $shipping_zone->shippingMethods->where('shipping_method_id',3)->first()->restriction_type == 0 ? 'selected' : '' }}>By order total</option>
              </select>
            </div>
            <hr>
            <strong>Ranges</strong>
            <div id="rangeBlock"> 
            @if($shipping_zone->shippingMethods->where('shipping_method_id',3)->first()->deliveryRates->count())
              @if($shipping_zone->shippingMethods->where('shipping_method_id',3)->where('restriction_type', 1)->count())
              @include ('product::partials._weight_range', ['rates' => $shipping_zone->shippingMethods->where('shipping_method_id',3)->first()->deliveryRates])
              @else
              @include ('product::partials._price_range', ['rates' => $shipping_zone->shippingMethods->where('shipping_method_id',3)->first()->deliveryRates])
              @endif
            @else
              <div class="row text-center">
                <div class="col-sm-3">
                  <label for="lower_0">From</label>
                </div>
                <div class="col-sm-3">
                  <label for="upper_0">To</label>
                </div>
                <div class="col-sm-3">
                  <label for="cost_0">Cost</label>
                </div>
              </div>
              <div id="range_0" class="row align-items-end row-item">
                <div class="col-sm-3 form-group">
                  
                  <div class="input-group transparent">
                    <input type="text" class="form-control autonumeric" id="lower_0" name="range[0][lower]">
                    <span class="input-group-addon">KG</span>
                  </div>
                </div>
                <div class="col-sm-3 form-group">
                  
                  <div class="input-group transparent">
                    <input type="text" class="form-control autonumeric" id="upper_0" name="range[0][upper]">
                    <span class="input-group-addon">KG</span>
                  </div>
                </div>
                <div class="col-sm-3 form-group">
                  
                  <div class="input-group transparent">
                    <span class="input-group-addon">Rs</span>
                    <input type="text" class="form-control autonumeric" id="cost_0" name="range[0][cost]">
                  </div>
                </div>
                <div class="col-sm-3 form-group action-group">
                  <button class="btn btn-outline-blue btn-add" type="button"><i class="fa fa-plus"></i></button>
                </div>
              </div>
            @endif
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <div class="form-group">
            <button type="button" class="btn btn-action-cancel"  data-dismiss="modal">Cancel</button>
            <button type="button" id="btnWeightOrder" class="btn btn-action-save">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end  weight modal -->

<!-- start store pickup modal -->
<div class="modal fade slide-up disable-scroll" id="modalStorePickUp" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog ">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5>Store Pickup <span class="semi-bold">Settings</span></h5>
        </div>
        <div class="modal-body">
          <form id="formStorePickup" action="{{ route('shipping.store_pickup.update', $shipping_zone->shippingMethods->where('shipping_method_id',4)->first()->id) }}" method="post" autocomplete="off">
            <div class="form-group">
              <label for="store_pickup_email">shipper email</label>
              <input type="email" name="store_pickup_email" id="store_pickup_email" class="form-control">
            </div>
            <div class="form-group">
              <label for="display_name">Display Name</label>
              <input type="text" name="display_name" id="display_name" class="form-control" value="{{ $shipping_zone->shippingMethods->where('shipping_method_id',4)->first()->display_name }}">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <div class="form-group">
            <button type="button" class="btn btn-action-cancel"  data-dismiss="modal">Cancel</button>
            <button type="button" id="btnStorePickup" class="btn btn-action-save">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end store pickup modal -->

@endsection

@section('scripts') 
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/plugins/switchery/js/switchery.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-autonumeric/autoNumeric.js') }}"></script>
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





