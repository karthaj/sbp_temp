@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">Stock</li>
  <li class="breadcrumb-item"><a href="{{ route('product.stocks') }}">product stocks</a></li>
  <li class="breadcrumb-item">master</li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <h1 class="section-title">Master Stock Management</h1>
      </div>

      @if(session('store')->storeLocations->count() > 1)

        <stock-management endpoint="{{ url('merchant/warehouse/stocks') }}" addstockurl="{{ route('warehouse.stocks.add') }}"
        cancelendpoint="{{ route('product.stocks') }}"></stock-management>

      @else
        
        <stock-management endpoint="{{ url('merchant/store/stocks') }}" addstockurl="{{ route('warehouse.stocks.add') }}"
        cancelendpoint="{{ route('product.stocks') }}"></stock-management>
        
      @endif 

    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/stock.js') }}"></script>
<script src="{{ asset('assets/plugins/classie/classie.js') }}"></script>

@endsection

@section('page_scripts')

@endsection



