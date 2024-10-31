@extends('layouts.zpanel')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')

<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('analytics.index') }}">analytics</a></li>
</ol>
    
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header d-flex flex-wrap justify-content-between align-items-end">
        <h1 class="section-title">Overview</h1>
        <rangedate-picker class="mb-3"></rangedate-picker>
      </div>
      <div class="card-block">
        <div class="row">
          <div class="col-md-4">
            <store-visit dimension="sb-chart-mini"></store-visit>
          </div>
          <div class="col-md-4">
            <chart-orders></chart-orders>
          </div>
          <div class="col-md-4">
            <chart-sales></chart-sales>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endsection




