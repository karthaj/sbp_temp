@if($section['disabled'] === false)
	<div id="shopbox-section-{{ $section_id }}" data-section-id="{{ $section_id }}" data-section-type="booking-ipms247" class="pt-90 pb-85" style="background-color: {{ $section['settings']['bg_color'] }}">
	    <div class="container">
	    	<form class="col-12" action="{{ $section['settings']['post_url'] }}" method="post" autocomplete="off" target="_blank">
				<input name="eZ_room" id="eZ_room" type="hidden" value="1">
				<input name="hidBodyLanguage" id="hidBodyLanguage" type="hidden" value="en">
				<input name="calformat" id="calformat" type="hidden" class="txtbox" value="dd-mm-yy">

	            <div class="d-flex row justify-content-center">
	            
	                <div class="form-group col-12 col-lg-2">
	                    <label for="checkIn" style="color: {{ $section['settings']['txt_color'] }}">Check-In</label>
	                    <input type="text" id="eZ_chkin" name="eZ_chkin">
	                </div>
																																																																																																																																																																																																																																																																																																																																																																																																																				
	                <div class="form-group col-4 col-lg-1">
	                	<label for="eZ_Nights" style="color: {{ $section['settings']['txt_color'] }}">Nights</label>
	                	<input type="number" id="eZ_Nights" name="eZ_Nights" min="1" class="form-control" value="1">
	                </div>		

	                <div class="form-group col-4 col-lg-1">
	                    <label for="adult" style="color: {{ $section['settings']['txt_color'] }}">Adult</label>
	                    <input type="number" id="eZ_adult" class="form-control" min="1" name="eZ_adult" value="1">
	                </div>

	                <div class="form-group col-4 col-lg-1">
	                    <label for="adult" style="color: {{ $section['settings']['txt_color'] }}">Child</label>
	                    <input type="number" id="eZ_child" class="form-control" min="0" name="eZ_child" value="0">
	                </div>

	                <div class="form-group col-12 col-lg-3 align-self-end">
	                    <input type="submit" class="add-to-cart btn-block btn-booking" value="CHECK AVAILABILITY">
	                </div>
	    
	            </div>
	        </form>
	    </div>
	</div>
@endif

@php

	$settings = [
	  "name" => "Booking IPMS247",
	  "section" => $section_id, 
	  "type" => "content_for_index",
	  "disabled" => false,
	  "settings" => [
	  	[
	        "type" => "text",
	        "id" => "post_url",
	        "label" => "Post URL"
	    ],
	    [
	        "type" => "color",
	        "id" => "bg_color",
	        "label" =>"Background Color"
	    ],
	    [
	        "type" => "color",
	        "id" => "txt_color",
	        "label" => "Text Color"
	    ]
	  ]
	];

	session()->push('schema', $settings);

@endphp