@php
  use Shopbox\Transformers\StoreFront\ProductTransformer;
@endphp

@if($section['disabled'] === false)
  @php
    $handles = [];
    $products = [];

    foreach ($section['block_order'] as $block) {
      array_push($handles, $section['blocks'][$block]['settings']['product']);
    }

    if(count($handles)) {
      $products  = fractal()
                  ->collection($store->products()->with(['variations.stock.storeStocks', 'images'])
                              ->whereIn('slug', array_filter($handles))
                              ->where('state', 1)->where('blocked', 0)->where('active', 1)->orderByRaw('FIELD(slug, ?)', $handles)->get())
                  ->transformWith(new ProductTransformer)
                  ->toArray()['data'];
    }
  @endphp

  <div class="product-section mb-25" data-section-id="{{ $section_id }}" data-section-type="product-grid">
      <div class="container">
          <div class="row">
            @if($section['settings']['title'] || $section['settings']['subtitle'])
              <div class="col-12">
                  <div class="section-title mb-50 text-center">
                    @if($section['settings']['title'])
                      <h2>{{ $section['settings']['title'] }}</h2>
                    @endif
                    @if($section['settings']['subtitle'])
                      <p>{{ $section['settings']['subtitle'] }}</p>
                    @endif
                  </div>
              </div>
            @endif
              
            <div class="col-12">  
                <div class="row">
                  @foreach($handles as $handle)
                    <div class="col-lg-3 col-md-4">
                      @include($theme_path.'.partials.product-grid.product', ['products' => $products, 'handle' => $handle])
                    </div>  
                  @endforeach    
                </div>
            </div>
          </div>
      </div>
  </div>
@endif

@php

  $settings = [
    "name" =>  "Product Grid",
    "section" =>  $section_id, 
    "type" => "content_for_index",
    "disabled" => false,
    "settings" => [
      [
        "type" => "radio",
        "id" => "style",
        "label" => "Style",
        "options" => [
          ["value" => "1", "label" => "Style 1"],
          ["value" => "2", "label" => "Style 2"]
        ]
      ],
      [
        "type" => "text",
        "id" => "title",
        "label" => "Title"
      ],
      [
        "type" => "text",
        "id" => "subtitle",
        "label" => "Title"
      ]
    ],
    "blocks" => [
      [
        "type" => "product",
        "name" => "Product",
        "settings" => [
          [
            "type" => "product",
            "id" => "product",
            "label" => "Select product"
          ]
        ]
      ]
    ],
    "defaults" => [
        "blocks" => [
          [
            "type" => "product",
            "settings" => [
                "product" => ""
            ]
          ]
        ]
    ]
  ];

  session()->push('schema', $settings);

@endphp