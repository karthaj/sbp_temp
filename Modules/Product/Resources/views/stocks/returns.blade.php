@extends('layouts.zpanel')


@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">stock</li>
  <li class="breadcrumb-item"><a href="{{ route('store.returns') }}">returns</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <h1 class="section-title">Stock Returns</h1>
      </div>
      <stock-return endpoint="{{ url('merchant/datatable/stock/returns') }}"></stock-return>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 


@endsection



