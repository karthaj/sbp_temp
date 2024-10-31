@if($section['settings']['style'] == 'grid')
	@include($theme_path.'.partials.feature-list.grid-view', ['section' => $section, 'section_id' => $section_id])
@elseif($section['settings']['style'] == 'list')
	@include($theme_path.'.partials.feature-list.list-view', ['section' => $section, 'section_id' => $section_id])
@endif	

@php

	$settings = [
	    "name" => "Feature List",
	    "section" => $section_id, 
	    "type" => "content_for_index",
	    "disabled" => false,
	    "settings" => [
	    	[
	          "type" => "radio",
	          "id" => "style",
	          "label" => "Style",
	          "options" => [
	            ["value" => "grid", "label" => "Grid View"],
	            ["value" => "list", "label" => "List View"]
	          ]
	        ],
	    	[
	          "type" => "image_picker",
	          "id" => "feature_img_1",
	          "label" => "Feature Image"
	        ],
	        [
	          "type" => "text",
	          "id" => "feature_title_1",
	          "label" => "Feature Title"
	        ],
	        [
	          "type" => "text",
	          "id" => "feature_subtitle_1",
	          "label" => "Feature Subtitle"
	        ],
	        [
	          "type" => "image_picker",
	          "id" => "feature_img_2",
	          "label" => "Feature Image"
	        ],
	        [
	          "type" => "text",
	          "id" => "feature_title_2",
	          "label" => "Feature Title"
	        ],
	        [
	          "type" => "text",
	          "id" => "feature_subtitle_2",
	          "label" => "Feature Subtitle"
	        ],
	        [
	          "type" => "image_picker",
	          "id" => "feature_img_3",
	          "label" => "Feature Image"
	        ],
	        [
	          "type" => "text",
	          "id" => "feature_title_3",
	          "label" => "Feature Title"
	        ],
	        [
	          "type" => "text",
	          "id" => "feature_subtitle_3",
	          "label" => "Feature Subtitle"
	        ]
	    ]
	];

	session()->push('schema', $settings);

@endphp