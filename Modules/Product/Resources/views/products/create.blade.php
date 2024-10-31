

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
 	<li class="breadcrumb-item">Product</li>
 	<li class="breadcrumb-item">add product</li>
	<li class="pull-right"><a href="javascript:void(0);" class="btn guide-me"><i class="aapl-lifebuoy"></i> Guide me</a></li>
</ol>
<!-- END BREADCRUMB --> 
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<form action="{{ route('product.store', $product->slug) }}" method="post" enctype="multipart/form-data" autocomplete="off" class="sodirty">
{{ csrf_field() }}
{{ method_field('PATCH') }}
<!-- start row -->
<div class="row">
  <div class="col-lg-12">
    <!-- START card -->
    <div class="card card-transparent">
      <div class="card-header ">
        <div>
            <h1 class="section-title">Add a Product</h1>
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
                <li id="tabDetails" class="nav-item">
                  <a href="#" class="active" data-toggle="tab" data-target="#product-details">Details</a>
                </li>
                <li class="nav-item">
                  <a href="#" data-toggle="tab" data-target="#productImages">Images</a>
                </li>
                <li class="nav-item @if(old('product_type') == 'variant')  @elseif($product->variations && !$product->variations->count()) hide @endif">
                  <a href="#" data-toggle="tab" data-target="#productVariant">Variant</a>
                </li>
                <li class="nav-item @if(old('product_type') == 'virtual')  @elseif(!$product->file) hide @endif">
                  <a href="#" data-toggle="tab" data-target="#productFile" 
                  @if($errors->has('product_file')) style="color:#f55753;" @endif>Product File</a>
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
                      <div id="productType" class="form-group{{ $errors->has('product_type') ? ' has-error' : '' }}">
                        <label for="product_type">Product type</label>
                        <select name="product_type" id="product_type" class="full-width" data-init-plugin="select2" required>
                          <option value="standard" @if(old('product_type', $product->type) == 'standard')  selected @endif>Standard Product</option>
                          <option value="variant" @if(old('product_type', $product->type) == 'variant')  selected @endif>Variant Product</option>
                          <option value="virtual" @if(old('product_type', $product->type) == 'virtual')  selected @endif>Virtual Product</option>
                        </select>
                        @if($errors->has('product_type'))
                        <label id="product_type-error" class="error" for="product_type">{{ $errors->first('product_type') }}</label>
                        @endif
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row column-seperation">
                    <div id="productTitle" class="col-sm-11">
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
                  
                  <div id="productCategory" class="row column-seperation">
                    <div  class="col-sm-11 form-group">
                        <label>Category </label>
                        @if(auth()->user()->can('add categories'))
                        <a href="javascript:;" data-toggle="modal" data-target="#createNewCategory">
                            Create New
                          </a>
                        @endif
                        <input type="hidden" id="category" name="category" value="{{ old('category') }}">
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
                    <div id="productSku" class="col-sm-4">
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
                  @include('product::products.partials._shipping')
                  <!-- start description -->
                  <div class="row column-seperation">
                      <div id="productShortDescription" class="col-sm-11 form-group">
                      <label for="short_description">Short Description</label>
                      <div class="no-scroll card-toolbar">
                        <div class="summernote-wrapper">
                          <textarea name="short_description" id="short_description" cols="30" rows="10" class="form-control">{!! old('short_description', $product->short_description) !!}</textarea>
                        </div>
                      </div>
                      </div>
                      <div id="productDescription" class="col-sm-11 form-group">
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
                        <div id="app">
                          <variation-table :data="{{ json_encode($attributes['data']) }}" updatevariationsurl="{{ route('product.variations.update', $product->slug) }}" updatevariationurl="{{ route('product.variation.update', $product->slug) }}" product="{{ $product->slug }}" :variations="{{ $option_sets->toJson() }}" loader="{{ asset('assets/img/progress/progress-circle-complete.svg') }}"></variation-table>
                        </div>
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
                  <button type="button" class="btn btn-default btn-block m-t-5" data-dismiss="modal">Close</button>
                </div>
                <div class="col-md-4 m-t-10 sm-m-t-10">
                  <button type="submit" id="btnSaveCategory" class="btn btn-primary btn-block m-t-5">Save</button>
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
<script src="{{ asset('assets/plugins/classie/classie.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/switchery/js/switchery.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-dynatree/jquery.dynatree.min.js') }}"></script>
<script src="{{ asset('assets/js/product_form.js') }}" type="text/javascript"></script>

@endsection

@section('page_scripts')

<script>
   
$(document).ready(function() {
  imagesProduct.initExpander();
  imagesProduct.init();
  featuresCollection.init();
  
});

	function startTour()
  {
    var intro = introJs();
    intro.setOptions({
      steps: [
        {
          element: document.querySelector('#tabDetails'),
          intro: "<h4>Details</h4><p>The first step would be to go over the basic product details which are required. These would be the important details which would need to be entered regarding your product.<br><br>Further configurations such as adding bank details for the bank transfer method can be done by clicking on the respective setup button.<br><br> Note: Please check with your delivery partner to determine whether COD facility is available.<br><img src='https://via.placeholder.com/450x1' alt='image'></p>"
        },
		{
          element: document.querySelector('#productType'),
          intro: "<h4>Product Type</h4><p>Select the type of product this is:<br><br><ol><li>Standard product- <br>Single physical product with no variation options. <br>Ex: A necklace</li> <li>Variant product- <br>A product with several variations or options. <br>Ex: Shirt with several size options (S,M,L) <br>* Important- be sure to create your variant set prior to adding a variant product. Go to Product <a href='{{ route('attributes.index') }}'>variants</a> to add variant sets.</li> <li>Virtual Product- <br>A product which is not physical in nature. <br>Ex: A downloadable file.</li></ol><img src='https://via.placeholder.com/450x1' alt='image'></p>",
			position: 'bottom-right-aligned'
        },
		{
          element: document.querySelector('#productTitle'),
          intro: "<h4>Product Title</h4><p>Enter the title of the product. This will be the visible on the storefront.<br><img src='https://via.placeholder.com/450x1' alt='image'></p>"
        },
		{
          element: document.querySelector('#productPricing'),
          intro: "<h4>Product Pricing</h4><p>Enter the selling price of the product. This price will be what the customer will pay.<br><br>Click ‘More pricing’ if you want to add a cost price (for your records, this will not be seen by your customers) or add a special price to show the product as discounted (Special price must be lower than selling price).<br><img src='https://via.placeholder.com/450x1' alt='image'></p>"
        },
		{
          element: document.querySelector('#productCategory'),
          intro: "<h4>Product Category</h4><p>Any categories already created will show in the box. To add a new category, click on ‘Create New’.<br><img src='https://via.placeholder.com/450x1' alt='image'></p>"
        },
		{
          element: document.querySelector('#productSku'),
          intro: "<h4>Product SKU</h4><p>Enter the SKU or product code. This should be a unique code for each of your products (including variants)<br><img src='https://via.placeholder.com/450x1' alt='image'></p>"
        },
		{
          element: document.querySelector('#productShipping'),
          intro: "<h4>Product Shipping</h4><p>The product weight and dimensions should be added here. This is used to calculate the shipping fees to deliver the product to your customer. Click on ‘Add dimensions’ to open the height, width and length fields.<br><br>Note: While most shippers may only require weight, some may require the dimensions too. Please check with your shipper for clarification.The weight and dimensions will not be visible to the customer.<br><img src='https://via.placeholder.com/450x1' alt='image'></p>"
        },
		{
          element: document.querySelector('#productShortDescription'),
          intro: "<h4>Product Short Description</h4><p>This is a brief description of the product. Generally one or two interesting sentences about the product to pique the interest of the customer.<br><img src='https://via.placeholder.com/450x1' alt='image'></p>"
        },
		{
          element: document.querySelector('#productDescription'),
          intro: "<h4>Product Description</h4><p>Enter more details about the product here such as product dimensions, material, care instructions, sizing charts, warranty information etc.<br><img src='https://via.placeholder.com/450x1' alt='image'></p>"
        },
		{
          element: document.querySelector('#productAvailability'),
          intro: "<h4>Product Availability</h4><p>Select this  box if this product is available for pre-order.<br><br>Pre-ordering allows your customers to order a product which you currently do not have stock of but will be in stock in the near future. This allows them to pay to reserve the product in advance. E.g: Merchant will be releasing a new T-shirt design but the product is not yet ready to be shipped as it is in the process of being manufactured.<br><img src='https://via.placeholder.com/450x1' alt='image'></p>"
        },
		{
          element: document.querySelector('#productPublish'),
          intro: "<h4>Product Publish</h4><p>If you do not wish to publish this product right away, select the publish date and time for when this product is to be visible on the storefront and the product will automatically be visible at the date and time you have selected.<br><img src='https://via.placeholder.com/450x1' alt='image'></p>"
        },
		{
          element: document.querySelector('#productLive'),
          intro: "<h4>Product Live</h4><p>If you wish to take this product offline entirely from your storefront but do not want to delete this from your product list, select this toggle to set the product to offline.<br><img src='https://via.placeholder.com/450x1' alt='image'></p>"
        }
      ],
      showStepNumbers: false,
      exitOnEsc: false
    });
    intro.start();
  }

  $(document).on('click', '.guide-me', function(e){  
       startTour();
  });

</script>

@endsection




