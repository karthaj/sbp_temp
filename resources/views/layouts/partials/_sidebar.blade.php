<nav class="page-sidebar" data-pages="sidebar">
  <!-- BEGIN SIDEBAR MENU TOP TRAY CONTENT-->
  <div class="sidebar-overlay-slide from-top" id="appMenu">
        content goes here
  </div>
  <!-- END SIDEBAR MENU TOP TRAY CONTENT-->
  <!-- BEGIN SIDEBAR MENU HEADER-->
  <div class="sidebar-header">
    <img src="{{ asset('assets/img/logo.png') }}" alt="logo" class="brand" data-src="{{ asset('assets/img/logo.png') }}" data-src-retina="{{ asset('assets/img/logo_2x.png') }}" width="78" height="22">
    <div class="sidebar-header-controls">
      <button type="button" class="btn btn-xs sidebar-slide-toggle btn-link m-l-20 hidden-md-down active" data-pages-toggle="#appMenu"><i class="fa fa-angle-down fs-16"></i>
          </button>
      <button type="button" class="btn btn-link hidden-md-down" data-toggle-pin="sidebar"><i class="fa fs-12"></i>
      </button>
    </div>
  </div>
  <!-- END SIDEBAR MENU HEADER-->
  <!-- START SIDEBAR MENU -->
  <div class="sidebar-menu">
    <!-- BEGIN SIDEBAR MENU ITEMS-->
    <ul class="menu-items">
      @include('layouts.partials._sidebar_items', ['items' => $items])
    </ul>
    <div class="sb-bottom">
      <ul class="menu-items text-left">
      <li class="m-t-20" data-step="{{ $items->count() + 1}}" data-intro="<h4>View Storefront</h4><p>Preview your storefront. If your storefront is locked, you will be prompted to enter the storefront password.</p>" data-position="right">
        <a href="{{ getStoreUrl(session('store')) }}" target="_blank">
          <span class="title">View Store</span>
        </a>
        <span class="icon-thumbnail"><i class="aapl-file-preview"></i></span>
      </li>
      </ul>
      
      <div class="bg-signout">
        <a href="{{ route('admin.logout') }}" class="" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        <span class="signout"><small>SIGN</small><i class="pg-power red"></i><small >UT</small></span>
        </a>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
      </div>
    
    </div>
    <div class="clearfix"></div>

  </div>
  <!-- END SIDEBAR MENU -->
</nav>