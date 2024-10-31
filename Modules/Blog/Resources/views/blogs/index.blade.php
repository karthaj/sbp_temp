@extends('layouts.zpanel')

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blogs</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header row">
        <div class="col-lg-10 col-md-9 col-sm-8">
            <h1 class="section-title">Blog posts</h1>
        </div>
      </div>
      <blog-table endpoint="{{ url('merchant/blogs/datatable') }}" route="{{ route('blogs.create') }}"><blog-table>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 

<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>

@endsection



