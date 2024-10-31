<header id="header_style_2">
    <div class="header-container">
        <div class="header-bottom-area header-transparent header-sticky {{ !$settings['box_view'] ? 'header-3' : '' }} {{ $settings['sections'][$settings['content_for_index'][0]]['type'] === 'slideshow' ? 'header-absolute' : ''}}">
            <div class="container-fluid pl-80 pr-80">
                <div class="row align-items-center">
                    <div class="col col-xl-2 col-lg-3 col-md-6 col-xs-12">
                        <div class="logo">
                          @if($section['settings']['logo'])
                            <a href="{{ getStoreUrl($store) }}"><img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['logo']) }}" class="img-fluid" alt="{{ $store->store_name }}"></a>
                          @else
                            <a href="{{ getStoreUrl($store) }}"><img src="https://via.placeholder.com/123x36.png?text=Logo" class="img-fluid" alt="{{ $store->store_name }}"></a>
                          @endif
                        </div>
                    </div>
                    <div class="col-xl-10 col-lg-9 col-md-6">
                      <div class="header-menu-area header-menu-2 float-left">
                        @include($theme_path.'.partials.header.main-menu')
                      </div>
                        @include($theme_path.'.partials.header.style-2-4.meta-menu')
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
    "name" => "Header Style 2",
    "section" => "header_style_2",
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
      ]
    ]
  ];

  session()->push('schema', $settings);

@endphp