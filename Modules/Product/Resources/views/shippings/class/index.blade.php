@extends('layouts.zpanel')


@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item">Store</li>
  <li class="breadcrumb-item active"><a href="{{ route('shipping.index') }}">Shipping class</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <a href="{{ route('shipping.index') }}" class="bold"><i class="pg-arrow_left"></i>Shipping zones</a>
          <h1 class="section-title">Shipping Classes</h1>
      </div>
      <shipping-class-table endpoint="{{ url('merchant/store/datatable/shipping-classes') }}" shipping-class-route="{{ route('shipping.classes.create') }}"><shipping-class-table>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/shipping_class_form.js') }}"></script>

@endsection



