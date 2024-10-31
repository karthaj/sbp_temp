@if($section['disabled'] === false)
<div id="shopbox-section-{{ $section_id }}">
	<ul id="banners_grid" class="clearfix">
		@foreach($section['block_order'] as $index => $block)
			<li>
				<a href="{{ $section['blocks'][$block]['settings']['button_link'] }}" class="img_container">
				@if($section['blocks'][$block]['settings']['image'])
					<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['blocks'][$block]['settings']['image']) }}" data-src="{{ asset('stores/'.$store->domain.'/img/'.$section['blocks'][$block]['settings']['image']) }}" alt="{{ $section['blocks'][$block]['settings']['title'] }}" class="lazy">
				@else
					<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/banners_cat_placeholder.jpg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/banners_cat_placeholder.jpg') }}" alt="{{ $section['blocks'][$block]['settings']['title'] }}" class="lazy">
				@endif	
					<div class="short_info opacity-mask" @if($section['blocks'][$block]['settings']['show_overlay'] == true) style="background-color:rgba(0, 0, 0, {{ $section['blocks'][$block]['settings']['overlay_opacity'] / 100 }})" @endif>
					@if($section['blocks'][$block]['settings']['title'])
						<h3 style="color: {{  $section['blocks'][$block]['settings']['text_color'] }};">{{ $section['blocks'][$block]['settings']['title'] }}</h3>
					@endif
					@if($section['blocks'][$block]['settings']['button_text'])
						<div><span class="btn_1">{{ $section['blocks'][$block]['settings']['button_text'] }}</span></div>
					@endif
					</div>
				</a>
			</li>
		@endforeach
	</ul>
</div>
@endif

@php
	$settings = [
		"name" => "Promotion Banners",
	    "section" =>  $section_id, 
	    "type" =>  "content_for_index",
	    "disabled" =>  false,
	    "blocks" => [
	      	[
		        "type" => "item",
		        "name" => "Item",
		        "settings" => [
	          		[
			            "type" => "image_picker",
			            "id" => "image",
			            "label" => "Image"
		          	],
		          	[
			          	"type" => "checkbox",
			          	"id" => "show_overlay",
			          	"label" => "Show overlay",
			          	"info" => "For text readability."
		        	],
		        	[
			    		"type" => "select",
			          	"id" => "overlay_opacity",
			          	"label" => "Overlay opacity",
			          	"options" =>  [
			            	[ "value" => "0", "label" => "0%" ],
							[ "value" => "15", "label" => "15%" ],
							[ "value" => "30", "label" => "30%" ],
							[ "value" => "50", "label" => "50%" ],
							[ "value" => "70", "label" => "70%" ],
							[ "value" => "100", "label" => "100%" ]
			          	]
			    	],
			    	[
			          	"type" => "color",
			          	"id" => "text_color",
			          	"label" => "Text color"
			        ],
			        [
			          	"type" => "text",
			          	"id" => "title",
			          	"label" => "Title"
			        ],
			        [
			            "type" => "text",
			            "id" => "button_text",
			            "label" => "Button Text"
			        ],
			        [
			            "type" => "text",
			            "id" => "button_link",
			            "label" => "Link"
			        ]
		        ]
		    ]
	    ],
	    "defaults" => [
	        "blocks" => [
	        	[
		          "type" => "item",
		          "settings" => [
	                "image" => "",
	                "show_overlay" => true,
	                "overlay_opacity" => "50%",
	                "text_color" => "#fff",
	                "title" => "Title Here",
	                "button_text" => "Shop Now",
	                "link" => ""
	              ]
		        ]
	        ]
	    ]
	];

	session()->push('schema', $settings);

@endphp