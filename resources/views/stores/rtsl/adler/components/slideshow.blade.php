@if($section['disabled'] === false)
<div id="shopbox-section-{{ $section_id }}">
	<div id="Slideshow-{{ $section_id }}" data-section-id="{{ $section_id }}" data-section-type="{{ $type }}" class="flexslider" data-animation="{{ $section['settings']['slider_animation'] }}" data-speed="{{ $section['settings']['slide_show_speed'] }}">
	  <ul class="slides">
	  	@foreach($section['block_order'] as $index => $block)
	    <li data-block="{{ $block }}" data-flex-index="{{ $index }}">
	    	@if($section['blocks'][$block]['settings']['image'])
	    		<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['blocks'][$block]['settings']['image']) }}" alt="slider image">
	    	@else
	    		<img src="https://via.placeholder.com/1900x900?text=Image Slide" alt="Image Slide">
	    	@endif
	    </li>
	    @endforeach
	  </ul>
	</div>
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
	    		"type" => "number",
		        "id" => "slide_show_speed",
		        "label" => "Slider speed",
		        "info" => "1000 = 1 second"
	    	],
	    	[
	    		"type" => "select",
	          	"id" => "slider_animation",
	          	"label" => "Slider animation",
	          	"options" =>  [
	            	[ "value" => "fade", "label" => "Fade" ],
		            [ "value" => "slide", "label" => "Slide" ]
	          	]
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
		            "type" => "url",
		            "id" => "link",
		            "label" => "Slide link"
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
	                "link" => ""
	              ]
		        ]
	        ]
	    ]
	];

	session()->push('schema', $settings);

@endphp