@extends('layouts.zpanel')

@section('styles')


@endsection

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">stock</li>
  <li class="breadcrumb-item active"><a href="{{ route('product.stocks') }}">product stocks</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header row justify-content-between">
        <div class="col-lg-8 col-md-6 col-sm-4">
          <h1 class="section-title">Stock Management</h1>
        </div>
        @if(session('store')->stocks()->limit(1)->count())
        <div class="col-lg-3 col-md-3 col-sm-4">
            <a href="{{ route('product.stocks.create') }}" class="btn btn-action-add btn-block mt-4 mb-4">Add / Remove master stock</a>
        </div>
        @endif
      </div>
      <stock-table endpoint="{{ url('merchant/datatable/stocks') }}"  endpointstocktransfer="{{ url('merchant/datatable/stock-history') }}" productimage="{{ asset('stores').'/'.session('store')->domain.'/product/' }}" defaultimage="{{ asset('assets/img/ProductDefault.gif') }}" :stores="{{ $store_locations }}"></stock-table>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-autonumeric/autoNumeric.js') }}"></script>
<script src="{{ asset('assets/js/stock.js') }}"></script>
<script src="{{ asset('assets/plugins/classie/classie.js') }}"></script>

@endsection

@section('page_scripts')

@endsection



