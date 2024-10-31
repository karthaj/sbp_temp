@if($section['disabled'] === false)
    <div id="shopbox-section-{{ $section_id }}">
        <div  class="banner-section-area mb-90">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="basswood-single-banner img-full">
                            <a href="{{ $section['settings']['banner_link_1'] }}">
                                @if($section['settings']['banner_img_1'])
                                    <img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['banner_img_1']) }}" alt="Banner Image">
                                @else
                                    <img src="https://via.placeholder.com/620x300/f2f2f2/dcdfde?text=Banner-620x300" alt="Banner Image">
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="basswood-single-banner img-full">
                            <a href="{{ $section['settings']['banner_link_2'] }}">
                                @if($section['settings']['banner_img_2'])
                                    <img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['banner_img_2']) }}" alt="Banner Image">
                                @else
                                    <img src="https://via.placeholder.com/620x300/f2f2f2/dcdfde?text=Banner-620x300" alt="Banner Image">
                                @endif 	
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="basswood-single-banner img-full">
                            <a href="{{ $section['settings']['banner_link_3'] }}">
                                @if($section['settings']['banner_img_3'])
                                    <img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['banner_img_3']) }}" alt="Banner Image">
                                @else
                                    <img src="https://via.placeholder.com/620x300/f2f2f2/dcdfde?text=Banner-620x300" alt="Banner Image">
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@php

    $settings = [
        "name" => "Banner",
        "section" => $section_id, 
        "type" => "content_for_index",
        "disabled" => false,
        "settings" => [
            [
              "type" => "image_picker",
              "id" => "banner_img_1",
              "label" => "Banner Image"
            ],
            [
              "type" => "text",
              "id" => "banner_link_1",
              "label" => "Banner Link"
            ],
            [
              "type" => "image_picker",
              "id" => "banner_img_2",
              "label" => "Banner Image"
            ],
            [
              "type" => "text",
              "id" => "banner_link_2",
              "label" => "Banner Link"
            ],
            [
              "type" => "image_picker",
              "id" => "banner_img_3",
              "label" => "Banner Image"
            ],
            [
              "type" => "text",
              "id" => "banner_link_3",
              "label" => "Banner Link"
            ]
        ]
    ];

    session()->push('schema', $settings);

@endphp