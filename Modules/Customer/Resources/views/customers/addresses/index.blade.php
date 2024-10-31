@extends('layouts.zpanel')

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">Customers</li>
  <li class="breadcrumb-item active"><a href="{{ route('customers.addresses.index') }}">Addresses</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header row">
        <div class="col-lg-10 col-md-9 col-sm-8">
            <h1 class="section-title">Addresses</h1>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4">
            <a href="{{ route('customers.addresses.add') }}" class="btn btn-primary btn-custom-v1 btn-block mt-4 mb-4">Add an address</a>
        </div>
      </div>
      <address-table endpoint="{{ url('customers/addresses/datatable') }}"><address-table>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/customer.js') }}"></script>

@endsection



