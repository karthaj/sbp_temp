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
  <li class="breadcrumb-item"><a href="{{ route('tax.rates.index') }}">tax zone</a></li>
</ol>

<!-- END BREADCRUMB --> 

<div  class="card card-transparent">
  <div class="card-header ">
    <a href="{{ route('tax.rates.index') }}" class="bold"><i class="pg-arrow_left"></i>Tax rates</a>
    <h1 class="section-title">Add a Tax Rate</h1>
  </div>
  @if ($errors->any())
    <div class="alert alert-danger bordered" role="alert">
      <button class="close" data-dismiss="alert"></button>
      @if($errors->count() == 1)
        <p class="pull-left"><strong>Note:</strong> There is 1 error with this tax rate.</p>
      @else
        <p class="pull-left"><strong>Note:</strong> There are {{ $errors->count() }} errors with this tax rate.</p>
      @endif 
    </div>
  @endif
  <div class="m-0 row card-block">
      <div class="col-lg-4 no-padding">
        <div class="p-r-30">
          <p>
           Tax rates let you specify different tax rates depending on your customer's locations.
          </p>
        </div>
      </div>
      <div class="col-lg-8 sm-no-padding">
        <form id="taxRateForm" action="{{ route('tax.rates.store') }}" method="post" autocomplete="off" class="sodirty">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default">
                      <div class="card-block">
                          <div class="row">
                              <div class="col-sm-6 form-group">
                                <label for="tax_zone">Tax Zone</label>
                                <select id="tax_zone" class="full-width{{ $errors->has('tax_zone') ? ' error' : '' }}" name="tax_zone" data-init-plugin="select2">
                                @if($tax_zones->count())
                                  @foreach($tax_zones as $zone)
                                  <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                  @endforeach
                                @endif
                                </select>
                              </div>
                              <div class="col-sm-6 form-group">
                                  <label for="tax_name">Tax Name</label>
                                  <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="A name that describes this tax rate (such as GST, PST, etc).">
                                    <i class="fa fa-question-circle"></i>
                                  </span>
                                  <input type="text" id="tax_name" class="form-control{{ $errors->has('tax_name') ? ' error' : '' }}" name="tax_name" value="{{ old('tax_name') }}">
                                  @if($errors->has('tax_name'))
                                  <label id="tax_name-error" class="error" for="tax_name">{{ $errors->first('tax_name') }}</label>
                                  @endif
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12 form-group">
                              <label for="">Tax Class Rates</label>
                              @if($tax_classes->count())
                                @foreach($tax_classes as $class)
                                <div class="row align-items-center">
                                  <div class="col-sm-2 pl-0 form-group">
                                    <input type="number" class="form-control{{ $errors->has('rates.'.$class->id) ? ' error' : '' }}" min="0" name="rates[{{ $class->id }}]" value="{{ old('rates.'.$class->id) }}">
                                  </div>
                                  <div class="col-sm-10 form-group" class="form-control">
                                    % for products marked as  {{ $class->name }}
                                  </div>
                                </div>
                                @if($errors->has('rates.'.$class->id))
                                  <label class="error">{{ $errors->first('rates.'.$class->id) }}</label>
                                @endif
                                @endforeach
                              @endif
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-4 form-group">
                              <label for="calculation_order">Calculation order</label>
                              <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="Enter a number which represents the calculation order in which this tax rate should be calculated against other tax rates in this zone. For tax rates in this zone that share the same calculation order, tax calculations be added together. For tax rates in this zone with different calculation order, the tax calculations will be from lowest to highest. Ex: (1 - 5)">
                                <i class="fa fa-question-circle"></i>
                              </span>
                              <input type="number" class="form-control{{ $errors->has('calculation_order') ? ' error' : '' }}" name="calculation_order" id="calculation_order" value="0" min="0">
                              @if($errors->has('calculation_order'))
                                <label id="calculation_order-error" class="error" for="calculation_order">{{ $errors->first('calculation_order') }}</label>
                              @endif
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12 form-group">
                              <div class="checkbox check-info">
                                <input type="checkbox" checked="checked" value="1" id="status" name="status">
                                <label for="zone_status">Active</label>
                              </div>
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
    form.validateRateForm()
  });
</script>
@endsection





