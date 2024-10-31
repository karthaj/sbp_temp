@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/redactor/redactor.min.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('assets/plugins/redactor/plugins/inlinestyle/inlinestyle.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('content')
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">storefront</li>
  <li class="breadcrumb-item">pages</li>
  <li class="breadcrumb-item active">new</li>
</ol>
<!-- END BREADCRUMB --> 
<form action="{{ route('pages.store') }}" method="post" autocomplete="off" class="sodirty">
{{ csrf_field() }}
  <div  class="card card-transparent">
    <div class="card-header ">
        <div>
            <h1 class="section-title">Add page</h1>
        </div>
    </div>

    <div class="m-0 row card-block">
      <div class="col-md-8 no-padding">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-transparent">
              <div class="card-block no-padding">
                <div class="card card-default">
                  <div class="card-header ">
                    <div class="card-title">
                      <p class="ui-subheader">Page details</p>
                    </div>
                  </div>
                  <div class="card-block">
                    <div class="form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                      <label for="title">Title</label>
                      <input type="text" name="title" id="title" class="form-control{{ $errors->has('title') ? ' form-control-danger' : '' }}" value="{{ old('title') }}">

                      @if($errors->has('title'))
                          <div class="form-control-feedback">{{ $errors->first('title') }}</div>
                      @endif
                    </div>

                    <div class="form-group">
                      <label for="content">content</label>
                      <div class="no-scroll card-toolbar">
                        <textarea name="content" id="content" cols="30" rows="10" class="form-control">{!! old('content') !!}</textarea>
                      </div>
                    </div>

                    <div class="form-group {{ old('type') !== 'contact' ? 'hide' : '' }} {{ $errors->has('enable_form') ? 'has-danger' : '' }}">
                      <div class="checkbox check-info">
                        <input type="checkbox" @if(old('enable_form')) checked @endif value="1" id="enable_form" name="enable_form">
                        <label for="enable_form">enable form</label>
                      </div>
                      @if($errors->has('enable_form'))
                          <div class="form-control-feedback">{{ $errors->first('enable_form') }}</div>
                      @endif
                    </div>
                    
                    <div class="form-group {{ old('type') !== 'contact' ? 'hide' : '' }}">
                      <label for="map">Map</label>
                      <textarea name="map" id="map" class="form-control" cols="30" rows="10"></textarea>
                    </div>
              
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="card card-transparent">
              <div class="card-block no-padding">
                <div class="card card-default">
                  <div class="card-header ">
                    <div class="card-title">
                      <p class="ui-subheader">search engine optimization</p>
                    </div>
                  </div>
                  <div class="card-block">
                    <div class="form-group{{ $errors->has('page_title') ? ' has-danger' : '' }}">
                        <label for="page_title">Page title</label>
                        <span class="text-muted pull-right">Max 70 characters</span>
                        <input type="text" class="form-control{{ $errors->has('page_title') ? ' form-control-danger' : '' }}" id="page_title" name="page_title" value="{{ old('page_title') }}" maxlength="70"> 
                        @if($errors->has('page_title'))
                          <div class="form-control-feedback">{{ $errors->first('page_title') }}</div>
                        @endif   
                    </div>
                      
                    <div class="form-group{{ $errors->has('meta_description') ? ' has-danger' : '' }}">
                      <label for="meta_description">Meta description</label>
                      <span class="text-muted pull-right">Max 160 characters</span>
                      <textarea name="meta_description" id="meta_description" cols="30" rows="5" class="form-control{{ $errors->has('meta_description') ? ' form-control-danger' : '' }}" maxlength="160">{{ old('meta_description') }}</textarea> 
                      @if($errors->has('meta_description'))
                        <div class="form-control-feedback">{{ $errors->first('meta_description') }}</div>
                      @endif  
                    </div>
                   
                    <div class="form-group">
                        <label for="meta_keywords">Meta keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control tagsinput" data-role="tagsinput" value="{{ old('meta_keywords') }}">
                    </div>
                     
                    <div class="form-group">
                      <label for="url_handle">URL and handle</label>
                      <div class="input-group transparent"> 
                        <span class="input-group-addon">{{ getStoreUrl(session('store')).'/pages/' }}</span>
                        <input type="text" id="url_handle" name="url_handle" class="form-control{{ $errors->has('url_handle') ? ' form-control-danger' : '' }}" value="{{ old('url_handle') }}">
                        @if($errors->has('url_handle'))
                          <div class="form-control-feedback">{{ $errors->first('url_handle') }}</div>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="col-md-4 sm-no-padding">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-transparent">
              <div class="card-block no-padding">
                <div class="card card-default">
                  <div class="card-header ">
                    <div class="card-title">
                      Visibility
                    </div>
                  </div>
                  <div class="card-block">
                    <div class="radio radio-info">
                      <input type="radio" value="1" name="active" id="visible" checked>
                      <label for="visible">Visible</label>
                      <input type="radio" value="0" name="active" id="hidden">
                      <label for="hidden">Hidden</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="card card-transparent">
              <div class="card-block no-padding">
                <div class="card card-default">
                  <div class="card-header ">
                    <div class="card-title">
                      page type
                    </div>
                  </div>
                  <div class="card-block">
                    <div class="form-group {{ $errors->has('type') ? 'has-danger' : '' }}">
                      <select name="type" id="type" class="form-control {{ $errors->has('type') ? 'form-control-danger' : '' }}">
                        <option value="page" @if(old('type') === 'page') selected @endif>page</option>
                        <option value="contact" @if(old('type') === 'contact') selected @endif>contact page</option>
                      </select>
                      @if($errors->has('type'))
                          <div class="form-control-feedback">{{ $errors->first('type') }}</div>
                      @endif
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
@include ('zpanel.partials._form_actions', ['path' => route('pages.index')])
</form>

@endsection

@section('scripts') 

<script src="{{ asset('assets/plugins/redactor/redactor3.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/fontcolor/fontcolor.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/alignment/alignment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/table/table.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/fontfamily/fontfamily.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/fontsize/fontsize.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/imagemanager/imagemanager.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/inlinestyle/inlinestyle.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/video/video.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/redactor/plugins/widget/widget.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/webpage.js') }}"></script>

@endsection

