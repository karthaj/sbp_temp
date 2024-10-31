@extends('layouts.zpanel')

@section('styles')


@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">stock</li>
  <li class="breadcrumb-item"><a href="{{ route('store.returns') }}">returns</a></li>
  <li class="breadcrumb-item">add</li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <h1 class="section-title">Return Stocks</h1>
      </div>
      <return-stock searchendpoint="{{ route('transfer.product.search') }}" endpoint="{{ route('stock.return.product') }}" :stores="{{ $stores }}" transfer-endpoint="{{ route('stock.return') }}"></return-stock>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.12/handlebars.min.js"></script>
<script src="{{ asset('assets/plugins/bootstrap-typehead/typeahead.bundle.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-typehead/typeahead.jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/classie/classie.js') }}"></script>
@endsection



