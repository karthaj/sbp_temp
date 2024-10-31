@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">Stock</li>
  <li class="breadcrumb-item"><a href="{{ route('store.transfers') }}">transfers</a></li>
  <li class="breadcrumb-item">add</li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <h1 class="section-title">Transfer Stocks</h1>
      </div>
      <transfer-stock searchendpoint="{{ route('transfer.product.search') }}" endpoint="{{ route('transfer.product.get') }}" :stores="{{ $stores }}" transfer-endpoint="{{ route('stock.transfer') }}"></transfer-stock>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.12/handlebars.min.js"></script>
<script src="{{ asset('assets/plugins/bootstrap-typehead/typeahead.bundle.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-typehead/typeahead.jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/classie/classie.js') }}"></script>
@endsection

@section('page_scripts')
  
@endsection



