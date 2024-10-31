@extends('layouts.zpanel')

@section('styles')

<link href="{{ asset('assets/plugins/dropzone/css/dropzone.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item">Modules</li>
  <li class="breadcrumb-item active"><a href="{{ route('plugin.add') }}">Add</a></li>
</ol>
<!-- END BREADCRUMB --> 
<!-- BEGIN PlACE PAGE CONTENT HERE -->
    <div class="card card-transparent">
      <div class="card-header ">
        <div>
            <h1 class="section-title">Modules</h1>
        </div>
      </div>
      <div class="m-0 row card-block">
        <div class="col-lg-4 no-padding">
          <div class="p-r-30">
            <h3>Effortless Customization</h3>
            <br>
            <p>Cards are pluggable UI components that are managed and displayed in a web portal. Cards in Pages are created by reusing the <a href="http://getbootstrap.com/components/#cards">cards</a> introduced in Bootstrap to enable effortless customization.
            </p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="card card-transparent">
            <div class="card-block no-padding">
              <div id="card-advance" class="card card-default">
                <div class="card-block">
                    <div id="file" class="dropzone"></div>
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

<script type="text/javascript" src="{{ asset('assets/plugins/dropzone/dropzone.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/module.js') }}"></script>

@endsection
