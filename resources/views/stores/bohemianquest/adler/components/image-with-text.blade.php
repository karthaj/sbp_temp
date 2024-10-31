@if($section['disabled'] === false)
<div id="shopbox-section-{{ $section_id }}">
	<section id="{{ $section_id }}" class="mb-5">
		<div class="b-section_title text-center">
			@if($section['settings']['title'])
				<h4 class="text-uppercase">
			      {{ $section['settings']['title'] }}
			      <span class="b-title_separator"><span></span></span>
			    </h4>
		    @endif
		</div>
		@if($section['settings']['layout_grid'] === 'half_right_text' || $section['settings']['layout_grid'] === 'half_right_image')
			@include($theme_path.'.partials.imgtxt-halfhalf', ['section' => $section])
		@endif

		@if($section['settings']['layout_grid'] === 'one_third_right_text' || $section['settings']['layout_grid'] === 'one_third_right_image')
			@include($theme_path.'.partials.imgtxt-onethird', ['section' => $section])
		@endif

		@if($section['settings']['layout_grid'] === 'two_third_right_text' || $section['settings']['layout_grid'] === 'two_third_right_image')
			@include($theme_path.'.partials.imgtxt-twothird', ['section' => $section])
		@endif
	</section>
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
		        "type" => "text",
		        "id" => "title",
		        "label" => "Title"
		    ],
		    [
		      	"type" => "select",
		      	"id" => "layout_grid",
		      	"label" => "Layout grid",
		      	"options" => [
			      	[
						"value" => "half_right_text", 
						"label" => "[1/2] IMAGE - [1/2] TEXT", 
						"group" => "Half"
			      	],
			      	[
						"value" => "half_right_image", 
						"label" => "[1/2] TEXT - [1/2] IMAGE", 
						"group" => "Half"
			      	],
			      	[
						"value" => "one_third_right_image", 
						"label" => "[1/3] TEXT - [2/3] IMAGE", 
						"group" => "One third"
			      	],
			      	[
						"value" => "one_third_right_text", 
						"label" => "[1/3] IMAGE - [2/3] TEXT", 
						"group" => "One third"
			      	],
			        [
						"value" => "two_third_right_text", 
						"label" => "[2/3] IMAGE - [1/3] TEXT", 
						"group" => "Two third"
			      	],
			      	[
						"value" => "two_third_right_image", 
						"label" => "[2/3] TEXT - [1/3] IMAGE", 
						"group" => "Two third"
			      	]
		      	]
		    ],
		    [
		        "type" => "richtext",
		        "id" => "text",
		        "label" => "Text"
		    ],
		    [
		      "type" => "image_picker",
		      "id" => "image",
		      "label" => "Image"
		    ]
	  	]
	];

	session()->push('schema', $settings);
@endphp