@if($section['disabled'] === false)
<div id="shopbox-section-{{ $section_id }}">
	<div class="feat">
		<div class="container">
			<ul>
				<li>
					<div class="box">
						@if($section['settings']['block_0_custom_icon'])
							<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['block_0_custom_icon']) }}">
						@else
							<i class="{{ $section['settings']['block_0_icon'] }}"></i>
						@endif
						
						@if($section['settings']['block_0_title'] || $section['settings']['block_0_content'])
							<div class="justify-content-center">
							@if($section['settings']['block_0_title'])
								<h3>{{ $section['settings']['block_0_title'] }}</h3>
							@endif
							@if($section['settings']['block_0_content'])
								<p>{{ $section['settings']['block_0_content'] }}</p>
							@endif
							</div>
						@endif
					</div>
				</li>
				<li>
					<div class="box">
						@if($section['settings']['block_1_custom_icon'])
							<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['block_1_custom_icon']) }}">
						@else
							<i class="{{ $section['settings']['block_1_icon'] }}"></i>
						@endif
											
						@if($section['settings']['block_1_title'] && $section['settings']['block_1_content'])
							<div class="justify-content-center">
							@if($section['settings']['block_1_title'])
								<h3>{{ $section['settings']['block_1_title'] }}</h3>
							@endif
							@if($section['settings']['block_1_content'])
								<p>{{ $section['settings']['block_1_content'] }}</p>
							@endif
							</div>
						@endif
					</div>
				</li>
				<li>
					<div class="box">
						@if($section['settings']['block_2_custom_icon'])
							<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['block_2_custom_icon']) }}">
						@else
							<i class="{{ $section['settings']['block_2_icon'] }}"></i>
						@endif
						
						@if($section['settings']['block_2_title'] && $section['settings']['block_2_content'])
							<div class="justify-content-center">
							@if($section['settings']['block_2_title'])
								<h3>{{ $section['settings']['block_2_title'] }}</h3>
							@endif
							@if($section['settings']['block_2_content'])
								<p>{{ $section['settings']['block_2_content'] }}</p>
							@endif
							</div>
						@endif
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>
@endif

@php
	$settings = [
		"name" => "Text with icons",
	    "section" =>  $section_id, 
	    "type" =>  "content_for_index",
	    "disabled" =>  false,
	    "settings" => [
	    	[
	    		"type" => "select",
	          	"id" => "block_0_icon",
	          	"label" => "Icon",
	          	"options" =>  [
	            	[ "value" => "ti-shopping-cart", "label" => "Shopping cart"],
	            	[ "value" => "ti-gift", "label" => "Gift wrap"],
	            	[ "value" => "ti-heart", "label" => "Heart"],
	            	[ "value" => "ti-location-pin", "label" => "Location pin"],
	            	[ "value" => "ti-announcement", "label" => "Announcement"],
	            	[ "value" => "ti-microphone", "label" => "Microphone"],
	            	[ "value" => "ti-comments", "label" => "Chat"],
	            	[ "value" => "ti-comment-alt", "label" => "Comment"],
	            	[ "value" => "ti-headphone-alt", "label" => "Customer support"],
	            	[ "value" => "ti-email", "label" => "Email"],
	            	[ "value" => "ti-sharethis", "label" => "Share"],
	            	[ "value" => "ti-package", "label" => "Package"],
	            	[ "value" => "ti-truck", "label" => "Delivery"],
	            	[ "value" => "ti-share-alt", "label" => "Returns"],
	            	[ "value" => "ti-timer", "label" => "Time"],
	            	[ "value" => "ti-credit-card", "label" => "Credit card"],
	            	[ "value" => "ti-shield", "label" => "Shield"],
	            	[ "value" => "ti-mobile", "label" => "Mobile"],
	            	[ "value" => "ti-desktop", "label" => "Desktop"],
	            	[ "value" => "ti-settings", "label" => "Settings"],
	            	[ "value" => "ti-reload", "label" => "Reload"],
	            	[ "value" => "ti-lock", "label" => "Secure"],
	            	[ "value" => "ti-bag", "label" => "Bag"],
	            	[ "value" => "ti-world", "label" => "World"],
	            	[ "value" => "ti-home", "label" => "Home"],
	            	[ "value" => "ti-calendar", "label" => "Calender"],
	            	[ "value" => "ti-wallet", "label" => "Wallet"],
	            	[ "value" => "ti-wallet", "label" => "Wallet"]
	          	]
	    	],
	    	[
	            "type" => "image_picker",
	            "id" => "block_0_custom_icon",
	            "label" => "Custom icon",
	            "info" => "60 x 60px .png with transparency recommended"
          	],
          	[
	          	"type" => "text",
	          	"id" => "block_0_title",
	          	"label" => "Title"
	        ],
	        [
	          	"type" => "text",
	          	"id" => "block_0_content",
	          	"label" => "Content"
	        ],
	        [
	    		"type" => "select",
	          	"id" => "block_1_icon",
	          	"label" => "Icon",
	          	"options" =>  [
	            	[ "value" => "ti-shopping-cart", "label" => "Shopping cart"],
	            	[ "value" => "ti-gift", "label" => "Gift wrap"],
	            	[ "value" => "ti-heart", "label" => "Heart"],
	            	[ "value" => "ti-location-pin", "label" => "Location pin"],
	            	[ "value" => "ti-announcement", "label" => "Announcement"],
	            	[ "value" => "ti-microphone", "label" => "Microphone"],
	            	[ "value" => "ti-comments", "label" => "Chat"],
	            	[ "value" => "ti-comment-alt", "label" => "Comment"],
	            	[ "value" => "ti-headphone-alt", "label" => "Customer support"],
	            	[ "value" => "ti-email", "label" => "Email"],
	            	[ "value" => "ti-sharethis", "label" => "Share"],
	            	[ "value" => "ti-package", "label" => "Package"],
	            	[ "value" => "ti-truck", "label" => "Delivery"],
	            	[ "value" => "ti-share-alt", "label" => "Returns"],
	            	[ "value" => "ti-timer", "label" => "Time"],
	            	[ "value" => "ti-credit-card", "label" => "Credit card"],
	            	[ "value" => "ti-shield", "label" => "Shield"],
	            	[ "value" => "ti-mobile", "label" => "Mobile"],
	            	[ "value" => "ti-desktop", "label" => "Desktop"],
	            	[ "value" => "ti-settings", "label" => "Settings"],
	            	[ "value" => "ti-reload", "label" => "Reload"],
	            	[ "value" => "ti-lock", "label" => "Secure"],
	            	[ "value" => "ti-bag", "label" => "Bag"],
	            	[ "value" => "ti-world", "label" => "World"],
	            	[ "value" => "ti-home", "label" => "Home"],
	            	[ "value" => "ti-calendar", "label" => "Calender"],
	            	[ "value" => "ti-wallet", "label" => "Wallet"],
	            	[ "value" => "ti-wallet", "label" => "Wallet"]
	          	]
	    	],
	    	[
	            "type" => "image_picker",
	            "id" => "block_1_custom_icon",
	            "label" => "Custom icon",
	            "info" => "60 x 60px .png with transparency recommended"
          	],
          	[
	          	"type" => "text",
	          	"id" => "block_1_title",
	          	"label" => "Title"
	        ],
	        [
	          	"type" => "text",
	          	"id" => "block_1_content",
	          	"label" => "Content"
	        ],
	        [
	    		"type" => "select",
	          	"id" => "block_2_icon",
	          	"label" => "Icon",
	          	"options" =>  [
	            	[ "value" => "ti-shopping-cart", "label" => "Shopping cart"],
	            	[ "value" => "ti-gift", "label" => "Gift wrap"],
	            	[ "value" => "ti-heart", "label" => "Heart"],
	            	[ "value" => "ti-location-pin", "label" => "Location pin"],
	            	[ "value" => "ti-announcement", "label" => "Announcement"],
	            	[ "value" => "ti-microphone", "label" => "Microphone"],
	            	[ "value" => "ti-comments", "label" => "Chat"],
	            	[ "value" => "ti-comment-alt", "label" => "Comment"],
	            	[ "value" => "ti-headphone-alt", "label" => "Customer support"],
	            	[ "value" => "ti-email", "label" => "Email"],
	            	[ "value" => "ti-sharethis", "label" => "Share"],
	            	[ "value" => "ti-package", "label" => "Package"],
	            	[ "value" => "ti-truck", "label" => "Delivery"],
	            	[ "value" => "ti-share-alt", "label" => "Returns"],
	            	[ "value" => "ti-timer", "label" => "Time"],
	            	[ "value" => "ti-credit-card", "label" => "Credit card"],
	            	[ "value" => "ti-shield", "label" => "Shield"],
	            	[ "value" => "ti-mobile", "label" => "Mobile"],
	            	[ "value" => "ti-desktop", "label" => "Desktop"],
	            	[ "value" => "ti-settings", "label" => "Settings"],
	            	[ "value" => "ti-reload", "label" => "Reload"],
	            	[ "value" => "ti-lock", "label" => "Secure"],
	            	[ "value" => "ti-bag", "label" => "Bag"],
	            	[ "value" => "ti-world", "label" => "World"],
	            	[ "value" => "ti-home", "label" => "Home"],
	            	[ "value" => "ti-calendar", "label" => "Calender"],
	            	[ "value" => "ti-wallet", "label" => "Wallet"],
	            	[ "value" => "ti-wallet", "label" => "Wallet"]
	          	]
	    	],
	    	[
	            "type" => "image_picker",
	            "id" => "block_2_custom_icon",
	            "label" => "Custom icon",
	            "info" => "60 x 60px .png with transparency recommended"
          	],
          	[
	          	"type" => "text",
	          	"id" => "block_2_title",
	          	"label" => "Title"
	        ],
	        [
	          	"type" => "text",
	          	"id" => "block_2_content",
	          	"label" => "Content"
	        ]
	    ]
	];

	session()->push('schema', $settings);

@endphp