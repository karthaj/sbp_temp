@extends('layouts.zpanel')

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">store</li>
  <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">payments</a></li>
  <li class="breadcrumb-item active"><a href="{{ route('cod.index') }}">cash on delivery</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <h1 class="section-title">Cash On Deliveries</h1>
      </div>
      <cod-table endpoint="{{ url('merchant/store/datatable/cashondeliveries') }}" route="{{ route('cod.create') }}"><cod-table>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/cod.js') }}"></script>

@endsection



