@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/cp/bootstrap-colorpicker.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
<!-- BEGIN PlACE PAGE CONTENT HERE -->

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">Product</li>
  <li class="breadcrumb-item"><a href="{{ route('attributes.index') }}">variants</a></li>
  <li class="breadcrumb-item">add</li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header ">
    <h1 class="section-title">Create an Option</h1>
  </div>

  <div class="m-0 row card-block">
      <div class="col-lg-4 no-padding">
        <div class="p-r-30">
          <h6 class="ui-subheader">Option Name and Type</h6>
          <p>
            Options such as size, color, etc can be attached to products in your store to allow for more flexible purchasing by your customers.
          </p>
        </div>
      </div>

      <div class="col-lg-8 sm-no-padding">
        <manage-attribute endpoint="{{ route('attributes.store') }}" cancel-endpoint="{{ route('attributes.index') }}" redirect-to="{{ route('attributes.index') }}"></manage-attribute>
      </div>
  </div>
</div>

<!-- END PLACE PAGE CONTENT HERE -->


@endsection

@section('scripts') 
<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/classie/classie.js') }}"></script>
<script src="{{ asset('assets/plugins/cp/bootstrap-colorpicker.js') }}" type="text/javascript"></script>
@endsection

@section('page_scripts')
<script>
  
  //
  
</script>
@endsection




