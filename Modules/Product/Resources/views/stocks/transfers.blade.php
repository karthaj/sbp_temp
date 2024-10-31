@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">stock</li>
  <li class="breadcrumb-item"><a href="{{ route('store.transfers') }}">transfers</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <h1 class="section-title">Transfers</h1>
      </div>
      <transfer endpoint="{{ url('merchant/datatable/stock/transfers') }}" transferurl="{{ route('stock.transfer.create') }}"
      :stores="{{ $stores }}" label="Create Stock Transfer"></transfer>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 


@endsection



