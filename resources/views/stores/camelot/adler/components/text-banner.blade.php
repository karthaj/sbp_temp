@if($section['disabled'] === false && ($section['settings']['banner_title'] || $section['settings']['banner_subtitle']))
<div id="shopbox-section-{{ $section_id }}">
	<section data-section-id="{{ $section_id }}">
		<div class="b-text_banner text-center" style="background-color: {{  $section['settings']['banner_bg_color'] }}">
		@if($section['settings']['banner_title'])
		  <h3 class="text-uppercase" style="color: {{  $section['settings']['banner_txt_color'] }}">{{ $section['settings']['banner_title'] }}</h3>
		@endif
		@if($section['settings']['banner_subtitle'])
		  <span class="text-uppercase" style="color: {{  $section['settings']['banner_txt_color'] }}">{{ $section['settings']['banner_subtitle'] }}</span>
		@endif
		</div>
	</section>
</div>
@endif

@php
	$settings = [
	  "name" =>  "Text Banner",
	  "section" =>  $section_id, 
	  "type" => "content_for_index",
	  "disabled" => false,
	  "settings" => [
	    [
		    "type" => "text",
		    "id" => "banner_title",
		    "label" => "Title"
	    ],
	    [
		    "type" => "text",
		    "id" => "banner_subtitle",
		    "label" => "Sub title"
	    ],
	    [
		    "type" => "color",
		    "id" => "banner_bg_color",
		    "label" => "Background color"
	    ],
	    [
		    "type" => "color",
		    "id" => "banner_txt_color",
		    "label" => "Text color"
	    ]
	  ]
	];

	session()->push('schema', $settings);

@endphp