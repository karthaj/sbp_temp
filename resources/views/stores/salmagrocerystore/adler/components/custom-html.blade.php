
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
		@if($section['settings']['code'])
			<div class="{{ $section['settings']['enable_full_width'] === false ? 'container' : '' }}">{!! $section['settings']['code'] !!}</div>
		@endif
	</section>
</div>
@endif

@php
	$settings = [
	  "name" => "Custom HTML",
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
	        "type" => "code",
	        "mode" => "text/html",
	        "id" => "code",
	        "label" => "HTML"
	    ],
	    [
	      	"type" => "checkbox",
	      	"id" => "enable_full_width",
	      	"label" => "Enable full width"
	    ]
	  ]
	];

	session()->push('schema', $settings);
@endphp