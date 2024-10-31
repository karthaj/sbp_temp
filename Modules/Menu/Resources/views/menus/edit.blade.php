@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{ asset('assets/plugins/jquery-nestable/jquery.nestable.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">storefront</li>
  <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">menu</a></li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header ">
      <div>
          <h1 class="section-title">{{ $menu->name }}</h1>
      </div>
  </div>
  
  <div class="row card-block">
    <div class="col-sm-12">
      <div class="col alert alert-danger hide" role="alert">
        <button class="close" data-dismiss="alert"></button>
        Menu creation has failed
      </div>
    </div>
    <div class="col-md-4">
      <!-- START card -->
      <div class="card-group horizontal" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="card m-b-0">
          <div class="card-header " role="tab" id="customLink">
            <h4 class="card-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#customLinks" aria-expanded="true" aria-controls="customLinks">
                 Custom Links
                </a>
              </h4>
          </div>
          <div id="customLinks" class="collapse" role="tabcard" aria-labelledby="customLink">
            <div class="card-block">
              <form id="customLinksForm" action="{{ url('/merchant/menus/items').'/'.$menu->slug }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="custom">

                  <div class="form-group">
                    <label for="link_url">URL</label>
                     <span class="ml-2" data-placement="right" data-toggle="tooltip" data-html="true" data-original-title="Eg: www.domain.com">
                      <i class="fa fa-question-circle"></i>
                    </span>
                    <small>
                      <div class="radio radio-info">
                        <input type="radio" value="http://" name="protocol" id="http_protocol">
                        <label for="http_protocol">http</label>
                        <input type="radio" checked="checked" value="https://" name="protocol" id="https_protocol">
                        <label for="https_protocol">https</label>
                      </div>
                    </small>
                    
                    <input type="text" class="form-control" id="link_url" name="link_url">
                  </div>
                  <div class="form-group">
                    <label for="custom_label">Menu Label</label>
                    <input type="text" class="form-control" id="label" name="label">
                  </div>
                  <div class="form-group">
                    <label for="target_tab">Target Tab</label>
                    <select name="target_tab" id="target_tab" class="form-control">
                      <option value="0">Current tab</option>
                      <option value="1" selected>New tab</option>
                    </select>
                  </div>
                  <div class="form-group pull-right sm-pull-reset my-3">
                    <button type="submit" class="btn btn-action-add">Add to Menu</button>
                  </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card m-b-0">
          <div class="card-header " role="tab" id="store_page">
            <h4 class="card-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#store_pages" aria-expanded="false" aria-controls="store_pages">
                  Pages
                </a>
              </h4>
          </div>
          <div id="store_pages" class="collapse" role="tabcard" aria-labelledby="store_page">
            <div class="card-block">
              <form id="pageForm" action="{{ url('/merchant/menus/items').'/'.$menu->slug }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="page">
                <div class="form-group">
                  <select id="page" name="page" class="full-width form-control" data-init-plugin="select2" data-placeholder="Search page">
                    @if($pages->count())
                      @foreach($pages as $page)
                      <option value="{{ $page->id }}">{{ $page->title }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
                <div class="form-group pull-right sm-pull-reset my-3">
                  <button type="submit" class="btn btn-action-add">Add to Menu</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card m-b-0">
          <div class="card-header " role="tab" id="store_category">
            <h4 class="card-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#store_categories" aria-expanded="false" aria-controls="store_categories">
                  Categories
                </a>
              </h4>
          </div>
          <div id="store_categories" class="collapse" role="tabcard" aria-labelledby="store_category">
            <div class="card-block">
              <form id="categoryForm" action="{{ url('/merchant/menus/items').'/'.$menu->slug }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="category">
                <div class="form-group">
                  <select id="category" name="category" class="full-width form-control" data-init-plugin="select2" data-placeholder="Search category">
                    <option value=""></option>
                    <option value="categories">all categories</option>
                    @if($categories->count())
                      @foreach($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
                <div class="form-group pull-right sm-pull-reset my-3">
                    <button type="submit" class="btn btn-action-add">Add to Menu</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card m-b-0">
          <div class="card-header " role="tab" id="store_product">
            <h4 class="card-title">
                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#store_products" aria-expanded="false" aria-controls="store_products">
                  Products
                </a>
              </h4>
          </div>
          <div id="store_products" class="collapse" role="tabcard" aria-labelledby="store_product">
            <div class="card-block">
              <form id="productForm" action="{{ url('/merchant/menus/items').'/'.$menu->slug }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="product">
                <div class="form-group">
                  <select id="product" name="product" class="full-width form-control" data-init-plugin="select2" data-placeholder="Search product">
                    <option value=""></option>
                    <option value="products">all products</option>
                    @if($products->count())
                      @foreach($products as $product)
                      <option value="{{ $product->id }}">{{ $product->name }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
                <div class="form-group pull-right sm-pull-reset my-3">
                    <button type="submit" class="btn btn-action-add">Add to Menu</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- END card -->
    </div>
    <div class="col-md-8">
      <form action="{{ route('menus.update', $menu) }}" method="post">
      {{ csrf_field() }}
      {{ method_field('patch') }}
      <input type="hidden" name="menu" id="menu" value="{{ $menu->id }}">
        <!-- START card -->
        <div class="card">
          <div class="card-block">
              <div class="form-group{{ $errors->has('menu_name') ? ' has-danger' : '' }}">
                <label for="menu_name">Menu Name</label>
                <input type="text" class="form-control{{ $errors->has('menu_name') ? ' form-control-danger' : '' }}" name="menu_name" value="{{ old('menu_name', $menu->name) }}" required>
                
                @if($errors->has('menu_name'))
                <div class="form-control-feedback">{{ $errors->first('menu_name') }}</div>
                @endif

              </div>
              
              <div class="checkbox check-info">
                <input type="checkbox" value="1" id="active" name="active" {{ $menu->active ? 'checked' : '' }}>
                <label for="active" class="form-check-label">Active</label>
              </div>
              @if($errors->has('active'))
              <div class="form-control-feedback">{{ $errors->first('active') }}</div>
              @endif
              
              @if(auth()->user()->can('delete menus'))
                <button id="btnDeleteMenu" class="btn btn-danger btn-cons m-t-10 btn-action-delete" type="button" data-id="{{ $menu }}">
                  <span class="bold">Delete Menu</span>
                </button>
              @endif
          </div>
        </div>
        <!-- END card -->
        <div class="card">
          <div class="card-header ">
            <h5 class="card-title">Menu Structure</h5>
          </div>
          <div class="card-block">
            <input type="hidden" name="menu_items" id="menu_items">
            <div class="dd" id="menuItems">
              @if($menu->items->count())
              <ol class="dd-list">
                 @include ('menu::partials._items', ['items' => $menu->items->where('parent_id', null)])
              </ol>
              @else
              <p class="text-center notification">This menu doesn't have any items.</p>
              @endif
              
            </div>
          </div>
        </div>

          @include ('zpanel.partials._form_actions', ['path' => route('menus.index')])
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts') 
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="https://dbushell.github.io/Nestable/jquery.nestable.js" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/js/menu.js') }}" type="text/javascript"></script>
@endsection

@section('page_scripts')
<script>
  $(document).ready(function() {

    menu.init();
    menu.validateCustomLinks();
    menu.validateCategoryForm();
    menu.validateProductForm();
    menu.validatePageForm();

  });
</script>
@endsection
