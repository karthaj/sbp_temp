@extends('layouts.zpanel')

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">orders</li>
  <li class="breadcrumb-item active"><a href="{{ route('orders.abandoned.carts.index') }}">abandoned carts</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header row">
        <div class="col-lg-10 col-md-9 col-sm-8">
            <h1 class="section-title">Abandoned Carts</h1>
        </div>
      </div>
      <cart-table endpoint="{{ url('merchant/orders/abandoned-carts/datatable') }}"><cart-table>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/order.js') }}"></script>

@endsection