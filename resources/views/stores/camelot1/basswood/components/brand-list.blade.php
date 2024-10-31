@php
    use Shopbox\Transformers\StoreFront\BrandTransformer;
@endphp

@if($section['disabled'] === false)
    @php
        $handles = [];

        foreach ($section['block_order'] as $block) {
          array_push($handles, $section['blocks'][$block]['settings']['brand']);
        }

        $brands  = fractal()
                    ->collection($store->brands()->whereIn('slug', array_filter($handles))->orderByRaw('FIELD(slug, ?)', $handles)->get())
                    ->transformWith(new BrandTransformer)
                     ->toArray()['data'];
    @endphp
    <div id="shopbox-section-{{ $section_id }}" data-section-id="{{ $section_id }}" data-section-type="brand-list" class="brand-area ptb-40 gray-bg2">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div id="BrandSlider-{{ $section_id }}" data-autoplay="{{ $section['settings']['autoplay'] }}" data-loop="{{ $section['settings']['loop'] }}" class="brand-active owl-carousel">
                        @if(count($brands))
                            @foreach($handles as $handle)
                                @include($theme_path.'.partials.brand.item', ['brands' => $brands, 'handle' => $handle])
                            @endforeach
                        @else
                            @for($i = 0; $i < 6; $i++)
                                <div class="single-brand">
                                    <a href="#">
                                        <img src="https://via.placeholder.com/195x110/c4c6c8/f2f2f2?text=Image" alt="Brand name">
                                    </a>
                                </div>
                            @endfor
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@php

    $settings = [
        "name" => "Brand List",
        "section" => $section_id, 
        "type" => "content_for_index",
        "disabled" => false,
        "settings" => [
            [
              "type" => "checkbox",
              "id" => "loop",
              "label" => "Enable infinite loop"
            ],
            [
              "type" => "checkbox",
              "id" => "autoplay",
              "label" => "Enable autoplay"
            ]
        ],
        "blocks" => [
            [
                "type" => "image",
                "name" => "Image",
                "settings" => [
                  [
                    "type" => "brand",
                    "id" => "brand",
                    "label" => "Brand"
                  ]
                ]
            ]
        ],
        "defaults" => [
            "blocks" => [
                [
                    "type" => "image",
                    "settings" => [
                        "brand" => ""
                    ]
                ]
            ]
        ]
    ];

    session()->push('schema', $settings);

@endphp