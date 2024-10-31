@if($section['disabled'] === false)
	<div id="shopbox-section-{{ $section_id }}" class="list-category">
	    <div class="container-fluid pl-60 pr-60">
	    	@if($section['settings']['style'] == 1)
    		 	<div class="row align-items-center">
    		 		@include($theme_path.'.partials.image-with-text.image', ['section' => $section, 'section_id' => $section_id])
    		 		@include($theme_path.'.partials.image-with-text.description', ['section' => $section, 'section_id' => $section_id])
		        </div>
	    	@elseif($section['settings']['style'] == 2)
	    		<div class="row align-items-center">
		            @include($theme_path.'.partials.image-with-text.description', ['section' => $section, 'section_id' => $section_id])
		            @include($theme_path.'.partials.image-with-text.image', ['section' => $section, 'section_id' => $section_id])
		        </div>
	    	@endif
	    </div>
	</div>
@endif

@php

	$settings = [
	    "name" => "Image with text",
	    "section" => $section_id, 
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
	          "type" => "image_picker",
	          "id" => "image",
	          "label" => "Image"
	        ],
	        [
	          "type" => "text",
	          "id" => "title",
	          "label" => "Title"
	        ],
	        [
	          "type" => "text",
	          "id" => "title_link",
	          "label" => "Title link"
	        ],
	        [
	          "type" => "richtext",
	          "id" => "description",
	          "label" => "Description"
	        ],
	        [
	          "type" => "text",
	          "id" => "button_label",
	          "label" => "Button label"
	        ],
	        [
	          "type" => "text",
	          "id" => "button_link",
	          "label" => "Button link"
	        ]
	    ]
	];

	session()->push('schema', $settings);

@endphp