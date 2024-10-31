@if($section['disabled'] === false)
	<div id="shopbox-section-{{ $section_id }}" data-section-id="{{ $section_id }}" data-section-type="instagram" class="instagram-area mb-75">
	    <div class="container-fluid pl-60 pr-60">
	        <div class="row d-flex justify-content-center">
	            <div class="col-8">
	                <div class="instagram-container">
	                   <div class="title-instagram">
	                        <h2>#Instagram <span>{{ '@'.$section['settings']['username'] }}</span></h2>
	                    </div>
	                    <div class="instagram-slider">
	                        <ul id="Instafeed" data-token="{{ $section['settings']['access_token'] }}" data-image-limit="{{ $section['settings']['image_limit'] }}">
	                        	@if(!$section['settings']['access_token'])
	                        		@for ($i = 0; $i < $section['settings']['image_limit']; $i++)
									    <li>
		                        			<a href="http://via.placeholder.com/255x255?text=Image" target="_new">
		                        				<img src="http://via.placeholder.com/255x255?text=Image">
		                        			</a>
		                        		</li>
									@endfor
	                        	@endif
	                        </ul>
	                    </div>
	                </div>
	            </div>    
	        </div>
	    </div>
	</div>
@endif

@php

	$settings = [ 
	    "name" => "Instagram Feed Style 2",
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
	          "type" => "text",
	          "id" => "access_token",
	          "label" => "Instagram Access Token",
	          "info" => "Get your Instagram access token at https://instagram.pixelunion.net"
	        ],
	        [
	          "type" => "text",
	          "id" => "username",
	          "label" => "Username"
	        ],
	        [
	          "type" => "select",
	          "id" => "image_limit",
	          "label" => "Images",
	          "options" => [
	          	[
					"value" => "6", 
					"label" => "6"
	          	],
	          	[
					"value" => "12", 
					"label" => "12"
	          	],
	          	[
					"value" => "18", 
					"label" => "18"
	          	]
	          ]
	        ]
	    ]
	];

	session()->push('schema', $settings);

@endphp