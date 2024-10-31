@extends('layouts.zpanel')

@section('styles')

@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item"><a href="{{ url('admin/datatable/features') }}">Features</a></li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header ">
      <div>
          <h1 class="section-title">Add a New Feature</h1>
      </div>
  </div>
  <div class="m-0 row card-block">
      <div class="col-lg-4 no-padding">
        <div class="p-r-30">
          <h6 class="ui-subheader">Feature name and Values</h6>
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
                        <form id="featureForm" action="{{ route('features.update', $feature) }}" method="post" autocomplete="off">
                          {{ csrf_field() }}
                          {{ method_field('PATCH') }}
                          <div class="row">
                              <div class="col-sm-12 form-group">
                                  <label>Name</label>
                                  <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' error' : '' }}" required name="name" value="{{ $feature->name }}">
                                  @if($errors->has('name'))
                                  <label id="name-error" class="error" for="name">{{ $errors->first('name') }}</label>
                                  @endif
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-sm-12 form-group">
                                <label>Value</label>
                              </div>
                          </div>
                          <div id="listOfvalues">
                          @if($feature->values->count())
                            @foreach($feature->values as $feature)
                            <div class="row align-items-center">
                              <div class="col-6 form-group">
                                  <input type="text" id="value" class="form-control" name="value[]" value="{{ $feature->value }}">
                              </div>
                              <div class="col-1">
                                <a href="javascript:;"><i class="pg-plus_circle add-value"></i></a>
                                <a href="javascript:;"><i class="pg-minus_circle pl-1 remove-value"></i></a>
                              </div>
                            </div>
                            @endforeach
                          @else
                            <div class="row align-items-center">
                              <div class="col-6 form-group">
                                  <input type="text" id="value" class="form-control" name="value[]">
                              </div>
                              <div class="col-1">
                                <a href="javascript:;"><i class="pg-plus_circle add-value"></i></a>
                                <a href="javascript:;"><i class="pg-minus_circle pl-1 remove-value"></i></a>
                              </div>
                            </div>
                          @endif 
                          </div>
                          @include ('zpanel.partials._form_actions', ['path' => route('features.index')])
                        </form>
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

<script type="text/javascript" src="{{ asset('assets/js/feature_form.js') }}"></script>
@endsection






