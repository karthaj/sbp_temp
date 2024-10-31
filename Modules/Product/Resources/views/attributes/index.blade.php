@extends('layouts.zpanel')

@section('styles')

<link href="{{ asset('assets/plugins/datatables-responsive/css/datatables.responsive.css') }}" rel="stylesheet" type="text/css" media="">
<link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.css') }}">

@endsection

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">Product</li>
  <li class="breadcrumb-item active"><a href="{{ route('attributes.index') }}">variants</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header row">
        <div class="col-lg-10 col-md-9 col-sm-8">
            <h1 class="section-title">Variants</h1>
        </div>
      </div>
      <ul class="nav nav-tabs nav-tabs-simple" role="tablist" data-init-reponsive-tabs="dropdownfx">
        @if(auth()->user()->can('view variations'))
          <li class="nav-item">
            <a class="active" data-toggle="tab" role="tab" data-target="#tabVariations" href="#">Variations</a>
          </li>
        @endif
        @if(auth()->user()->can('view variant sets'))
          <li class="nav-item">
            <a href="#" data-toggle="tab" role="tab" data-target="#tabVariationSets">Variation Sets</a>
          </li>
        @endif
      </ul>
      <attribute-table endpoint="{{ url('/merchant/attributes/datatable') }}" endpointoptionsets="{{ url('/merchant/datatable/variation-sets') }}" optionstoreuri="{{ route('attributes.create') }}" optionsetstoreuri="{{ route('attributes.sets.create') }}"></attribute-table> 
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/classie/classie.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/attribute_form.js') }}"></script>
<script src="{{ asset('assets/plugins/cp/bootstrap-colorpicker.js') }}" type="text/javascript"></script>
@endsection




