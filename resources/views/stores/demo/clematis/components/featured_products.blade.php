@php
use Shopbox\Transformers\StoreFront\ProductCollectionTransformer;

$products = $category = [];

if($section['settings']['category']) {
	$category = $store->categories->where('slug', $section['settings']['category'])->first();
}


if($category) {
  	$products  = fractal()
            ->collection($category->products()->where('state', 1)->where('blocked', 0)->where('active', 1)->with(['variations.stock.storeStocks', 'images', 'stock.storeStocks'])->limit($section['settings']['products_limit'])->get())
            ->transformWith(new ProductCollectionTransformer)
            ->toArray()['data'];
}

            
@endphp
@if($section['disabled'] === false)
<div id="shopbox-section-{{ $section_id }}">
	<div class="container margin_60_35">
		@if($section['settings']['title'] || $section['settings']['content'])
			<div class="main_title">
			@if($section['settings']['title'])
				<h2>{{ $section['settings']['title'] }}</h2>
			@endif
			@if($section['settings']['title2'])
				<span>{{ $section['settings']['title2'] }}</span>
			@endif
			@if($section['settings']['content'])	
				<p>{{ $section['settings']['content'] }}</p>
			@endif
			</div>
		@endif
		@if(count($products))
			@if($section['settings']['layout'] == 'horizontal')
			<div class="row small-gutters justify-content-center" data-section-id="{{ $section_id }}" data-section-type="{{ $type }}">
				@foreach($products as $product)
					<div class="col-6 col-md-4 col-xl-3">
						@include($theme_path.'.partials.product._grid_item', ['product' => $product])
					</div>
				@endforeach
			</div>
			@elseif($section['settings']['layout'] == 'vertical')
			<div id="ProductSlider-{{ $section_id }}" class="owl-carousel owl-theme products_carousel" data-section-id="{{ $section_id }}" data-section-type="{{ $type }}" >
				@foreach($products as $product)
					<div class="item">
						@include($theme_path.'.partials.product._grid_item', ['product' => $product])
					</div>
				@endforeach
			</div>
			@endif
		@endif
	</div>
</div>
@endif

@php
	$settings = [
		"name" => "Featured Products",
	    "section" =>  $section_id, 
	    "type" =>  "content_for_index",
	    "disabled" =>  false,
	    "settings" => [
	    	[
	            "type" => "category",
	            "id" => "category",
	            "label" => "Category"
          	],
          	[
	          	"type" => "number",
	          	"id" => "products_limit",
	          	"label" => "Products to show"
        	],
        	[
	    		"type" => "select",
	          	"id" => "layout",
	          	"label" => "Layout",
	          	"options" =>  [
	            	[ "value" => "vertical", "label" => "Vertical" ],
	            	[ "value" => "horizontal", "label" => "Horizontal" ]
	          	]
	    	],
	        [
	          	"type" => "text",
	          	"id" => "title",
	          	"label" => "Title"
	        ],
	        [
	          	"type" => "text",
	          	"id" => "title2",
	          	"label" => "Title 2"
	        ],
	        [
	          	"type" => "text",
	          	"id" => "content",
	          	"label" => "Content"
	        ]
	    ]
	];

	session()->push('schema', $settings);

@endphp