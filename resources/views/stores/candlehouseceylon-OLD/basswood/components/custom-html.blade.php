@if($section['disabled'] === false && ($section['settings']['title'] || $section['settings']['code']))
	<div id="shopbox-section-{{ $section_id }}" class="pt-90 pb-85">
	    <div class="container">
	    	<div class="row">
	    		@if($section['settings']['title'])
	    			<div class="col-12">
			        	<div class="section-title mb-50 text-center">
		                    <h2>{{ $section['settings']['title'] }}</h2>
		                </div>
			        </div>
	    		@endif
	    		@if($section['settings']['code'])
	    			<div class="col-12">{!! $section['settings']['code'] !!}</div>
	    		@endif
	    	</div>
	    </div>
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
	        "type" => "html",
	        "id" => "code",
	        "label" => "HTML"
	    ]
	  ]
	];

	session()->push('schema', $settings);

@endphp