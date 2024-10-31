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
  <li class="breadcrumb-item">store</li>
  <li class="breadcrumb-item"><a href="{{ route('cod.index') }}">cash on delivery</a></li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header">
    <h1 class="section-title">Payment methods</h1>
  </div>
  <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
        <h6 class="ui-subheader">
         Accept payments
        </h6>
        <div class="p-r-30">
          <p>
            Enable Cash on Delivery into your store to accept payments for orders.
          </p>
        </div>
      </div>
      <div class="col-lg-8 sm-no-padding">
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default">
                      <div class="card-header">
                        <div class="card-title"><span class="font-weight-bold">cash on delivery (COD)</span>
                        </div>
                      </div>
                      <div class="card-block">
                        <form action="{{ route('cod.store') }}" method="post" class="sodirty">
                          {{ csrf_field() }}
                          <div class="form-group{{ $errors->has('shipping_zone') ? ' has-danger' : '' }}">
                            <label for="shipping_zone">shipping zone</label>
                            <select name="shipping_zone" id="shipping_zone" class="full-width form-control{{ $errors->has('shipping_zone') ? ' form-control-danger' : '' }}" data-init-plugin="select2" required>
                              <option value="">Select a zone</option>
                              @if($zones->count())
                                @foreach($zones as $zone)
                                  <option value="{{ $zone->id }}">
                                    {{ $zone->zone_name }}
                                  </option>
                                @endforeach
                              @endif
                            </select>
                            @if($errors->has('shipping_zone'))
                              <div class="form-control-feedback">{{ $errors->first('shipping_zone') }}</div>
                            @endif
                          </div>

                          <div class="form-group{{ $errors->has('surcharge') ? ' has-danger' : '' }}">
                            <label for="surcharge">surcharge</label>
                            <div class="input-group">
                              <div class="input-group-addon">
                                {{ session('store')->setting->currency->iso_code }}
                              </div>
                              <input type="text"  class="form-control autonumeric{{ $errors->has('surcharge') ? ' form-control-danger' : '' }}" name="surcharge" value="{{ old('surcharge') }}" id="surcharge">
                            </div>
                            
                            @if($errors->has('surcharge'))
                              <div class="form-control-feedback">{{ $errors->first('surcharge') }}</div>
                            @endif
                          </div>


                          <div class="form-group{{ $errors->has('payment_info') ? ' has-danger' : '' }}">
                            <label for="payment_info">Payment Instructions</label>
                            <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="If a customer chooses to pay via COD then he will be shown the text you type into this box once he completes his order. You should include your payment terms and other information here so the customer can pay for his order by cash on delivery.">
                              <i class="fa fa-question-circle"></i>
                            </span>
                            <textarea name="payment_info" id="payment_info" cols="30" rows="4" class="form-control{{ $errors->has('payment_info') ? ' form-control-danger' : '' }}" required>{{ old('payment_info') }}</textarea>

                            @if($errors->has('payment_info'))
                              <div class="form-control-feedback">{{ $errors->first('payment_info') }}</div>
                            @endif
                          </div>

                          <div class="form-group">
                            <label for="status">Activate</label><br>
                            <input type="checkbox" id="status" name="status" data-init-plugin="switchery" data-size="small" data-color="info" value="1" checked="checked">
                          </div>

                          @include ('zpanel.partials._form_actions', ['path' => route('payments.index')])
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

@endsection

@section('scripts') 
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/switchery/js/switchery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-autonumeric/autoNumeric.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cod.js') }}"></script>
@endsection

@section('page_scripts')
<script>
  $(document).ready(function() {
    cod.init()
  });
</script>
@endsection
