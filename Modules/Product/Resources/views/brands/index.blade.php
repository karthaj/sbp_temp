@extends('layouts.zpanel')

@section('styles')



@endsection

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">Product</li>
  <li class="breadcrumb-item active"><a href="{{ route('brands.index') }}">brands</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header row">
        <div class="col-lg-10 col-md-9 col-sm-8">
            <h1 class="section-title">Brands</h1>
        </div>
      </div>
      <brand-table endpoint="{{ url('merchant/brands/datatable') }}" route="{{ route('brands.create') }}"><brand-table>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/brand_form.js') }}"></script>

@endsection



