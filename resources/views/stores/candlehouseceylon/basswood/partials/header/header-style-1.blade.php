<header id="header_style_1">
    <div class="header-container">
        <div class="header-top-area">
            <div class="container-fluid pl-80 pr-80">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="contact-box">
                            <div class="header-top-phone">
                                <p>Call Us : <span>{{ $section['settings']['phone'] }}</span></p>
                            </div>
                            <div class="header-top-phone">
                                <p>Email : <span><a href="#">{{ $section['settings']['email'] }}</a></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                      @include($theme_path.'.partials.header.style-1-3.meta-menu')
                    </div>
                </div>
            </div>
        </div>
        <div class="header-bottom-area header-sticky">
            <div class="container-fluid pl-80 pr-80">
                <div class="row align-items-center">
                    <div class="col col-xl-2 col-lg-3 col-md-6 col-xs-12">
                        <div class="logo">
                          @if($section['settings']['logo'])
                            <a href="{{ getStoreUrl($store) }}"><img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['logo']) }}" class="img-fluid" alt="{{ $store->store_name }}" style="max-height: {{ $section['settings']['logo_max_height'] }}px"></a>
                          @else
                            <a href="{{ getStoreUrl($store) }}"><img src="https://via.placeholder.com/123x36.png?text=Logo" class="img-fluid" alt="{{ $store->store_name }}" style="max-height: {{ $section['settings']['logo_max_height'] }}px"></a>
                          @endif
                        </div>
                    </div>
                    <div class="col-xl-10 col-lg-9 col-md-6">
                        <div class="header-menu-area float-left">
                          @include($theme_path.'.partials.header.main-menu')
                        </div>
                        <div class="shoppingcart-search-item float-right">
                          <ul>
                              <li><a class="sidebar-trigger-search" href="#"><i class="zmdi zmdi-search"></i></a></li>
                              <li><a href="#"><i class="zmdi zmdi-shopping-cart-plus"></i> <span class="item-total bigcounter">0</span></a>
                                <mini-cart></mini-cart>
                              </li>
                          </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">  
                    <div class="mobile-menu d-lg-none d-xl-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@php

  $settings = [
    "name" => "Header Style 1",
    "section" => "header_style_1",
    "type" => "header",
    "settings" => [
      [
        "type" => "image_picker",
        "id" => "logo",
        "label" => "Logo image"
      ],
      [
        "type" => "number",
        "id" => "logo_max_height",
        "label" => "Custom logo height",
        "info" => "pixel (px)"
      ],
      [
        "type" => "link_list",
        "id" => "menu",
        "label" => "Main menu"
      ],
      [
        "type" => "text",
        "id" => "phone",
        "label" => "Phone"
      ],
      [
        "type" => "text",
        "id" => "email",
        "label" => "Email"
      ]
    ]
  ];

  session()->push('schema', $settings);

@endphp