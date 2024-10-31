@php
	$image = 'background-image: url(https://via.placeholder.com/1900x900?text=Image); background-repeat: no-repeat; padding: 8% 25px; background-position: center; background-size: cover';

	if($section['settings']['newsletter_bg']) {
		$image =  "background-image: url('".asset('stores/'.$store->domain.'/img/'.$section['settings']['newsletter_bg'])."'); background-repeat: no-repeat; padding: 8% 25px; background-position: center; background-size: cover;";
	}

@endphp

@if($section['disabled'] === false)
<div id="shopbox-section-{{ $section_id }}">
	<section id="{{ $section_id }}">
	  <div class="b-newsletter mb-5" style="{{ $image }}">
	      <div class="b-newsletter_inner">
	          <h3 class="text-center font-italic">{{ $section['settings']['newsletter_sub_heading'] }}</h3>
	          <h2 class="text-center">{{ $section['settings']['title'] }}</h2>
	          <p class="text-center">{{ $section['settings']['newsletter_text'] }}</p>
	          <div class="b-newsletter_form">
	              <form action="{{ $section['settings']['newsletter_form_action'] ? $section['settings']['newsletter_form_action'] : '#' }}" class="clearfix" autocomplete="off" method="post" target="_blank">
	                  <div class="form-group float-left"> 
	                      <label>Email address: </label>
	                      <input name="EMAIL" placeholder="Your email address" required="" type="email"> 
	                  </div>  
	                  <div class="b-form_submit float-left">    
	                      <button class="b-submit" type="submit">Sign up</button>
	                  </div>    
	              </form>
	          </div>
	      </div>
	  </div>
	</section>
</div>
@endif

@php
	$settings = [
	  	"name" => "Newsletter",
	  	"section" => $section_id, 
	  	"type" => "content_for_index",
	  	"disabled" => false,
	  	"settings" => [
		    [
		      "type" => "image_picker",
		      "id" => "newsletter_bg",
		      "label" => "Background image"
		    ],
		    [
			    "type" => "text",
			    "id" => "newsletter_sub_heading",
			    "label" => "Sub heading"
		    ],
		    [
			    "type" => "text",
			    "id" => "title",
			    "label" => "Heading"
		    ],
		    [
			    "type" => "text",
			    "id" => "newsletter_text",
			    "label" => "Paragraph"
		    ],
		    [
			    "type" => "text",
			    "id" => "newsletter_form_action",
			    "label" => "MailChimp form action URL",
			    "info" => "Find your MailChimp form action URL from MailChimp.com"
		    ]
	  	]
	];

	session()->push('schema', $settings);
@endphp