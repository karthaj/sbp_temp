 @php
  use Shopbox\Transformers\StoreFront\MenuCollectionTransformer;

  $menu = [];

  if($section['settings']['menu_cat'] && $menus->where('slug', $section['settings']['menu_cat'])->count()) {
      $menu = fractal()
        ->item($menus->where('slug', $section['settings']['menu_cat'])->first())
        ->transformWith(new MenuCollectionTransformer)
        ->toArray()['data'];
  }

@endphp
 <!-- categories -->
 <div class="main_nav Sticky">
    <div class="container">
        <div class="row small-gutters">
            <div class="col-xl-3 col-lg-3 col-md-3">
                <nav class="categories">
                    <ul class="clearfix">
                        <li>
                            @if(count($menu))
                                <span>
                                    <a href="#">
                                        <span class="hamburger hamburger--spin">
                                            <span class="hamburger-box">
                                                <span class="hamburger-inner"></span>
                                            </span>
                                        </span>
                                        Categories
                                    </a>
                                </span>
                            <div id="menu">
                                <ul>
                                @foreach($menu['links'] as $link)
                                    <li><span><a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }}</a></span>
                                        @if(count($link['sub']))
                                            <ul>
                                            @foreach($link['sub'] as $link)
                                                <li><a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }}</a></li>
                                            @endforeach
                                            </ul>
                                        @endif
                                @endforeach
                                </ul>
                            </div>
                            @endif
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col-xl-6 col-lg-7 col-md-6 d-none d-md-block">
                <form action="/search" method="get" role="search">
                    <div class="custom-search-input">
                        <input type="text" name="q" placeholder="Search products">
                        <button type="submit"><i class="header-icon_search_custom"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-xl-3 col-lg-2 col-md-3">
                <ul class="top_tools">
                    <li>
                        <mini-cart></mini-cart>
                    </li>
                    <li>
                        <a href="#0" class="wishlist"><span>Wishlist</span></a>
                    </li>
                    <li>
                        <div class="dropdown dropdown-access">
                            @if(auth()->check())
                                <a href="/account?tab=profile" class="access_link"><span>Account</span></a>
                            @else
                                <a href="/login" class="access_link"><span>Login</span></a>
                            @endif
                            <div class="dropdown-menu">
                                @if(!auth()->check())
                                    <a href="/login" class="btn_1">Sign In or Sign Up</a>
                                @endif
                                @if(auth()->check())
                                <ul>
                                    <li>
                                        <a href="/account?tab=order_list"><i class="ti-package"></i>My Orders</a>
                                    </li>
                                    <li>
                                        <a href="/account?tab=profile"><i class="ti-user"></i>My Profile</a>
                                    </li>
                                    <li>
                                        <a href="/logout" onclick="event.preventDefault();document.getElementById('signout-form').submit();"><i class="ti-back-left"></i>Sign out</a>
                                        <form id="signout-form" action="/logout" method="POST" style="display: none;"></form>
                                    </li>
                                </ul>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="btn_search_mob"><span>Search</span></a>
                    </li>
                    @if($store->categories->where('is_root_category', 1)->count())
                    <li>
                        <a href="#menu" class="btn_cat_mob">
                            <div class="hamburger hamburger--spin" id="hamburger">
                                <div class="hamburger-box">
                                    <div class="hamburger-inner"></div>
                                </div>
                            </div>
                            Categories
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- /row -->
    </div>
    <div class="search_mob_wp">
        <form action="/search" method="get" role="search">
            <input type="text" class="form-control" name="q" placeholder="Search products">
            <input type="submit" class="btn_1 full-width" value="Search">
        </form>
    </div>
    <!-- /search_mobile -->
</div>
<!-- /categories -->