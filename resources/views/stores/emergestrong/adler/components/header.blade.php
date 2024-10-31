@php
  use Shopbox\Transformers\StoreFront\MenuCollectionTransformer;

  $menu = $mega_menu = [];

  if($section['settings']['header_main_menu'] && $menus->where('slug', $section['settings']['header_main_menu'])->count()) {
      $menu = fractal()
        ->item($menus->where('slug', $section['settings']['header_main_menu'])->first())
        ->transformWith(new MenuCollectionTransformer)
        ->toArray()['data'];
  }

  if($section['settings']['header_enable_secondary_menu'] && $menus->where('slug', $section['settings']['header_secondary_menu'])->count()) {
    $mega_menu = fractal()
      ->item($menus->where('slug', $section['settings']['header_secondary_menu'])->first())
      ->transformWith(new MenuCollectionTransformer)
      ->toArray()['data'];
  }

@endphp

<div id="shopbox-section-header">
  <header id="header">
        @if($section['settings']['show_top_bar'])
        <div class="b-header b-header_bg">
            <div class="container">
                <div class="b-header_topbar row clearfix">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="b-top_bar_left float-md-left text-center">
                            {{ $section['settings']['top_bar_text'] }} 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="b-header b-header_main">
          <div class="container">
            <div class="clearfix row">
              @if($section['settings']['align_logo'] == 'left')
                @include($theme_path.'.partials.logo', ['settings' => $section['settings']])
                @include($theme_path.'.partials.menu', ['settings' => $section['settings'], 'menu' => $menu])
              @elseif($section['settings']['align_logo'] == 'center')
                 @include($theme_path.'.partials.menu', ['settings' => $section['settings'], 'menu' => $menu])
                 @include($theme_path.'.partials.logo', ['settings' => $section['settings']])
              @endif
              
              <div class="col-xl-5 col-lg-5 col-mb-4 col-sm-8 col-xs-6">
                <div class="b-header_right">
                  @if(auth()->check())
                    <div class="b-main_menu b-header_links hidden-sm-down">
                      <ul class="categories pl-0 mb-0 list-unstyled">
                          <li class="b-has_sub b-dropdown_wrapper from-bottom">
                            <a href="#" target="_self" class=" description "><span class="top">{{ auth()->user()->firstname }}</span><i class="aapl-chevron-down"></i></a> <div class="b-dropdown_content sub-holder dropdown-left glyphicon-arrow-down hidden"><div class="dropdown-inner"><div class="row"><div class="col-xs-12 col-sm-12"><div class="menu-item"><div class="categories"><div class="row">
                              <div class="col-sm-12 hover-menu">
                              <ul>
                              <li><a href="/account" target="_self">Profile</a></li>
                              <hr>
                              <li>
                                <a href="/logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                    
                                </form>
                              </li>
                              </ul>
                            </div></div></div></div></div></div></div></div></li>
                      </ul>   
                    </div>
                  @else
                  <div class="b-header_links hidden-sm-down">
                    <ul class="pl-0 mb-0 list-unstyled">
                        <li>
                          <a href="/login">Login / Register</a>
                        </li>
                    </ul>   
                  </div>
                  @endif
                  @if($settings['enable_multicurrency'])
                  @php
                    $currencies = array_filter(explode(',', $settings['currencies']));
                  @endphp
                  <div class="form-group hidden-sm-down">
                    <select class="form-control" name="currencies">
                      <option value="{{ $store->setting->currency->iso_code }}"
                        @if(request()->cookie('currency') === $store->setting->currency->iso_code) selected="selected" @endif>
                        {{ $store->setting->currency->iso_code }}</option>
                      @foreach($currencies as $currency)
                        <option value="{{ $currency }}"
                        @if(request()->cookie('currency') === $currency) selected="selected" @endif>
                      {{ $currency }}</option>
                      @endforeach
                    </select>
                  </div>
                  @endif
                  <div class="b-search_icon">
                    <a href="javascript:;" id="b-search_toggle" class="d-inline-block">
                      <i class="aapl-magnifier icons"></i>
                    </a>
                  </div>
                  <div class="b-wishlist_icon">
                    <a href="/account?tab=wishlist" class="d-inline-block">
                      <i class="aapl-heart"></i>
                      @if(auth()->guard('web')->check() && auth()->user()->wishlists->count())
                        <span>{{ auth()->user()->wishlists->count() }}</span>
                      @endif
                    </a>
                  </div>
                  <div class="b-cart_basket pr-0">
                    <a href="javascript:void(0);" id="b-mini_cart" class="d-inline-block">
                      <i class="aapl-bag2 icons"></i>
                      <span class="b-cart_totals">
                        <span class="b-cart_number">0</span>
                      </span>
                    </a>
                  </div>
                  <div class="hidden-lg-up">
                    <i class="aapl-menu icons b-nav_icon" id="b-nav_icon"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @if($section['settings']['header_enable_secondary_menu'])
            <div class="row text-center mega-menu">
              @include($theme_path.'.partials.menu-fullwidth', ['settings' => $section['settings'], 'mega_menu' => $mega_menu])
            </div>
          @endif
        </div>
  </header> 
</div>

@php
  $settings = [
      "name" => "Header",
      "section" => "header", 
      "type" => "header",
      "disabled" =>  false,
      "settings" => [
        [
          "type" => "radio",
          "id" => "align_logo",
          "label" => "Logo alignment",
          "options" => [
            ["value" => "left", "label" => "Left"],
            ["value" => "center", "label" => "Centered"]
          ]
        ],
        [
          "type" => "image_picker",
          "id" => "logo",
          "label" => "Logo image"
        ],
        [
          "type" => "link_list",
          "id" => "header_main_menu",
          "label" => "Main menu"
        ],
        [
          "type" => "checkbox",
          "id" => "header_enable_secondary_menu",
          "label" => "Enable mega menu"
        ],
        [
          "type" => "link_list",
          "id" => "header_secondary_menu",
          "label" => "Mega menu"
        ],
        [
          "type" => "checkbox",
          "id" => "show_top_bar",
          "label" => "Show top bar"
        ],
        [
          "type" => "text",
          "id" => "top_bar_text",
          "label" => "Top bar text"
        ],
        [
          "type" => "url",
          "id" => "top_bar_url",
          "label" => "Top bar url"
        ]
      ]
  ];

  session()->push('schema', $settings);
@endphp
