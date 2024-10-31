@extends('layouts.zpanel')

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">orders</li>
  <li class="breadcrumb-item active"><a href="{{ route('orders.return.create') }}">add</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header row">
        <div class="col-lg-10 col-md-9 col-sm-8">
          <a href="{{ route('orders.return.index') }}" class="bold"><i class="pg-arrow_left"></i>Returns</a>
          <h1 class="section-title">Add Return</h1>
        </div>
      </div>
      <add-return endpoint="{{ route('orders.return.invoice') }}" add-return-endpoint="{{ route('orders.return.store') }}"><add-return>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/order.js') }}"></script>

@endsection