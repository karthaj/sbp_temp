@php
use Shopbox\Transformers\StoreFront\ProductCollectionTransformer;

$handles = [];

foreach ($section['block_order'] as $block) {
  array_push($handles, $section['blocks'][$block]['settings']['product']);
}

$products  = fractal()
            ->collection($store->products()->with(['variations.stock.storeStocks', 'images'])
                        ->whereIn('slug', $handles)
                        ->where('state', 1)->where('blocked', 0)->where('active', 1)->orderByRaw('FIELD(slug, ?)', $handles)->get())
            ->transformWith(new ProductCollectionTransformer)
            ->toArray()['data'];
            
@endphp
@if($section['disabled'] === false)
<div id="shopbox-section-{{ $section_id }}">
  <section id="{{ $section_id }}"> 
    <div class="b-section_title text-center">
      @if($section['settings']['title'])
        <h4 class="text-center text-uppercase">
          {{ $section['settings']['title'] }}
          <span class="b-title_separator"><span></span></span>
        </h4>
      @endif
    </div>
    <div class="b-products b-product_grid b-product_grid_four mb-4">
        <div class="container">
            <div class="row justify-content-center clearfix">
                @if($section['settings']['column_grid'] == 3)
                  @foreach($handles as $handle)
                    @include($theme_path.'.partials.product-grid-3', ['products' => $products, 'slug' => $handle])
                  @endforeach
                @elseif($section['settings']['column_grid'] == 4)
                  @foreach($handles as $handle)
                    @include($theme_path.'.partials.product-grid-4', ['products' => $products, 'slug' => $handle])
                  @endforeach
                @endif
            </div>
        </div>
    </div>      
  </section> 
</div>
@endif

@php
  $settings = [
      "name" => "Product Grid",
      "section" => $section_id, 
      "type" => "content_for_index",
      "disabled" => false,
      "settings" => [
        [
          "type" => "text",
          "id" => "title",
          "label" => "Title"
        ],
        [
          "type" => "select",
          "id" => "column_grid",
          "label" => "Column grid",
          "options" => [
            [ "value" => "3", "label" => "3" ],
            [ "value" => "4", "label" => "4" ]
          ]
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
