@extends('layouts.zpanel')

@section('styles')

<link href="{{ asset('assets/plugins/dropzone/css/dropzone.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{ asset('assets/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('assets/plugins/redactor/redactor.min.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('assets/plugins/redactor/plugins/inlinestyle/inlinestyle.min.css') }}" rel="stylesheet" type="text/css" media="screen">
<link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.css') }}">
<link href="{{ asset('assets/plugins/jquery-dynatree/skin/ui.dynatree.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('content')
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('product.list') }}">All Products</a></li>
</ol>
<!-- END BREADCRUMB --> 
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<form action="{{ route('product.update', $product->slug) }}" method="post" enctype="multipart/form-data" class="sodirty">
{{ csrf_field() }}
{{ method_field('PATCH') }}
<input type="hidden" name="live" value="2">
<!-- start row -->
<div class="row">
  <div class="col-lg-12">
    <!-- START card -->
    <div class="card card-transparent">
      <div class="card-header ">
        <div>
            <h1 class="section-title">Edit Product {{ $product->name }}</h1>
        </div>
      </div>
      @if ($errors->any())
        <div class="alert alert-danger bordered" role="alert">
          <button class="close" data-dismiss="alert"></button>
          @if($errors->count() == 1)
            <p class="pull-left"><strong>Note:</strong> There is 1 error with this product.</p>
          @else
            <p class="pull-left"><strong>Note:</strong> There are {{ $errors->count() }} errors with this product.</p>
          @endif 
          <br> 
          @if ($errors->any())
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          @endif
          <div class="clearfix"></div>
        </div>
      @endif
      <div class="card-block no-padding">
        <div class="row">
          <div class="col-xl-12">
            <div class="card card-transparent flex-row">
              <ul class="nav nav-tabs nav-tabs-simple nav-tabs-left bg-white">
                <li class="nav-item">
                  <a href="#" class="active" data-toggle="tab" data-target="#product-details">Details</a>
                </li>
                <li class="nav-item">
                  <a href="#" data-toggle="tab" data-target="#productImages">Images</a>
                </li>
                <li class="nav-item {{ $product->type !== 'variant' ? 'hide' : '' }}">
                  <a href="#" data-toggle="tab" data-target="#productVariant">Variant</a>
                </li>
                <li class="nav-item {{ $product->type == 'virtual' ? '' : 'hide' }}">
                  <a href="#" data-toggle="tab" data-target="#productFile">Product File</a>
                </li>
                <li class="nav-item">
                  <a href="#" data-toggle="tab" data-target="#productAnalytics">Analytics</a>
                </li>
                <li class="nav-item">
                  <a href="#" data-toggle="tab" data-target="#other">Other</a>
                </li>
              </ul>
              <div class="tab-content bg-white col-sm-10">
                <div class="tab-pane active" id="product-details">
                  <div class="row column-seperation">
                    <div class="col-sm-4">
                      <div class="form-group{{ $errors->has('product_type') ? ' has-error' : '' }}">
                        <label for="product_type">Product type</label>
                        <select name="product_type" id="product_type" class="full-width" data-init-plugin="select2" disabled>
                          <option value="standard" @if($product->type === 'standard') selected @endif>Standard Product</option>
                          <option value="variant" @if($product->type === 'variant') selected @endif>Variant Product</option>
                          <option value="virtual" @if($product->type === 'virtual') selected @endif>Virtual Product</option>
                        </select>
                        @if($errors->has('product_type'))
                        <label id="product_type-error" class="error" for="product_type">{{ $errors->first('product_type') }}</label>
                        @endif
                      </div>
                      <input type="hidden" name="product_type" value="{{ $product->type }}">
                    </div>
                  </div>
                  <hr>
                  <div class="row column-seperation">
                    <div class="col-sm-11">
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label>Title</label>
                            <input type="text" class="form-control" required id="title" name="title" value="{{ old('title', $product->name) }}">
                            @if($errors->has('title'))
                            <label id="title-error" class="error" for="title">{{ $errors->first('title') }}</label>
                            @endif
                        </div>
                    </div>
                  </div>
                  <hr>
                  @include('product::products.partials._product_pricing')
                  
                  <div class="row column-seperation">
                    <div class="col-sm-11 form-group">
                        <label>Category </label>
                        @if(auth()->user()->can('add categories'))
                        <a href="javascript:;" data-toggle="modal" data-target="#createNewCategory">
                            Create New
                          </a>
                        @endif
                        <input type="hidden" id="category" name="category" value="{{ $product->categories->implode('id', ',') }}">
                        @if($errors->has('category'))
                          <label id="category-error" class="error" for="category">{{ $errors->first('category') }}</label>
                        @endif
                        <div class="card card-outline-secondary">
                          <div class="scrollable">
                            <div id="categoryTree" data-id="{{ $product->slug }}" style="height: 150px;">
                            </div>
                          </div>
                        </div>
                    </div>  
                  </div>

                  <div class="row column-seperation">
                    <div class="col-sm-4">
                      <div class="form-group{{ $errors->has('sku') ? ' has-error' : '' }}">
                        <label for="sku">SKU (Stock Keeping Unit)</label>
                        <input type="text" class="form-control" name="sku" id="sku" value="{{ old('sku', $product->sku) }}">
                        @if($errors->has('sku'))
                          <label id="sku-error" class="error" for="sku">{{ $errors->first('sku') }}</label>
                        @endif
                      </div>
                    </div>
                  </div>
                  <hr>
                  @if($product->type !== 'virtual')
                  @include('product::products.partials._shipping')
                  @endif
                  <!-- start description -->
                  <div class="row column-seperation">
                      <div class="col-sm-11 form-group">
                      <label for="short_description">Short Description</label>
                      <div class="no-scroll card-toolbar">
                        <div class="summernote-wrapper">
                          <textarea name="short_description" id="short_description" cols="30" rows="10" class="form-control">{!! old('short_description', $product->short_description) !!}</textarea>
                        </div>
                      </div>
                      </div>
                      
                      <div class="col-sm-11 form-group">
                      <label for="description">Description</label>
                      <div class="no-scroll card-toolbar">
                        <div class="summernote-wrapper">
                          <textarea name="description" id="description" cols="30" rows="10" class="form-control">{!! old('description', $product->description) !!}</textarea>
                        </div>
                      </div>
                      </div>
                  </div>
                  <!-- end description -->
                  <hr>
                  @include('product::products.partials._product_availability')

                </div>

                <div class="tab-pane" id="productImages">
                    @include('product::products.partials._product_image_uploader', ['product' => $product])
                </div>
                
                <div class="tab-pane" id="productVariant">
                  <p class="text-uppercase"><strong>Variations</strong></p>
                  <!-- start variation -->
                  <div class="row column-seperation">
                    <div class="col-sm-12">
                        
                          <variation-table :data="{{ json_encode($attributes['data']) }}" updatevariationsurl="{{ route('product.variations.update', $product->slug) }}" updatevariationurl="{{ route('product.variation.update', $product->slug) }}" product="{{ $product->slug }}" :variations="{{ $option_sets->toJson() }}" loader="{{ asset('assets/img/progress/progress-circle-complete.svg') }}">
                            
                          </variation-table>
                       
                    </div>
                  </div>
                  <!-- end variation -->
                </div>
                <div class="tab-pane" id="productFile">
                  @include('product::products.partials._product_files')
                </div>
                <div class="tab-pane" id="productAnalytics">
                  @include('product::products.partials._seo')
                </div>
                <div class="tab-pane" id="other">
                  @include('product::products.partials._product_identifiers')
                  <hr>
                  @include('product::products.partials._related_products')
                  <hr>
                  @include('product::products.partials._product_meta')
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END card -->
  </div>
</div>
<!-- end row -->              
@include ('zpanel.partials._form_actions', ['path' => route('product.list')])
</form>

<!-- start category add modal -->
  <div class="modal fade slide-up disable-scroll" id="createNewCategory" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog ">
      <div class="modal-content-wrapper">
        <div class="modal-content">
          <div class="modal-header clearfix text-left">
            <h5>Create New <span class="semi-bold">Category</span></h5>
          </div>
          <div class="modal-body">
            <form id="formCategory" role="form" data-url="{{ route('product.category.save') }}" autocomplete="off">
              {{ csrf_field() }}
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                  </div>
                </div>
              </div>
              <div class="scrollable">
                <div class="row" style="height: 150px;">
                  <div class="col-md-12">
                    <div id="categoryTreeNew">
                      @if($categories->count()) 
                        @include ('product::products.partials._categories', ['categories' => $categories->where('parent_id', null)])
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" id="parent_category" name="parent_category" value="{{ $categories->where('parent_id', null)->first()->id }}" required>
              <div class="row justify-content-end">
                <div class="col-md-4 m-t-10 sm-m-t-10">
                  <button type="button" class="btn btn-action-cancel btn-block m-t-5" data-dismiss="modal">Cancel</button>
                </div>
                <div class="col-md-4 m-t-10 sm-m-t-10">
                  <button type="submit" id="btnSaveCategory" class="btn btn-action-save btn-block m-t-5">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
  <!-- end category add modal -->

  @include('product::products.partials._create_brand')

  @include('product::products.partials._create_taxclass')

  @include('product::products.partials._create_shippingclass')

<!-- start product image meta modal -->

@include('product::products.partials._image_meta')

<!-- end product image meta modal  -->

<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/dropzone.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-autonumeric/autoNumeric.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('assets/plugins/classie/classie.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/switchery/js/switchery.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-dynatree/jquery.dynatree.min.js') }}"></script>
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
<script src="{{ asset('assets/js/product_form.js') }}" type="text/javascript"></script>

@endsection

@section('page_scripts')

<script>
   
$(document).ready(function() {
  imagesProduct.initExpander();
  imagesProduct.init();
});

</script>

@endsection




