@extends('layouts.zpanel')

@section('styles')

<link href="{{ asset('assets/plugins/redactor/redactor.min.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('assets/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('assets/plugins/jquery-dynatree/skin/ui.dynatree.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />

@endsection

@section('content')

<!-- BEGIN PlACE PAGE CONTENT HERE -->
<form id="categoryForm" action="{{ route('categories.update', $category) }}" method="post" class="sodirty" enctype="multipart/form-data" class="sodirty">
{{ csrf_field() }}
{{ method_field('PATCH') }}
<input type="hidden" name="category_edit" value="{{ $category->id }}">
<input type="hidden" id="handle" value="{{ $category->slug }}">

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
  <li class="breadcrumb-item">edit</li>
</ol>
<!-- END BREADCRUMB --> 

<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <div>
            <h1 class="section-title"><small>Edit</small> Category {{ $category->name }}</h1>
        </div>
      </div>
      <div class="card-block">
        <div class="row">
          <div class="col-lg-8">
            <div data-pages="card" class="card card-default card-custom">
              <div class="card-block">
                  <div class="row">
                    <div class="col form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                        <label>Name</label>
                        <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' form-control-danger' : '' }}" required name="name" value="{{ $category->name }}">
                        @if($errors->has('name'))
                          <div class="form-control-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                  </div>
                  <div class="row">
                      <div class="col form-group">
                      <label for="description">Description</label>
                      <div class="no-scroll card-toolbar">
                        <textarea name="description" id="description" class="form-control" cols="30" rows="10">{{ $category->description }}</textarea>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
            <div data-pages="card" class="card card-default card-custom">
              <div class="card-block">
                  <div class="row">
                    <div class="col form-group">
                        <label>Parent Category</label>
                        @if($errors->has('category'))
                          <label id="category-error" for="category" class="error">{{ $errors->first('category') }}</label>
                        @endif
                        <input type="hidden" id="category" name="category" value="{{ $category->parent->id }}">
                        <div id="categoryTree">
                        @if($categories->count())
                           
                            @include ('product::partials._categories', ['categories' => $categories->where('parent_id', null), 'edit_category' => $category])
                            
                        @endif
                        </div>
                    </div>
                  </div>
              </div>
            </div>
            <div data-pages="card" class="card card-default card-custom">
              <div class="card-header">
                <div class="card-title"><p class="ui-subheader">Products in category</p>
                </div>
                <div class="card-controls">
                  <ul>
                    <li><a data-toggle="collapse" class="card-collapse" href="javascript:;"><i class="pg-arrow_maximize"></i></a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card-block">
                <div class="form-group">
                  <select id="products" class="full-width" data-init-plugin="select2" data-placeholder="Search for products">
                    <option value="">Search for products</option>
                    @if($products->count())
                      @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
                <ul id="productList" class="list-group list-group-flush">
                @if($category->products->count())
                  @foreach($category->products as $product)
                  <li class="list-group-item d-flex align-items-center justify-content-between">
                    <div class="p-2">
                      @if($product->images()->where('cover', 1)->count()) 
                        <img src="{{ asset('stores').'/'.session('store')->domain.'/product/'.$product->images()->where('cover', 1)->first()->small_default }}" alt="{{ $product->images()->where('cover', 1)->first()->alt_text }}" class="p-2 img-fluid" width="40">
                      @else
                        <img src="{{ asset('assets/img/ProductDefault.gif') }}" alt="default image" class="p-2 img-fluid" width="40">
                      @endif
                      <span class="p-2">{{ $product->name }}</span>
                    </div>
                    <div class="p-2">
                      <a href="#" data-id="{{ $product->id }}" class="remove-product"><i class="aapl-cross"></i></a>
                    </div>
                  </li>
                  @endforeach
                @else
                  <li id="message" class="list-group-item justify-content-center">There are no products in this category</li> 
                @endif           
                </ul>
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
                        <input type="text" class="form-control{{ $errors->has('page_title') ? ' form-control-danger' : '' }}" id="page_title" name="page_title" value="{{ $category->meta_title }}">   
                        @if($errors->has('page_title'))
                          <div class="form-control-feedback">{{ $errors->first('page_title') }}</div>
                        @endif  
                    </div>
                </div>
                <div class="row">
                    <div class="col form-group{{ $errors->has('meta_description') ? ' has-danger' : '' }}">
                        <label for="page_title">Meta description</label>
                        <span class="text-muted pull-right">Max 160 characters</span>
                        <textarea name="meta_description" id="meta_description" cols="30" rows="5" class="form-control{{ $errors->has('meta_description') ? ' form-control-danger' : '' }}">{{ $category->meta_description }}</textarea> 
                        @if($errors->has('meta_description'))
                          <div class="form-control-feedback">{{ $errors->first('meta_description') }}</div>
                        @endif   
                    </div>
                </div> 
                <div class="row">
                    <div class="col form-group">
                        <label for="page_title">Meta keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control tagsinput" data-role="tagsinput" value="{{ $category->meta_keywords }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col form-group{{ $errors->has('url_handle') ? ' has-danger' : '' }}"> 
                        <label for="url_handle">URL and handle</label>
                        <div class="input-group transparent"> 
                          <span class="input-group-addon">{{ getStoreUrl(session('store')).'/categories/' }}</span>
                          <input type="text" id="url_handle" name="url_handle" class="form-control{{ $errors->has('url_handle') ? ' form-control-danger' : '' }}" value="{{ $category->slug }}">
                          @if($errors->has('url_handle'))
                            <div class="form-control-feedback">{{ $errors->first('url_handle') }}</div>
                          @endif
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <div data-pages="card" class="card card-default card-custom">
                  <div class="card-header">
                    <div class="card-title"><p class="ui-subheader">Status</p>
                    </div>
                    <div class="card-controls">
                      <ul>
                        <li><a data-toggle="collapse" class="card-collapse" href="javascript:;"><i class="pg-arrow_maximize"></i></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="card-block">
                    <div class="row" id="options">
                          <div class="col pr-4 form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                               <p>Enable or disable this category from displaying in your store.</p>
                               <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="info" {{ $category->status ? 'checked' : ''}} data-switchery="true" style="display: none;" value="1" name="status" class="form-control{{ $errors->has('status') ? ' form-control-danger' : '' }}">
                               @if($errors->has('status'))
                                  <div class="form-control-feedback">{{ $errors->first('status') }}</div>
                              @endif
                          </div>
                    </div>
                  </div>
            </div>
          </div>
          <div class="col-lg-4">
              <div data-pages="card" class="card card-default card-custom">
              <div class="card-header text-center">
                <div class="card-title"><p class="ui-subheader">Category Image</p>
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
                          <img src="{{ $category->cover_image ? asset('stores').'/'.session('store')->domain.'/category/'.$category->cover_image : 'https://via.placeholder.com/141x180?text=Image' }}" alt="category cover image" class="img-fluid cover-image-preview" width="141" height="180">
                      </div>
                      <div class="col form-group">
                          <label for="cover_image">Cover Image</label>
                          <div class="styled-file-input col">
                            <div class="btn button">
                              <input type="file" accept="image/*" class="form-control{{ $errors->has('cover_image') ? ' error' : '' }}" onchange="categories.coverImage(this)" name="cover_image" style="overflow: hidden;">
                              <label class="mb-0">Upload image</label>
                            </div>
                          </div>
                          @if($errors->has('cover_image'))
                            <label id="cover_image-error" class="error" for="cover_image">{{ $errors->first('cover_image') }}</label>
                          @endif
                          <div class="col">
                              <button id="btnDeleteCover" class="btn btn-default btn-default-custom" type="button">Remove</button>
                          </div>
                      </div>
                </div>
                <hr>
                <div class="row text-left">
                    <div class="col form-group">
                        <label for="sort_order">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-control" value="{{ $category->sort_order }}" min="0">
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
@include ('zpanel.partials._form_actions', ['path' => route('categories.index')])
</form>

<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 

<script type="text/javascript" src="{{ asset('assets/js/category_form.js') }}"></script>
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
<script src="{{ asset('assets/plugins/switchery/js/switchery.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-dynatree/jquery.dynatree.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

@endsection




