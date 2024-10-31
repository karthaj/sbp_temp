@if($section['disabled'] === false)
<div id="shopbox-section-{{ $section_id }}">
	<div class="container margin_90_0">
		@if($section['settings']['title'])
			<div class="main_title">
			@if($section['settings']['title'])
				<h2>{{ $section['settings']['title'] }}</h2>
			@endif
			</div>
		@endif
		<div class="row justify-content-between align-items-center content_general_row">
			@if($section['settings']['image_position'] == 'left')
				@include($theme_path.'.partials.image_with_text._image')
				@include($theme_path.'.partials.image_with_text._content')
			@elseif($section['settings']['image_position'] == 'right')
				@include($theme_path.'.partials.image_with_text._content')
				@include($theme_path.'.partials.image_with_text._image')
			@endif
		</div>
	</div>
</div>
@endif

@php
	$settings = [
		"name" => "Image with text",
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
	    		"type" => "select",
	          	"id" => "image_position",
	          	"label" => "Image position",
	          	"options" =>  [
	            	[ "value" => "left", "label" => "Left" ],
	            	[ "value" => "right", "label" => "Right" ]
	          	]
	    	],
	        [
	          	"type" => "text",
	          	"id" => "title",
	          	"label" => "Title"
	        ],
	        [
	          	"type" => "richtext",
	          	"id" => "content",
	          	"label" => "Content"
	        ]
	    ]
	];

	session()->push('schema', $settings);

@endphp