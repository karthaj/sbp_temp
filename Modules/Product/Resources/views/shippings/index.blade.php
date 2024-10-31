@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/redactor/redactor.min.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('assets/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('settings.index') }}">settings</a></li>
  <li class="breadcrumb-item active"><a href="{{ route('shipping.index') }}">shipping</a></li>
</ol>
<!-- END BREADCRUMB --> 

<div  class="card card-transparent">
  <div class="card-header">
    <h1 class="section-title">store pickup</h1>
  </div>
  <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
        <div class="p-r-30">
          <p>Allow your customers to choose the option of picking up the product from the store instead of getting it delivered.</p>
        </div>
      </div>
      <div class="col-lg-8 sm-no-padding">
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div class="card card-default">
                      <div class="card-block">
                        <form action="{{ route('shipping.config.storepickup') }}" method="post">
                          {{ csrf_field() }}
                          <input type="hidden" name="store_pickup" value="0">
                          <div class="form-group{{ $errors->has('store_pickup') ? ' has-danger' : '' }}">
                            <label for="store_pickup">Enable store pickup</label>
                            <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="info" id="store_pickup" class="form-control{{ $errors->has('store_pickup') ? ' form-control-danger' : '' }}" name="store_pickup"
                            value="1" @if(old('store_pickup', session('store')->setting->enable_store_pickup)) checked @endif>
                            @if($errors->has('store_pickup'))
                              <div class="form-control-feedback">{{ $errors->first('store_pickup') }}</div>
                            @endif
                          </div>
                          <div id="storePickup" class="{{ !old('store_pickup', session('store')->setting->enable_store_pickup) ? 'collapse' : ''}}">
                            <div class="form-group{{ $errors->has('display_name') ? ' has-danger' : '' }}">
                              <label for="display_name">Display Name</label>
                              <input type="text" name="display_name" id="display_name" class="form-control{{ $errors->has('display_name') ? ' form-control-danger' : '' }}" value="{{ old('display_name', session('store')->setting->store_pickup_display_name) }}">
                              @if($errors->has('display_name'))
                                <div class="form-control-feedback">{{ $errors->first('display_name') }}</div>
                              @endif
                            </div>
                            <div class="form-group">
                              <label for="instructions">Instructions</label>
                              <textarea name="instructions" id="instructions" class="form-control" cols="30" rows="10">{{ old('instructions', session('store')->setting->store_pickup_instructions) }}</textarea>
                            </div>
                          </div>
                          <div class="form-group">
                            <button type="submit" class="btn btn-info">save</button>
                          </div>
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

@if(auth()->user()->can('view shipping zones'))
  <div class="card card-transparent">
    <div class="card-header">
      <h1 class="section-title">shipping zones</h1>
    </div>
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <div class="p-r-30">
            <p>
             Set up your delivery zones and the rates to charge your customers.
            </p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div class="card card-default">
                      <div class="card-header">
                        <div class="card-title d-flex align-items-center justify-content-between">
                          <span class="font-weight-bold">shipping zones</span>
                          @if(auth()->user()->can('add shipping zones'))
                            <a href="{{ route('shipping.zones.create') }}" class="btn btn-action-add">add shipping zone</a>
                          @endif
                        </div>
                      </div>
                      <div class="card-block">
                        @if($shipping_zones->count())
                          @foreach($shipping_zones as $zone)
                            <div class="d-flex justify-content-between align-items-center my-3">
                              <span>
                                @if($zone->zone_type === 'country')
                                  <small class="mr-2 text-muted">Country</small>
                                @elseif($zone->zone_type === 'state')
                                  <small class="mr-2 text-muted">State</small>
                                @elseif($zone->zone_type === 'zip_code')
                                  <small class="mr-2 text-muted">Postal Code</small>
                                @endif
                                <strong>- {{ $zone->zone_name }}</strong>
                              </span> 
                              @if(auth()->user()->can('edit shipping zones') || auth()->user()->can('delete shipping zones'))
                                <div class="dropdown">
                                  @if(auth()->user()->can('edit shipping zones'))
                                    <a href="{{ route('shipping.edit', $zone) }}">Edit</a>
                                  @endif
                                  @if(auth()->user()->can('delete shipping zones'))
                                    <a data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                      <i class="aapl-chevron-down-circle ml-2"></i>
                                    </a>
                                    <div class="dropdown-menu" role="menu">
                                      <a href="{{ route('shipping.zone.delete', $zone) }}" class="dropdown-item delete-shipping-zone">Delete</a>
                                    </div>
                                  @endif
                                </div>
                              @endif
                            </div>
                            @if($zone->shippingMethod)
                              <div class="media ml-4">
                                <img class="align-self-center mr-3" src="https://via.placeholder.com/40" alt="Generic placeholder image">
                                <div class="media-body">
                                  <p class="mb-0"><strong>{{ $zone->shippingMethod->display_name }}</strong></p>
                                  @if($zone->status)
                                    <small class="badge badge-active">active</small>
                                  @else
                                    <small class="badge badge-inactive">inactive</small>
                                  @endif
                                </div>
                              </div>
                            @endif
                            @if(!$loop->last)
                              <hr>
                            @endif
                          @endforeach
                        @else
                          <p>No shipping zones created</p>
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
@endif

@if(auth()->user()->can('view shipping classes'))
  <div class="card card-transparent">
    <div class="card-header">
      <h1 class="section-title">shipping classes</h1>
    </div>
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <div class="p-r-30">
            <p>
             A shipping class can be assigned to a particular product. This can be used to restrict a certain shipping option said product. E.g. A product which is too large/heavy to be sent by a courier company can be assigned a rate for delivery by freight/truck.
            </p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div id="manualPayments" class="card card-transparent">
                    <div class="card-block no-padding">
                      <div class="card card-default">
                        <div class="card-header">
                          <div class="card-title d-flex align-items-center justify-content-between">
                            <span class="font-weight-bold">shipping classes</span>
                            @if(auth()->user()->can('add shipping classes'))
                              <a href="{{ route('shipping.classes.create') }}" class="btn btn-action-add">add shipping class</a>
                            @endif
                          </div>
                        </div>
                        <div class="card-block">
                          @if($shipping_classes->count())
                          <ul class="list-group list-group-flush">
                            @foreach($shipping_classes as $class)
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                  <span class="mr-5">{{ $class->name }}</span>
                                  @if($class->status)
                                    <span class="badge badge-active">active</span>
                                  @else
                                    <span class="badge badge-inactive">inactive</span>
                                  @endif
                                </div>
                                @if(auth()->user()->can('edit shipping classes') || auth()->user()->can('delete shipping classes'))
                                  <div class="dropdown">
                                    @if(auth()->user()->can('edit shipping classes'))
                                      <a href="{{ route('shipping.classes.edit', $class) }}">Edit</a>
                                    @endif
                                    @if(auth()->user()->can('delete shipping classes'))
                                      <a data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                        <i class="aapl-chevron-down-circle ml-2"></i>
                                      </a>
                                      <div class="dropdown-menu" role="menu">
                                        <a href="{{ route('shipping.classes.delete', $class) }}" class="dropdown-item delete-shipping-class">Delete</a>
                                      </div>
                                    @endif
                                  </div>
                                @endif
                              </li>
                            @endforeach
                          </ul>
                          @else
                            <p class="text-center">No shipping classes created.</p>
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
@endif

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/shipping_form.js') }}"></script>
<script src="{{ asset('assets/plugins/switchery/js/switchery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/redactor3.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/fontcolor/fontcolor.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/alignment/alignment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/table/table.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/fontfamily/fontfamily.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/fontsize/fontsize.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/imagemanager/imagemanager.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/inlinestyle/inlinestyle.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/video/video.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/widget/widget.min.js') }}" type="text/javascript"></script>

@endsection

@section('page_scripts')
<script>
  $(document).ready(function() {
    form.initRedactor();
  });
</script>
@endsection


