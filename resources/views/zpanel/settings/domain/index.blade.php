@extends('layouts.zpanel')

@section('styles')

<link href="{{ asset('assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/datatables-responsive/css/datatables.responsive.css') }}" rel="stylesheet" type="text/css" media="screen" />

@endsection

@section('content')
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('settings.domain.index') }}">domain</a></li>
</ol>
<!-- END BREADCRUMB -->

  <div id="app" class="card card-transparent">
    <div class="card-header">
      <h1 class="section-title">Connect Domain</h1>
    </div>

    <connect-domain></connect-domain>

  </div>
@endsection