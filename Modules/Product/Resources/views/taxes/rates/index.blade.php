@extends('layouts.zpanel')

@section('styles')



@endsection

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item">store</li>
  <li class="breadcrumb-item">tax</li>
  <li class="breadcrumb-item active"><a href="{{ route('tax.rates.index') }}">tax rates</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <a href="{{ route('tax.zones.index') }}" class="bold"><i class="pg-arrow_left"></i>Tax zones</a>
        <h1 class="section-title">Tax Rates</h1>
      </div>
      <taxrate-table endpoint="{{ url('merchant/store/datatable/tax-rates') }}" route="{{ route('tax.rates.create') }}"><taxrate-table>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/tax_form.js') }}"></script>

@endsection

@section('page_scripts')

@endsection


