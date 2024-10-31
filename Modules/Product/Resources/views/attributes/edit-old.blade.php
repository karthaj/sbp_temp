@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/summernote/css/summernote.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('assets/plugins/cp/bootstrap-colorpicker.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

<!-- BEGIN PlACE PAGE CONTENT HERE -->

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item"><a href="{{ route('attributes.index') }}">attributes</a></li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header ">
      <a href="{{ route('attributes.index') }}" class="font-weight-bold">Back to variants</a>
      <div>
          <h1 class="section-title">Edit Option {{ $attribute->name }}</h1>
      </div>
  </div>
  <div class="m-0 row card-block">
      <div class="col-lg-4 sm-no-padding">
        <div class="p-r-30">
          <h6 class="ui-subheader">Option Name and Type</h6>
          <p>
            Options such as size, color, etc can be attached to products in your store to allow for more flexible purchasing by your customers.
          </p>
        </div>
      </div>
      <div class="col-lg-8 sm-no-padding">
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default">
                      <div class="card-block">
                          <div id="app">
                            <attribute-edit :data="{{ $attribute->toJson() }}" adduri="{{ url('merchant/attributes/value') }}" indexuri="{{ route('attributes.index') }}"></attribute-edit>
                          </div>
                      </div>
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
<script type="text/javascript" src="{{ asset('assets/plugins/classie/classie.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/cp/bootstrap-colorpicker.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/js/attribute_form.js') }}"></script>
@endsection




