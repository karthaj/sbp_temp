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
	<div id="shopbox-section-{{ $section_id }}">
		<section data-section-id="{{ $section_id }}">
			<div class="b-section_line_title">
	          	<div class="container">
		            <h2><span>{{ $section['settings']['title'] }}</span></h2>
	          	</div>
	        </div>
	        <div class="container mt-4 pb-3 mb-5">
	          	<ul class="list-unstyled pl-0 b-partners_02">
	          	@if(count($brands))
                    @foreach($handles as $handle)
                    	@php
							$brand = array_where($brands, function ($brand) use ($handle) {
							                return $brand['handle'] === $handle;
							            });

							if(count($brand)) {
							  $brand = head($brand);
							}
						@endphp
						@if(count($brand))
							<li class="d-inline-block">
								<a href="{{ $brand['url'] }}">
									@if($brand['image']['medium'])
										<img src="{{ $brand['image']['medium'] }}" alt="{{ $brand['name'] }}">
									@else
										<img src="//via.placeholder.com/360x160?text={{ $brand['name'] }}" alt="{{ $brand['name'] }}">
									@endif
								</a>
							</li>
						@else
							<li class="d-inline-block"><img src="//via.placeholder.com/360x160?text=Image" alt="Brand Name"></li>
						@endif
                    @endforeach
                @else
                    @foreach($section['block_order'] as $block)
                        <li class="d-inline-block"> <img src="//via.placeholder.com/360x160?text=Image" alt="Brand Name"></li>
                    @endforeach
                @endif
	          	</ul>
	        </div>
		</section>
	</div>
@endif

@php
	$settings = [
	  "name" =>  "Brand List",
	  "section" =>  $section_id, 
	  "type" => "content_for_index",
	  "disabled" => false,
	  "settings" => [
	    [
		    "type" => "text",
		    "id" => "title",
		    "label" => "Title"
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