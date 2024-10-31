<div class="header ">
  <div class="b-r b-grey">
    <div class="brand inline">
      <a href="{{ route('theme.index') }}">
        <img src="https://shopbox.lk/images/shopbox-logo.png" alt="Shopbox" data-src="https://shopbox.lk/images/shopbox-logo.png" data-src-retina="https://shopbox.lk/images/shopbox-logo.png" class="img-fluid">
      </a>
    </div>
  </div>
  <ul class="no-margin no-style p-l-20">
    <li class="p-r-10 inline">
      <div class="dropdown">
        <a href="javascript:;" data-toggle="dropdown" aria-expanded="false">
          <span class="current__page">Home Page</span>
          <i class="header-icon aapl-chevron-down"></i>
        </a>

        <!-- START Notification Dropdown -->
        <div class="dropdown-menu dropdown-menu-left page-list" role="menu" style="max-height: 300px; overflow: auto;">
          <a href="/" data-title="Home page" class="dropdown-item">Home page</a>
          @if($product)
          <a href="/products/{{ $product->slug }}" data-title="Product page" class="dropdown-item">Product page</a>
          @endif
          <a href="/categories" data-title="Categories" class="dropdown-item">Categories</a>
          <a href="/cart" data-title="Cart" class="dropdown-item">Cart</a>
          @if($pages->count())
            @foreach($pages as $page)
              <a href="/pages/{{ $page->slug }}" data-title="{{ $page->title.' page' }}" class="dropdown-item">{{ $page->title }} page</a>
            @endforeach
          @endif
        </div>
      </div>
    </li>
  </ul>
  <ul class="mx-auto nav nav-tabs nav-tabs-simple nav-tabs-info hidden-xs-down">
    <li class="nav-item text-center">
      <a class="nav-link device-preview" href="javascript:;" data-device="mobile"><i class="aapl-phone h4"></i></a>
    </li>
    <li class="nav-item text-center">
      <a class="nav-link device-preview" href="javascript:;" data-device="tablet"><i class="aapl-tablet h4"></i></a>
    </li>
    <li class="nav-item text-center">
      <a class="nav-link device-preview active" href="javascript:;" data-device="desktop"><i class="aapl-desktop h4"></i></a>
    </li>
  </ul>
  <div class="d-flex align-items-center">
    <div class="pull-left p-r-10 fs-14 font-heading">
      @if(session('store')->template->theme->alias === $theme->alias)
        <span class="semi-bold">Active</span>
      @else
        <span class="semi-bold">Draft</span>
      @endif
      
    </div>
    <div id="editorAction">
            
      <save endpoint="{{ route('theme.config.store', $theme->alias) }}"></save>
   
    </div>
  </div>
</div>