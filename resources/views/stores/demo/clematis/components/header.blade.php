<div id="shopbox-section-header">
    <header class="{{ $section['settings']['nav_layout'] == 2 ? 'version_2' : 'version_1' }}">
        <div class="layer"></div>
        @if($section['settings']['show_top_bar'] && $section['settings']['top_bar_text'])
            @include($theme_path.'.partials.header._topbar', ['section' => $section])
        @endif
        <div class="main_header">
            <div class="container">
                <div class="row small-gutters">
                    <div class="col-xl-3 col-lg-3 d-lg-flex align-items-center">
                        <div id="logo">
                            <a href="/">
                              @if($section['settings']['logo'])
                                <img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['logo']) }}" alt="{{ $store->store_name }}" width="100" height="35" class="img-fluid">
                              @else
                                <img src="//via.placeholder.com/100x35?text=Logo" alt="{{ $store->store_name }}" width="100" height="35">
                              @endif
                            </a>
                        </div>
                    </div>
                    <nav class="col-xl-6 col-lg-7">
                        <a class="open_close" href="javascript:void(0);">
                            <div class="hamburger hamburger--spin">
                                <div class="hamburger-box">
                                    <div class="hamburger-inner"></div>
                                </div>
                            </div>
                        </a>
                        @include($theme_path.'.partials.header._menu', ['section' => $section])
                    </nav>
                    <div class="col-xl-3 col-lg-2 d-lg-flex align-items-center justify-content-end text-right">
                        @if($section['settings']['nav_layout'] == 1)
                            @if($section['settings']['phone_no'])
                            <a class="phone_top" href="tel://{{ $section['settings']['phone_no'] }}">
                                <strong>
                                @if($section['settings']['phone_text'])
                                    <span>{{ $section['settings']['phone_text'] }}</span>
                                @endif
                                    {{ $section['settings']['phone_no'] }}
                                </strong>
                            </a>
                            @endif
                        @elseif($section['settings']['nav_layout'] == 2)
                        <ul class="top_tools">
                            <li>
                                <mini-cart></mini-cart>
                                <!-- /dropdown-cart-->
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
                                          <a href="account.html" class="btn_1">Sign In or Sign Up</a>
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
                                <!-- /dropdown-access-->
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="search_panel"><span>Search</span></a>
                            </li>
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if($section['settings']['nav_layout'] == 1)
        @include($theme_path.'.partials.header._categories')
        @endif
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
          "type" => "image_picker",
          "id" => "logo",
          "label" => "Logo image"
        ],
        [
          "type" => "link_list",
          "id" => "menu",
          "label" => "Menu"
        ],
        [
            "type" => "select",
            "id" => "nav_layout",
            "label" => "Layout",
            "options" =>  [
                [ "value" => "1", "label" => "Inline" ],
                [ "value" => "2", "label" => "Condensed" ]
            ]
        ],
        [
          "type" => "link_list",
          "id" => "menu_cat",
          "label" => "Categories menu",
          "info" => "Displays only if Layout is set to inline."
        ],
        [
          "type" => "text",
          "id" => "phone_text",
          "label" => "Phone number text"
        ],
        [
          "type" => "text",
          "id" => "phone_no",
          "label" => "Phone number"
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
        ]
      ],
      "blocks" => [
            [
                "type" => "mega_menu",
                "name" => "Mega menu",
                "settings" => [
                  [
                    "type" => "text",
                    "id" => "menu_item",
                    "label" => "Menu item",
                    "info" => "Enter menu item to apply a mega menu dropdown."
                  ],
                  [
                    "type" => "image_picker",
                    "id" => "image",
                    "label" => "Image",
                    "info" => "400 x 550px .jpg recommended"
                  ],
                  [
                    "type" => "url",
                    "id" => "image_link",
                    "label" => "Link"
                  ]
                ]
            ]
        ],
        "defaults" => [
            "blocks" => [
                [
                  "type" => "mega_menu",
                  "settings" => [
                    "menu_item" => "",
                    "image" => "",
                    "image_link" => ""
                  ]
                ]
            ]
        ]
  ];

  session()->push('schema', $settings);
@endphp