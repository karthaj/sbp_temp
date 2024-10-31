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
  <li class="breadcrumb-item">class</li>
  <li class="breadcrumb-item">edit</li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent mb-0">
  <div class="card-header ">
    <h1 class="section-title">Edit {{ $shipping_class->name }}</h1>
  </div>
  <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
        <div class="p-r-30">
          <p>
           A shipping class can be assigned to a particular product. This can be used to restrict a certain shipping option said product. E.g. A product which is too large/heavy to be sent by a courier company can be assigned a rate for delivery by freight/truck.
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
                        <form id="shippingClassForm" action="{{ route('shipping.classes.update', $shipping_class->slug) }}" method="post" autocomplete="off">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <input type="hidden" name="shipping_class" value="{{ $shipping_class->id }}">
                          <div class="row">
                              <div class="col-sm-6 form-group">
                                  <label for="name">Class Name</label>
                                  <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' error' : '' }}" name="name" value="{{ $shipping_class->name }}" required>
                                  @if($errors->has('name'))
                                  <label id="name-error" class="error" for="name">{{ $errors->first('name') }}</label>
                                  @endif
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-6 form-group">
                              <label for="shipping_zones">Shipping Zones</label>
                              <select id="shipping_zones" class="full-width form-control{{ $errors->has('shipping_zones') ? ' error' : '' }}" name="shipping_zones[]" multiple>
                              @if($shipping_zones->count())
                                @foreach($shipping_zones as $zone)
                                <option value="{{ $zone->id }}" {{ $shipping_class->shippingZones->contains($zone->id) ? 'selected' : '' }}>{{ $zone->zone_name }}</option>
                                @endforeach
                              @endif
                              </select>
                              @if($errors->has('shipping_zones'))
                              <label id="shipping_zones-error" class="error" for="shipping_zones">{{ $errors->first('shipping_zones') }}</label>
                              @endif
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12 form-group">
                              <div class="checkbox check-info">
                                <input type="checkbox" {{ $shipping_class->status ? 'checked' : '' }} value="1" id="status" name="status">
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
<script type="text/javascript" src="{{ asset('assets/js/shipping_class_form.js') }}"></script>
@endsection

@section('page_scripts')
<script>
  $(document).ready(function() {
    form.init();
  });
</script>
@endsection
