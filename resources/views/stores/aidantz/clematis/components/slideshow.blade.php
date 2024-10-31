@if($section['disabled'] === false)
<div id="shopbox-section-{{ $section_id }}">
	<div id="Slideshow-{{ $section_id }}" class="carousel-home" data-section-id="{{ $section_id }}" data-section-type="{{ $type }}"  data-speed="{{ $section['settings']['slide_show_speed'] }}" data-autoplay="{{ $section['settings']['autoplay'] }}" data-style="{{ $section['settings']['style'] }}">
			<div class="owl-carousel owl-theme">
			@foreach($section['block_order'] as $index => $block)
				<div class="owl-slide cover" style="background-image: url({{ $section['blocks'][$block]['settings']['image'] ? asset('stores/'.$store->domain.'/img/'.$section['blocks'][$block]['settings']['image']) : '//via.placeholder.com/1450x750?text=Image%20Slide'  }});">
					<div class="opacity-mask d-flex align-items-center" @if($section['blocks'][$block]['settings']['show_overlay'] == true) style="background-color: rgba(0, 0, 0, {{ $section['blocks'][$block]['settings']['overlay_opacity'] / 100 }})" @endif>
					@if($section['blocks'][$block]['settings']['title'] || $section['blocks'][$block]['settings']['content'] || ($section['blocks'][$block]['settings']['link'] && $section['blocks'][$block]['settings']['button_text']))
						<div class="container">
							<div class="row justify-content-center @if($section['blocks'][$block]['settings']['content_position'] == 'left' || $section['blocks'][$block]['settings']['content_position'] == 'center') justify-content-md-start @elseif($section['blocks'][$block]['settings']['content_position'] == 'right') justify-content-md-end @endif">
								<div class="{{ $section['blocks'][$block]['settings']['content_position'] == 'center' ? 'col-lg-12' : 'col-lg-6' }} static">
									<div class="slide-text text-center @if($section['blocks'][$block]['settings']['content_position'] == 'right') text-md-right @elseif($section['blocks'][$block]['settings']['content_position'] == 'left') text-md-left @endif">
										@if($section['blocks'][$block]['settings']['title'])
											<h2 class="owl-slide-animated owl-slide-title" style="color: {{  $section['blocks'][$block]['settings']['text_color'] }};">{{ $section['blocks'][$block]['settings']['title'] }}</h2>
										@endif
										@if($section['blocks'][$block]['settings']['content'])
											<p class="owl-slide-animated owl-slide-subtitle" style="color: {{  $section['blocks'][$block]['settings']['text_color'] }};">
												{{ $section['blocks'][$block]['settings']['content'] }}
											</p>
										@endif
										@if($section['blocks'][$block]['settings']['link'] && $section['blocks'][$block]['settings']['button_text'])
											<div class="owl-slide-animated owl-slide-cta">
												<a class="btn_1" href="{{ $section['blocks'][$block]['settings']['link'] }}" role="button">
													{{ $section['blocks'][$block]['settings']['button_text'] }}
												</a>
											</div>
										@endif
									</div>
								</div>
							</div>
						</div>
					@endif
					</div>
				</div>
			@endforeach
			</div>
			<div id="icon_drag_mobile"></div>
		</div>
		<!--/carousel-->
</div>

@endif

@php
	$settings = [
		"name" => "Slideshow",
	    "section" =>  $section_id, 
	    "type" =>  "content_for_index",
	    "content" =>  "slideshow",
	    "disabled" =>  false,
	    "settings" => [
	    	[
	    		"type" => "select",
	          	"id" => "style",
	          	"label" => "Style",
	          	"options" =>  [
	            	[ "value" => "default", "label" => "Default" ],
	            	[ "value" => "vertical", "label" => "Vertical" ]
	          	]
	    	],
	    	[
	          "type" => "checkbox",
	          "id" => "autoplay",
	          "label" => "Auto rotate between slides"
	        ],
	    	[
	    		"type" => "number",
		        "id" => "slide_show_speed",
		        "label" => "Slider speed",
		        "info" => "1000 = 1 second"
	    	]
	    ],
	    "blocks" => [
	      	[
		        "type" => "image",
		        "name" => "Image slide",
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
		    ]
	    ],
	    "defaults" => [
	        "blocks" => [
	        	[
		          "type" => "image",
		          "settings" => [
	                "image" => "",
	                "show_overlay" => true,
	                "overlay_opacity" => "50%",
	                "text_color" => "#fff",
	                "title" => "Image Slide",
	                "content" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit",
	                "content_position" => "right",
	                "link" => "",
	                "button_text" => "Button"
	              ]
		        ]
	        ]
	    ]
	];

	session()->push('schema', $settings);

@endphp