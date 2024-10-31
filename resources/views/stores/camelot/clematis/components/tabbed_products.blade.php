@php
	use Shopbox\Transformers\StoreFront\ProductCollectionTransformer;
@endphp


@if($section['disabled'] === false)
	@php
	    $handles = [];
	    $categories = [];

	    foreach ($section['block_order'] as $block) {
	      array_push($handles, $section['blocks'][$block]['settings']['category']);
	    }

	    if(count($handles)) {
	    	$categories = $store->categories()
	    						->with(['products' => function ($query) use ($section) {
								    $query->where('state', 1)->where('blocked', 0)->where('active', 1)->limit($section['settings']['product_limit']);
								}])
	    						->whereIn('slug', array_filter($handles))
	    						->where('is_root_category', 0)
	    						->where('status', 1)
	    						->orderByRaw('FIELD(slug, '.rtrim(str_repeat('?,',count(3)), ',').')', $handles)
	    						->get(['id', 'name', 'slug']);
	    }
  @endphp
	<div id="shopbox-section-{{ $section_id }}">
		<div class="container margin_60_35">
			@if($section['settings']['title'] || $section['settings']['content'])
				<div class="main_title mb-4">
				@if($section['settings']['title'])
					<h2>{{ $section['settings']['title'] }}</h2>
				@endif
				@if($section['settings']['title2'])
		          <span>{{ $section['settings']['title2'] }}</span>
		        @endif
				@if($section['settings']['sub_title'])	
					<p>{{ $section['settings']['sub_title'] }}</p>
				@endif
				</div>
			@endif
			@if($categories->count())
			<div class="isotope_filter">
				<ul>
					@foreach($categories as $category)
					<li><a href="#0" id="{{ $category->slug }}" data-filter=".{{ $category->slug }}">{{ title_case($category->name) }}</a></li>
					@endforeach
				</ul>
			</div>
			@endif
			@if($categories->count())
			<div id="ProductFilter-{{ $section_id }}" data-section-id="{{ $section_id }}" data-section-type="{{ $type }}" data-init-filter=".{{ $categories->first()->slug }}">
				<div class="row small-gutters">
            		@foreach($categories as $category)
            			@php
            				$products  = fractal()
							            ->collection($category->products)
							            ->transformWith(new ProductCollectionTransformer)
							            ->toArray()['data'];
            			@endphp
						
						@if(count($products))
	            			@foreach($products as $product)
								<div class="col-6 col-md-4 col-xl-3 isotope-item {{ $category->slug }}">
									@include($theme_path.'.partials.product._grid_item', ['product' => $product])
								</div>
							@endforeach
						@endif
					@endforeach
				</div>
			</div>
			@endif
		</div>
	</div>
@endif

@section('scripts')
<script src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/js/isotope.min.js') }}"></script>
@endsection

@php
	$settings = [
		"name" => "Tabbed Products",
	    "section" =>  $section_id, 
	    "type" => "content_for_index",
	    "disabled" =>  false,
	    "settings" => [
	    	[
	          "type" => "text",
	          "id" => "title",
	          "label" => "Heading"
	        ],
	        [
	          "type" => "text",
	          "id" => "title2",
	          "label" => "Title 2"
	        ],
	    	[
	    		"type" => "text",
		        "id" => "sub_title",
		        "label" => "Subheading"
	    	]
	    ],
	    "blocks" => [
	      	[
		        "type" => "category",
		        "name" => "Category",
		        "settings" => [
          		 	[
				    	"type" => "category",
					    "id" => "category",
					    "label" => "Category"
				    ]
		        ]
		    ]
	    ],
	    "defaults" => [
	        "blocks" => [
	        	[
		          "type" => "category",
		          "settings" => [
	                "category" => ""
	              ]
		        ]
	        ]
	    ]
	];

	session()->push('schema', $settings);

@endphp