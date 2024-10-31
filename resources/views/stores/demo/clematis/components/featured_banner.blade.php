@if($section['disabled'] === false)
<div id="shopbox-section-{{ $section_id }}">
	<div class="featured lazy" data-bg="url({{ $section['settings']['image'] ? asset('stores/'.$store->domain.'/img/'.$section['settings']['image']) : asset('stores/'.$store->domain.'/themes/clematis/assets/img/featured_home.jpg') }})">
		<div class="opacity-mask d-flex align-items-center"  @if($section['settings']['show_overlay'] == true) style="background-color: rgba(0, 0, 0, {{ $section['settings']['overlay_opacity'] / 100 }})"  @endif>
			@if($section['settings']['title'] || $section['settings']['content'] || $section['settings']['button_text'])
				<div class="container margin_60">
					<div class="row @if($section['settings']['content_position'] == 'left') justify-content-start @elseif($section['settings']['content_position'] == 'right') justify-content-end @elseif($section['settings']['content_position'] == 'center')  justify-content-center @endif">
						<div class="col-lg-6 wow text-center @if($section['settings']['content_position'] == 'right') text-md-right @elseif($section['settings']['content_position'] == 'left') text-md-left @endif" data-wow-offset="150">
							@if($section['settings']['title'])
								<h3 style="color: {{ $section['settings']['text_color'] }}">{{ $section['settings']['title'] }}</h3>
							@endif
							@if($section['settings']['content'])
								<p style="color: {{ $section['settings']['text_color'] }}">{{ $section['settings']['content'] }}</p>
							@endif
							
							@if($section['settings']['button_text'])
								<div class="feat_text_block">
									<a class="btn_1" href="{{ $section['settings']['link'] }}" role="button">{{ $section['settings']['button_text'] }}</a>
								</div>
							@endif
						</div>
					</div>
				</div>
			@endif
		</div>
	</div>
</div>
@endif

@php
	$settings = [
		"name" => "Featured Banner",
	    "section" =>  $section_id, 
	    "type" =>  "content_for_index",
	    "disabled" =>  false,
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
	          	"id" => "content",
	          	"label" => "Content"
	        ],
	        [
	    		"type" => "select",
	          	"id" => "content_position",
	          	"label" => "Content position",
	          	"options" =>  [
	            	[ "value" => "left", "label" => "Left" ],
	            	[ "value" => "right", "label" => "Right" ],
	            	[ "value" => "center", "label" => "Center" ]
	          	],
	          	"info" => "On mobile, content is always centered."
	    	],
	        [
	            "type" => "text",
	            "id" => "link",
	            "label" => "Link"
	        ],
	        [
	            "type" => "text",
	            "id" => "button_text",
	            "label" => "Button Text"
	        ]
	    ]
	];

	session()->push('schema', $settings);

@endphp