@extends('layouts.zpanel')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.css') }}">
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">product</li>
  <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">brands</a></li>
  <li class="breadcrumb-item">edit</li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header ">
      <div>
          <h1 class="section-title">Edit Brand</h1>
      </div>
  </div>
  <div class="m-0 row card-block">
      <div class="col-lg-4 no-padding">
        <div class="p-r-30">
          <p>
           Brands can be associated with products, allowing your customers to shop by browsing their favorite brands.
          </p>
        </div>
      </div>
      <div class="col-lg-8 sm-no-padding">
        <form id="featureForm" action="{{ route('brands.update', $brand) }}" method="post" autocomplete="off" enctype="multipart/form-data" class="sodirty">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default">
                      <div class="card-block">
                          <div class="row">
                              <div class="col-sm-12 form-group">
                                  <label>Name</label>
                                  <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' error' : '' }}" required name="name" value="{{ $brand->name }}">
                                  @if($errors->has('name'))
                                  <label id="name-error" class="error" for="name">{{ $errors->first('name') }}</label>
                                  @endif
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-12 mb-2 text-center">
                              @if($brand->medium_default != '')
                                <img src="{{ asset('stores').'/'.session('store')->domain.'/brand/'.$brand->medium_default }}" alt="{{ $brand->name }}" class="img-fluid image-preview">
                              @else
                                <img src="https://via.placeholder.com/500x180?text=Image" alt="brand logo" class="img-fluid image-preview">
                              @endif
                        
                              <div class="form-group mt-3">
                                  <div class="styled-file-input">
                                    <div class="btn button">
                                      <input type="file" accept="image/*" id="logo" class="form-control{{ $errors->has('logo') ? ' error' : '' }}"  name="logo" style="overflow: hidden;">
                                      <label class="mb-0">Upload image</label>
                                    </div>
                                     @if($brand->medium_default != '')

                                        <button id="deleteLogo" class="btn btn-action-delete" type="button" delete-url="{{ route('brands.destroy',$brand) }}">DELETE</button>
                                   
                                      @endif
                                  </div>
                                  @if($errors->has('logo'))
                                    <label id="logo-error" class="error" for="logo">{{ $errors->first('logo') }}</label>
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
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default">
                      <div class="card-header">
                        <div class="card-title"><p class="ui-subheader">Search engine optimization</p>
                        </div>
                        <div class="card-controls">
                          <ul>
                            <li><a data-toggle="collapse" class="card-collapse" href="javascript:;"><i class="pg-arrow_maximize"></i></a>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="card-block">
                        <div class="row">
                            <div class="col px-0 form-group">
                                <label for="page_title">Page title</label>
                                <span class="text-muted pull-right">Max 70 characters</span>
                                <input type="text" class="form-control{{ $errors->has('page_title') ? ' error' : '' }}" id="page_title" name="page_title" value="{{ $brand->meta_title }}">
                                @if($errors->has('page_title'))
                                  <label id="page_title-error" class="error" for="name">{{ $errors->first('page_title') }}</label>
                                @endif    
                            </div>
                        </div>
                        <div class="row">
                            <div class="col px-0 form-group">
                                <label for="page_title">Meta description</label>
                                <span class="text-muted pull-right">Max 160 characters</span>
                                <textarea name="meta_description" id="meta_description" cols="30" rows="5" class="form-control{{ $errors->has('meta_description') ? ' error' : '' }}">{{ $brand->meta_description }}</textarea> 
                                @if($errors->has('meta_description'))
                                  <label id="meta_description-error" class="error" for="name">{{ $errors->first('meta_description') }}</label>
                                @endif  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col px-0 form-group">
                                <label for="meta_keywords">Meta Keywords</label>
                                <span class="text-muted pull-right">Max 160 characters</span>
                                <input class="tagsinput form-control{{ $errors->has('meta_keywords') ? ' error' : '' }}" data-role="tagsinput" type="text" id="meta_keywords" name="meta_keywords" value="{{ $brand->meta_keywords }}">
                                @if($errors->has('meta_keywords'))
                                  <label id="meta_keywords-error" class="error" for="name">{{ $errors->first('meta_keywords') }}</label>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col px-0 form-group"> 
                                <label for="url_handle">URL and handle</label>
                                <div class="input-group">
                                  <span class="input-group-addon">{{ getStoreUrl(session('store')).'/brands/' }}</span>
                                  <input type="text" id="url_handle" name="url_handle" class="form-control{{ $errors->has('url_handle') ? ' error' : '' }}" value="{{ $brand->slug }}">
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>    
            </div>
        </div>
          @include ('zpanel.partials._form_actions', ['path' => route('features.index')])
        </form>
      </div>
  </div>
</div>

<!-- END PLACE PAGE CONTENT HERE -->


@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/brand_form.js') }}"></script>
@endsection





