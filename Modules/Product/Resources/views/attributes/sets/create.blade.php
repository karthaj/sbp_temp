@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">Product</li>
  <li class="breadcrumb-item"><a href="{{ route('attributes.index') }}">variant sets</a></li>
  <li class="breadcrumb-item">add</li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header ">
      <div>
          <h1 class="section-title">Create a New Option Set</h1>
      </div>

  </div>
  <div class="m-0 row card-block">
      <div class="col-lg-4 no-padding">
        <div class="p-r-30">
          <p>
            Add a new option set which can then be applied to one or more products in your store.
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
                        <form action="{{ route('attributes.sets.store') }}" method="post">
                          {{ csrf_field() }} 
                          <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                              <label>Name</label>
                              <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' form-control-danger' : '' }}" required name="name" value="{{ old('name') }}">
              
                              @if($errors->has('name'))
                              <div class="form-control-feedback">
                                  {{ $errors->first('name') }}
                              </div>
                              @endif
                          </div>
                          <div class="form-group{{ $errors->has('options') ? ' has-danger' : '' }}">
                              <label>Available Options</label>
                              <select name="options[]" id="options" class="full-width form-control{{ $errors->has('options') ? ' form-control-danger' : '' }}" data-init-plugin="select2" multiple required>
                                @if($attributes->count())
                                  @foreach($attributes as $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                  @endforeach
                                @endif
                              </select>

                              @if($errors->has('options'))
                              <div class="form-control-feedback">
                                  {{ $errors->first('options') }}
                              </div>
                              @endif
                          </div>
                          @include ('zpanel.partials._form_actions', ['path' => route('attributes.index')])
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
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
@endsection

