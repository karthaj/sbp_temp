@extends('layouts.zpanel')

@section('styles')

<link href="{{ asset('assets/plugins/redactor/redactor.min.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('assets/plugins/redactor/plugins/inlinestyle/inlinestyle.min.css') }}" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.css') }}">

@endsection

@section('content')

<!-- BEGIN PlACE PAGE CONTENT HERE -->
<form id="blogForm" action="{{ route('blogs.update', $blog) }}" method="post" class="sodirty" enctype="multipart/form-data" autocomplete="off">
{{ csrf_field() }}
{{ method_field('PATCH') }}
<input type="hidden" name="blog" value="{{ $blog->id }}">
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blogs</a></li>
  <li class="breadcrumb-item active"><a href="{{ route('blogs.create') }}">add new</a></li>
</ol>
<!-- END BREADCRUMB --> 

<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <div>
            <h1 class="section-title">Edit Blog Post</h1>
        </div>
      </div>
      <div class="card-block">
        <div class="row">
          <div class="col-lg-8">
            <div data-pages="card" class="card card-default card-custom">
              <div class="card-block">
                  <div class="row">
                    <div class="col form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                        <label>Title</label>
                        <input type="text" id="title" class="form-control{{ $errors->has('title') ? ' form-control-danger' : '' }}" required name="title" value="{{ old('title', $blog->title) }}">
                        @if($errors->has('title'))
                        <div class="form-control-feedback">{{ $errors->first('title') }}</div>
                        @endif
                    </div>
                  </div>
                  <div class="row">
                      <div class="col form-group">
                      <label for="content">Content</label>
                      <div class="no-scroll card-toolbar">
                        <div class="summernote-wrapper">
                          <textarea name="content" id="content" cols="30" rows="10" class="form-control">{{ old('content', $blog->content) }}</textarea>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
            <div data-pages="card" class="card card-default card-custom">
              <div class="card-header">
                <div class="card-title"><p class="ui-subheader">Search engine listing preview</p>
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
                    <div class="col form-group{{ $errors->has('page_title') ? ' has-danger' : '' }}">
                        <label for="page_title">Page title</label>
                        <span class="text-muted pull-right">Max 70 characters</span>
                        <input type="text" class="form-control{{ $errors->has('page_title') ? ' form-control-danger' : '' }}" id="page_title" name="page_title" value="{{ old('page_title', $blog->meta_title) }}"> 
                        @if($errors->has('page_title'))
                          <div class="form-control-feedback">{{ $errors->first('page_title') }}</div>
                        @endif   
                    </div>
                </div>
                <div class="row">
                    <div class="col form-group{{ $errors->has('meta_description') ? ' has-danger' : '' }}">
                        <label for="meta_description">Meta description</label>
                        <span class="text-muted pull-right">Max 160 characters</span>
                        <textarea name="meta_description" id="meta_description" cols="30" rows="5" class="form-control{{ $errors->has('meta_description') ? ' form-control-danger' : '' }}">{{ old('meta_description', $blog->meta_description) }}</textarea> 
                        @if($errors->has('meta_description'))
                          <div class="form-control-feedback">{{ $errors->first('meta_description') }}</div>
                        @endif  
                    </div>
                </div> 
                <div class="row">
                    <div class="col form-group">
                        <label for="meta_keywords">Meta keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control tagsinput" data-role="tagsinput" value="{{ old('meta_keywords', $blog->meta_keywords) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col form-group{{ $errors->has('url_handle') ? ' has-danger' : '' }}"> 
                        <label for="url_handle">URL and handle</label>
                        <input type="text" id="url_handle" name="url_handle" class="form-control{{ $errors->has('url_handle') ? ' form-control-danger' : '' }}" value="{{ old('url_handle', $blog->slug) }}">
                        @if($errors->has('url_handle'))
                          <div class="form-control-feedback">{{ $errors->first('url_handle') }}</div>
                        @endif
                    </div>
                </div>
                
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="row">
              <div class="col-md-12">
                <div class="card card-default card-custom">
                  <div class="card-header text-center">
                    <div class="card-title"><p class="ui-subheader">visibility</p>
                    </div>
                    <div class="card-controls">
                      <ul>
                        <li><a data-toggle="collapse" class="card-collapse" href="javascript:;"><i class="pg-arrow_maximize"></i></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="card-block text-center">
                    <div class="form-group {{ $errors->has('visibility') ? 'has-danger' : '' }}">
                      <div class="radio radio-success">
                        <input type="radio" value="1" name="visibility" id="visible"  @if($blog->active) checked @endif>
                        <label for="visible">Visible</label>
                        <input type="radio" value="0" name="visibility" id="hidden"  @if($blog->active == 0) checked @endif>
                        <label for="hidden">Hidden</label>
                      </div>
                      @if($errors->has('visibility'))
                          <div class="form-control-feedback">{{ $errors->first('visibility') }}</div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-12">
                <div data-pages="card" class="card card-default card-custom">
                  <div class="card-header text-center">
                    <div class="card-title"><p class="ui-subheader">Featured Image</p>
                    </div>
                    <div class="card-controls">
                      <ul>
                        <li><a data-toggle="collapse" class="card-collapse" href="javascript:;"><i class="pg-arrow_maximize"></i></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="card-block text-center">
                    
                    <div class="row">
                          <div class="col-12 mb-2">
                              <img src="{{ $blog->featured_image ? asset('stores').'/'.session('store')->domain.'/blog/'.$blog->featured_image : 'https://via.placeholder.com/141x180?text=Image' }}" alt="{{ $blog->title }}" class="img-fluid featured-image-preview" width="141" height="180">
                          </div>
                          <div class="col form-group">
                              <label for="featured_image">Cover Image</label>
                              <div class="styled-file-input col">
                                <div class="btn button">
                                  <input type="file" accept="image/*" class="form-control{{ $errors->has('featured_image') ? ' error' : '' }}" onchange="blog.coverImage(this)" name="featured_image" style="overflow: hidden;">
                                  <label class="mb-0">Upload image</label>
                                </div>
                              </div>
                              @if($errors->has('featured_image'))
                                <label id="featured_image-error" class="error" for="featured_image">{{ $errors->first('featured_image') }}</label>
                              @endif
                              <div class="col mt-2">
                                  @if($blog->featured_image != '')

                                    <button id="btnDeleteFeaturedImage" class="btn btn-action-delete" type="button" delete-url="{{ route('blogs.image.destroy',$blog) }}">DELETE</button>
                               
                                  @endif
                              </div>
                          </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="card card-default card-custom">
                  <div class="card-header text-center">
                    <div class="card-title"><p class="ui-subheader">other</p>
                    </div>
                    <div class="card-controls">
                      <ul>
                        <li><a data-toggle="collapse" class="card-collapse" href="javascript:;"><i class="pg-arrow_maximize"></i></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="card-block">
                    <div class="form-group {{ $errors->has('author') ? 'has-danger' : '' }}">
                      <label>Author</label>
                      <input type="text" id="author" class="form-control{{ $errors->has('author') ? ' form-control-danger' : '' }}" name="author" value="{{ old('author', $blog->author) }}">
                      @if($errors->has('author'))
                        <div class="form-control-feedback">{{ $errors->first('author') }}</div>
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
</div>
@include ('zpanel.partials._form_actions', ['path' => route('blogs.index')])
</form>

<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 

<script type="text/javascript" src="{{ asset('assets/js/blog.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/blog.js') }}"></script>
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
<script src="{{ asset('assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>

@endsection





