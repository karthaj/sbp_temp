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
  <li class="breadcrumb-item">Product</li>
  <li class="breadcrumb-item active"><a href="{{ url('admin/categories') }}">Categories</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header row">
        <div class="col-lg-10 col-md-9 col-sm-8">
            <h1 class="section-title">Categories</h1>
        </div>
      </div>
      <category-table endpoint="{{ url('/merchant/categories/datatable') }}" route="{{ route('categories.new') }}"></category-table> 
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/classie/classie.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-dynatree/jquery.dynatree.min.js') }}"></script>
{{-- <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/froala-editor@2.9.1/js/froala_editor.min.js'></script> --}}
<script src="{{ asset('assets/plugins/redactor/redactor3.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/category_form.js') }}" type="text/javascript"></script>


@endsection





