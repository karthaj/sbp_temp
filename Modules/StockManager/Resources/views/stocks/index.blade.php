@extends('layouts.zpanel')

@section('styles')


@endsection

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item">Product</li>
  <li class="breadcrumb-item active"><a href="{{ route('stockmanager.index') }}">stock overview</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header row">
        <div class="col-lg-10 col-md-9 col-sm-8">
            <h1 class="section-title">Stock Overview</h1>
        </div>
      </div>
      <div class="card-block">
        <div class="row">
          <div class="col">
            <div data-pages="card" class="card card-default">
            	<div id="example-1-result" class="backgrid-container">
            		
            	</div>
            </div>
          </div>
    	</div>
      </div>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/backgrid.js')}}"></script>

@endsection

@section('page_scripts')

@endsection



