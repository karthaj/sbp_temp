@extends('layouts.zpanel')

@section('styles')

<link href="{{ asset('assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/jquery-datatable/extensions/FixedColumns/css/dataTables.fixedColumns.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/datatables-responsive/css/datatables.responsive.css') }}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{ asset('assets/plugins/summernote/css/summernote.css') }}" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.css') }}">

@endsection

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">Settings</a></li>
  <li class="breadcrumb-item"><a href="{{ route('account.users') }}">Users</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <h1 class="section-title">Users</h1>
      </div>
      <user-table endpoint="{{ url('merchant/account/datatable/users') }}" route="{{ route('account.users.create') }}"
      :accounts-limit="{{ session('store')->plan->accounts_limit }}" :accounts="{{ session('store')->users()->where('master', 0)->count() }}"></user-table> 
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/classie/classie.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/color-picker/js/colorpicker.js') }}"></script>
@endsection

@section('page_scripts')
<script>

  
</script>

@endsection



