@php
use Shopbox\Transformers\StoreFront\MenuCollectionTransformer;

$fmenu1 = $fmenu2 = $fmenu3 = '';
$menus = $store->menus()->with(['items' => function ($query) {
            $query->whereNull('parent_id');
        }])->where('active', 1)->get();

if($menus->where('slug', $section['settings']['footer_menu_1'])->count()) {
  $fmenu1 = fractal()
        ->item($menus->where('slug', $section['settings']['footer_menu_1'])->first())
        ->transformWith(new MenuCollectionTransformer)
        ->toArray()['data'];
}

if($menus->where('slug', $section['settings']['footer_menu_2'])->count()) {
  $fmenu2 = fractal()
        ->item($menus->where('slug', $section['settings']['footer_menu_2'])->first())
        ->transformWith(new MenuCollectionTransformer)
        ->toArray()['data'];
}

if($menus->where('slug', $section['settings']['footer_menu_3'])->count()) {
  $fmenu3 = fractal()
        ->item($menus->where('slug', $section['settings']['footer_menu_3'])->first())
        ->transformWith(new MenuCollectionTransformer)
        ->toArray()['data'];
}

@endphp

<div id="shopbox-section-footer">
  <section id="footer">
    <footer class="b-footer_container color-scheme-light hidden -sm-down">
      <div class="container b-main_footer">
          <!-- footer-main -->
          <aside class="row justify-content-center clearfix">
            @if($section['settings']['logo'] || $settings['social_facebook_link'] 
                || $settings['social_twitter_link'] || $settings['social_pinterest_link'] 
                || $settings['social_instagram_link'] || $settings['social_youtube_link'] 
                || $settings['social_vimeo_link'])

              <div class="b-footer_column col-md-12 col-sm-12">
                  <div class="b-footer_block">
                      <div class="b-footer_block_in">
                          @if($section['settings']['logo'])
                            <p class="text-center mb-0">
                              <img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['logo']) }}" class="d-block m-auto img-fluid" alt="{{ $store->store_name }}">
                            </p>
                          @endif

                          @if($settings['social_facebook_link'] || $settings['social_twitter_link'] 
                              || $settings['social_pinterest_link'] || $settings['social_instagram_link'] 
                              || $settings['social_youtube_link'] || $settings['social_vimeo_link'])
                            <ul class="b-social-icons text-center mt-3">
                              @if($settings['social_facebook_link'])
                                <li class="b-social_facebook">
                                  <a href="{{ $settings['social_facebook_link'] }}" target="_blank"><i class="fa fa-facebook"></i>Facebook</a>
                                </li>
                              @endif
                              @if($settings['social_twitter_link'])
                                <li class="b-social_twitter">
                                  <a href="{{ $settings['social_twitter_link'] }}" target="_blank"><i class="fa fa-twitter"></i>Twitter</a>
                                </li>
                              @endif
                              @if($settings['social_pinterest_link'])
                                <li class="b-social_pinterest">
                                  <a href="{{ $settings['social_pinterest_link'] }}" target="_blank"><i class="fa fa-pinterest"></i>Pinterest</a>
                                </li>
                              @endif
                              @if($settings['social_instagram_link'])
                                <li class="b-social_instagram">
                                  <a href="{{ $settings['social_instagram_link'] }}" target="_blank"><i class="fa fa-instagram"></i>Instagram</a>
                                </li>
                              @endif
                              @if($settings['social_youtube_link'])
                                <li class="b-social_youtube">
                                  <a href="{{$settings['social_youtube_link'] }}" target="_blank"><i class="fa fa-youtube-play"></i>Youtube</a>
                                </li>
                              @endif
                              @if($settings['social_vimeo_link'])
                                <li class="b-social_vimeo">
                                  <a href="{{ $settings['social_vimeo_link'] }}" target="_blank"><i class="fa fa-vimeo-square"></i>Vimeo</a>
                                </li>
                              @endif
                            </ul>
                            <br>
                          @endif
                      </div>
                  </div>
              </div>
            @endif
              @if($section['settings']['footer_menu_title_1'] || $fmenu1)
                <div class="b-footer_column col-lg-3 col-md-3 col-sm-6 mb-4 text-center text-sm-left">
                    <div class="b-footer_block">
                      @if($section['settings']['footer_menu_title_1'])
                        <h5 class="b-footer_block_title">{{ $section['settings']['footer_menu_title_1'] }}</h5>
                      @endif
                      <div class="b-footer_block_in">
                        @if($fmenu1)
                          <ul class="b-footer_menu">
                            @foreach($fmenu1['links'] as $link)
                              <li><a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }}</a></li>
                            @endforeach
                          </ul>
                        @endif
                      </div>
                    </div>
                </div>
              @endif
              @if($section['settings']['footer_menu_title_2'] || $fmenu2)
              <div class="b-footer_column col-lg-3 col-md-3 col-sm-6 mb-4 text-center text-sm-left">
                  <div class="b-footer_block">
                    @if($section['settings']['footer_menu_title_2'])
                      <h5 class="b-footer_block_title">{{ $section['settings']['footer_menu_title_2'] }}</h5>
                    @endif
                    <div class="b-footer_block_in">
                      @if($fmenu2)
                        <ul class="b-footer_menu">
                          @foreach($fmenu2['links'] as $link)
                            <li><a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }}</a></li>
                          @endforeach
                        </ul>
                      @endif
                    </div>
                  </div>
              </div> 
              @endif
              @if($section['settings']['footer_menu_title_3'] || $fmenu3)
              <div class="b-footer_column col-lg-3 col-md-3 col-sm-6 mb-4 text-center text-sm-left">
                  <div class="b-footer_block">
                    @if($section['settings']['footer_menu_title_3'])
                      <h5 class="b-footer_block_title">{{ $section['settings']['footer_menu_title_3'] }}</h5>
                    @endif
                    <div class="b-footer_block_in">
                      @if($fmenu3)
                        <ul class="b-footer_menu">
                          @foreach($fmenu3['links'] as $link)
                            <li><a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }}</a></li>
                          @endforeach
                        </ul>
                      @endif
                    </div>
                  </div>
              </div>
              @endif
             
            @if($section['settings']['footer_menu_title_4'] || $section['settings']['footer_menu_text'] || $section['settings']['footer_address'] || $section['settings']['footer_phone'] || $section['settings']['footer_show_payment_icons'])
              <div class="b-footer_column col-lg-3 col-md-12 col-sm-12 mb-4 text-center text-sm-left">
                  <div class="b-footer_block">
                    @if($section['settings']['footer_menu_title_4'])
                      <h5 class="b-footer_block_title">{{ $section['settings']['footer_menu_title_4'] }}</h5>
                    @endif
                    <div class="b-footer_block_in">
                      @if($section['settings']['footer_menu_text'])
                        <p>{{ $section['settings']['footer_menu_text'] }}</p>
                      @endif
                      @if($section['settings']['footer_address'] || $section['settings']['footer_phone'])
                        <div class="b-contact_info">
                          @if($section['settings']['footer_address'])
                            <i class="aapl-map-marker d-inline-block"></i> {{ $section['settings']['footer_address'] }}
                            <br>
                          @endif
                          @if($section['settings']['footer_phone'])
                            <i class="aapl-telephone2 d-inline-block"></i> {{ $section['settings']['footer_phone'] }}
                            <br> 
                          @endif
                        </div>
                      @endif
                    </div>
                  </div>
              </div>
            @endif
          </aside>
          <!-- footer-main -->
          @if($section['settings']['footer_show_payment_icons'] && count($payments))
          <div class="row justify-content-center clearfix">
            @foreach($payments as $payment)
            <img src="{{ $payment }}" alt="payment logo" class="mr-2 mb-2" height="20">
            @endforeach
          </div>
          @endif
      </div>
      <!-- footer-bar -->
      <div class="b-copyrights_wrapper">
          <div class="container">
              <div class="d-footer_bar row">
                  <div class="col-md-6 text-center text-sm-left">
                      <i class="fa fa-copyright"></i> {{ date("Y") }} 
                      <a href="{{ getStoreUrl($store) }}" class="text-white">{{ $store->store_name }}</a> 
                  </div>
                  @if($store->setting->show_branding)
                  <div class="col-md-6 text-center text-sm-right">
                      powered by
                      <a href="//shopbox.lk" class="text-white">
                         ShopBox
                      </a> 
                  </div>
                  @endif
              </div>
          </div>
      </div>
      <!-- footer-bar -->
    </footer>
  </section>
</div>

@php
  $settings = [
    "name" => "Footer",
    "section" =>  'footer', 
    "type" =>  "footer",
    "disabled" =>  false,
    "settings" => [
      [
        "type" => "image_picker",
        "id" => "logo",
        "label" => "Logo"
      ],
      [
        "type" => "text",
        "id" => "footer_menu_title_1",
        "label" => "Menu title 1"
      ],
      [
       "type" => "link_list",
       "id" => "footer_menu_1",
       "label" => "Menu 1"
      ],
      [
        "type" => "text",
        "id" => "footer_menu_title_2",
        "label" => "Menu title 2"
      ],
      [
        "type" => "link_list",
        "id" => "footer_menu_2",
        "label" => "Menu 2"
      ],
      [
        "type" => "text",
        "id" => "footer_menu_title_3",
        "label" => "Menu heading 3",
        "label" => "Menu title 3",
      ],
      [
        "type" => "link_list",
        "id" => "footer_menu_3",
        "label" => "Menu 3"
      ],
      [
        "type" => "text",
        "id" => "footer_menu_title_4",
        "label" => "Store Details",
      ],
      [
        "type" => "text",
        "id" => "footer_menu_text",
        "label" => "Store Description",
      ],
      [
        "type" => "text",
        "id" => "footer_address",
        "label" => "Address",
      ],
      [
        "type" => "text",
        "id" => "footer_phone",
        "label" => "Phone",
      ],
      [
        "type" => "checkbox",
        "id" => "footer_show_payment_icons",
        "label" => "Show payment icons"
      ]
    ]
  ];

  session()->push('schema', $settings);
@endphp