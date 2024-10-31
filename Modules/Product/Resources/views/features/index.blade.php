@extends('layouts.zpanel')

@section('styles')



@endsection

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item">Products</li>
  <li class="breadcrumb-item active"><a href="{{ route('features.index') }}">features</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header row">
        <div class="col-lg-10 col-md-9 col-sm-8">
            <h1 class="section-title">Features</h1>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4">
            <a href="{{ route('features.create') }}" class="btn btn-primary btn-custom-v1 save-staff btn-block mt-4 mb-4">Add a Feature</a>
        </div>
      </div>
      <feature-table endpoint="{{ url('features/datatable') }}" data="{{ $feature->toJson() }}"></feature-table>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/feature_form.js') }}"></script>
<script src="{{ asset('assets/plugins/classie/classie.js') }}"></script>

@endsection




