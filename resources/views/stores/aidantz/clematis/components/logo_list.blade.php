@if($section['disabled'] === false)
<div id="shopbox-section-{{ $section_id }}">
	<div style="background-color: {{ $section['settings']['bg_color'] }}">
		<div class="container margin_30">
			@if($section['settings']['title'] || $section['settings']['sub_title'])
				<div class="main_title">
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
			<div id="BrandSlider-{{ $section_id }}" class="owl-carousel owl-theme" data-section-id="{{ $section_id }}" data-section-type="{{ $type }}">
				@if(count($section['block_order']))
                    @foreach($section['block_order'] as $block)
                    <div class="item">
						@if($section['blocks'][$block]['settings']['image'])
							<a href="{{ $section['blocks'][$block]['settings']['link'] ?: '#0' }}">
								<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['blocks'][$block]['settings']['image']) }}" data-src=" {{ asset('stores/'.$store->domain.'/img/'.$section['blocks'][$block]['settings']['image']) }}" alt class="owl-lazy" style="opacity: 1;">
							</a>
						@else
							<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/brand_placeholder.png') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/brand_placeholder.png') }}" alt class="owl-lazy" style="opacity: 1;">
						@endif
					</div>
                    @endforeach
                @else
                    @for($i = 0; $i < 6; $i++)
                        <div class="item">
							<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/brand_placeholder.png') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/brand_placeholder.png') }}" alt class="owl-lazy" style="opacity: 1;">
						</div>
                    @endfor
                @endif
			</div>
		</div>
	</div>
</div>
@endif

@php

    $settings = [
        "name" => "Logo list",
        "section" => $section_id, 
        "type" => "content_for_index",
        "disabled" => false,
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
            ],
            [
              "type" => "color",
              "id" => "bg_color",
              "label" => "Background color"
            ]
        ],
        "blocks" => [
            [
                "type" => "image",
                "name" => "Image",
                "settings" => [
                  [
                    "type" => "image_picker",
                    "id" => "image",
                    "label" => "Image"
                  ]
                ]
            ],
         	[
	            "type" => "text",
	            "id" => "link",
	            "label" => "Link"
	        ]
        ],
        "defaults" => [
            "blocks" => [
                [
                    "type" => "image",
                    "settings" => [
                        "image" => "",
                        "link" => ""
                    ]
                ]
            ]
        ]
    ];

    session()->push('schema', $settings);

@endphp