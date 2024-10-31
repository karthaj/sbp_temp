@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">stock</li>
  <li class="breadcrumb-item"><a href="{{ route('store.requests') }}">requests</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <h1 class="section-title">Requests</h1>
      </div>
      <transfer endpoint="{{ url('merchant/datatable/stock/requests') }}" transferurl="{{ route('stock.request.create') }}"
      :stores="{{ $stores }}" label="Create Stock Request"></transfer>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 


@endsection



