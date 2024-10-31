<header  id="header_style_3">
  <div class="header-container">
      <div class="header-top-area">
          <div class="container">
              <div class="row">
                  <div class="col-md-6 col-12">
                      <div class="contact-box">
                          <div class="header-top-phone">
                              <p>Call us : <span>{{ $section['settings']['phone'] }}</span></p>
                          </div>
                          <div class="header-top-phone">
                              <p>email : <span><a href="#">{{ $section['settings']['email'] }}</a></span></p>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-6 col-12">
                    @include($theme_path.'.partials.header.style-1-3.meta-menu')
                  </div>
              </div>
          </div>
      </div>
      <div class="header-middle-area">
        <div class="container">
          <div class="row align-items-center">
              <div class="col-md-4 col-xs-12">
                  <div class="wellcome-mes">
                      <p>{{ $section['settings']['message'] }}</p>
                  </div>
              </div>
              <div class="col-md-4 col-xs-12">
                  <div class="logo text-center">
                    @if($section['settings']['logo'])
                      <a href="{{ getStoreUrl($store) }}"><img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['logo']) }}" class="img-fluid" alt="{{ $store->store_name }}"></a>
                    @else
                      <a href="{{ getStoreUrl($store) }}"><img src="https://via.placeholder.com/123x36.png?text=Logo" class="img-fluid" alt="{{ $store->store_name }}"></a>
                    @endif
                  </div>
              </div>
              <div class="col-md-4 col-xs-12">
                  <div class="shoppingcart-search-item text-right">
                    <ul>
                        <li><a class="sidebar-trigger-search" href="#"><i class="zmdi zmdi-search"></i></a></li>
                        <li><a href="#"><i class="zmdi zmdi-shopping-cart-plus"></i>  <span class="item-total bigcounter">0</span></a>
                            <mini-cart></mini-cart>
                        </li>
                    </ul>
                  </div>
              </div>
          </div>
        </div>
      </div>
      <div class="header-bottom-area  header-sticky">
        <div class="container">
          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="header-menu-area header-menu-3 text-center">
                @include($theme_path.'.partials.header.main-menu')                     
              </div>
            </div>
          </div>
          <div class="row">
             <div class="col-12">
                 <div class="logo-3 d-none">
                    @if($section['settings']['logo'])
                      <a href="{{ getStoreUrl($store) }}"><img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['logo']) }}" alt="{{ $store->store_name }}"></a>
                    @else
                      <a href="{{ getStoreUrl($store) }}"><img src="https://via.placeholder.com/123x36.png?text=Logo" alt="{{ $store->store_name }}"></a>
                    @endif
                 </div>
             </div>
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
    "name" => "Header Style 3",
    "section" => "header_style_3",
    "type" => "header",
    "settings" => [
      [
        "type" => "image_picker",
        "id" => "logo",
        "label" => "Logo image"
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
      ],
      [
        "type" => "text",
        "id" => "message",
        "label" => "Message"
      ]
    ]
  ];

  session()->push('schema', $settings);

@endphp