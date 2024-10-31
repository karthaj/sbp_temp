@php
use Shopbox\Transformers\StoreFront\MenuCollectionTransformer;

$menu1 = $menu2 = $submenu = '';

if($menus->where('slug', $section['settings']['menu_1'])->count()) {
  $menu1 = fractal()
        ->item($menus->where('slug', $section['settings']['menu_1'])->first())
        ->transformWith(new MenuCollectionTransformer)
        ->toArray()['data'];
}

if($menus->where('slug', $section['settings']['menu_2'])->count()) {
  $menu2 = fractal()
        ->item($menus->where('slug', $section['settings']['menu_2'])->first())
        ->transformWith(new MenuCollectionTransformer)
        ->toArray()['data'];
}

if($menus->where('slug', $section['settings']['submenu'])->count()) {
  $submenu = fractal()
        ->item($menus->where('slug', $section['settings']['submenu'])->first())
        ->transformWith(new MenuCollectionTransformer)
        ->toArray()['data'];
}

@endphp

<footer id="shopbox-section-footer" class="revealed">
	<div class="container">
		<div class="row">
			@if($section['settings']['menu_title_1'] || $menu1)
				<div class="col-lg-3 col-md-6">
					@if($section['settings']['menu_title_1'])
						<h3 data-target="#collapse_1">{{ $section['settings']['menu_title_1'] }}</h3>
					@endif
					@if($menu1)
						<div class="collapse dont-collapse-sm links" id="collapse_1">
							<ul>
							 	@foreach($menu1['links'] as $link)
									<li><a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }}</a></li>
								@endforeach
							</ul>
						</div>
					@endif
				</div>
			@endif
			@if($section['settings']['menu_title_1'] || $menu2)
				<div class="col-lg-3 col-md-6">
					@if($section['settings']['menu_title_2'])
						<h3 data-target="#collapse_2">{{ $section['settings']['menu_title_2'] }}</h3>
					@endif
					@if($menu2)
						<div class="collapse dont-collapse-sm links" id="collapse_2">
							<ul>
								@foreach($menu2['links'] as $link)
									<li><a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }}</a></li>
								@endforeach
							</ul>
						</div>
					@endif
				</div>
			@endif
			@if($section['settings']['text_title'] || $section['settings']['text_address'] || $section['settings']['text_phone'] || $section['settings']['text_email'])
				<div class="col-lg-3 col-md-6">
					@if($section['settings']['text_title'])
						<h3 data-target="#collapse_3">{{ $section['settings']['text_title'] }}</h3>
					@endif
					<div class="collapse dont-collapse-sm contacts" id="collapse_3">
						<ul>
							@if($section['settings']['text_address'])
								<li><i class="ti-home"></i>{{ $section['settings']['text_address'] }}</li>
							@endif
							@if($section['settings']['text_phone'])
								<li><i class="ti-headphone-alt"></i>{{ $section['settings']['text_phone'] }}</li>
							@endif
							@if($section['settings']['text_email'])
								<li><i class="ti-email"></i><a href="#0">{{ $section['settings']['text_email'] }}</a></li>
							@endif
						</ul>
					</div>
				</div>
			@endif
			@if($section['settings']['newsletter_title'] || $section['settings']['social_title'])
				<div class="col-lg-3 col-md-6">
					@if($section['settings']['newsletter_title'])
						<h3 data-target="#collapse_4">{{ $section['settings']['newsletter_title'] }}</h3>
					@endif
					<div class="collapse dont-collapse-sm" id="collapse_4">
						@if($section['settings']['show_newsletter'])
						<form action="{{ $section['settings']['newsletter_form_action'] ? $section['settings']['newsletter_form_action'] : '#' }}" class="clearfix" autocomplete="off" method="post" target="_blank">
							<div id="newsletter">
							    <div class="form-group">
							        <input type="email" name="EMAIL" id="email_newsletter" class="form-control" placeholder="Your email">
							        <button type="submit" id="submit-newsletter"><i class="ti-angle-double-right"></i></button>
							    </div>
							</div>
						</form>
						@endif
						<div class="follow_us">
							<h5>{{ $section['settings']['social_title'] }}</h5>
							@if($section['settings']['show_social_icons'] && ($settings['social_twitter_link'] || $settings['social_facebook_link'] || $settings['social_instagram_link'] || $settings['social_youtube_link']))
								<ul>
									@if($settings['social_twitter_link'])
										<li>
											<a href="{{ $settings['social_twitter_link'] }}">
											<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/twitter_icon.svg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/twitter_icon.svg') }}" alt="Twitter" class="lazy"></a>
										</li>
									@endif
									@if($settings['social_facebook_link'])
										<li>
											<a href="{{ $settings['social_facebook_link'] }}">
											<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/facebook_icon.svg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/facebook_icon.svg') }}" alt="Facebook" class="lazy"></a>
										</li>
									@endif
									@if($settings['social_instagram_link'])
										<li>
											<a href="{{ $settings['social_instagram_link'] }}">
											<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/instagram_icon.svg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/instagram_icon.svg') }}" alt="Instagram" class="lazy"></a>
										</li>
									@endif
									@if($settings['social_youtube_link'])
										<li>
											<a href="{{ $settings['social_youtube_link'] }}">
											<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/youtube_icon.svg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/youtube_icon.svg') }}" alt="Youtube" class="lazy"></a>
										</li>
									@endif
								</ul>
							@endif
						</div>
					</div>
				</div>
			@endif
		</div>
		<hr>
		<div class="row add_bottom_25">
			<div class="col-lg-6">
				<ul class="footer-selector clearfix">
				@if($settings['enable_multicurrency'])
					@php
	                    $currencies = array_filter(explode(',', $settings['currencies']));
                  	@endphp
					<li>
						@include($theme_path.'.partials.common._currency_selector', ['currencies' => $currencies])
					</li>
				@endif
				@if($section['settings']['show_payment_icons'] && count($payments))
					@foreach($payments as $payment)
						<li>
							<img src="{{ $payment }}" data-src="{{ $payment }}" alt="Accepted payments" height="25" class="lazy">
						</li>
					@endforeach
				@endif
				</ul>
			</div>
			<div class="col-lg-6">
				<ul class="additional_links">
					@if($submenu)
						@foreach($submenu['links'] as $link)
							<li><a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }}</a></li>
						@endforeach
					@endif
					<li><span>© {{ date("Y") }} {{ title_case($store->store_name) }}. Powered by <a href="https://shopbox.lk">ShopBox</a></span></li>
				</ul>
			</div>
		</div>
	</div>
</footer>


@php
  $settings = [
      "name" => "Footer",
      "section" => "footer", 
      "type" => "footer",
      "disabled" =>  false,
      "settings" => [
        [
	        "type" => "text",
	        "id" => "menu_title_1",
	        "label" => "Heading"
      	],
      	[
	       "type" => "link_list",
	       "id" => "menu_1",
	       "label" => "Menu",
	       "info" => "This menu won't show dropdown items."
      	],
      	[
	        "type" => "text",
	        "id" => "menu_title_2",
	        "label" => "Heading"
      	],
      	[
	       "type" => "link_list",
	       "id" => "menu_2",
	       "label" => "Menu",
	       "info" => "This menu won't show dropdown items."
      	],
      	[
	        "type" => "text",
	        "id" => "text_title",
	        "label" => "Heading"
      	],
      	[
	        "type" => "text",
	        "id" => "text_address",
	        "label" => "Address"
      	],
      	[
	        "type" => "text",
	        "id" => "text_phone",
	        "label" => "Phone"
      	],
      	[
	        "type" => "text",
	        "id" => "text_email",
	        "label" => "Email"
      	],
      	[
	        "type" => "checkbox",
	        "id" => "show_newsletter",
	        "label" => "Show newsletter"
      	],
      	[
	        "type" => "text",
	        "id" => "newsletter_title",
	        "label" => "Heading"
      	],
      	[
		    "type" => "text",
		    "id" => "newsletter_form_action",
		    "label" => "MailChimp form action URL",
		    "info" => "Find your MailChimp form action URL from MailChimp.com"
	    ],
  		[
	        "type" => "checkbox",
	        "id" => "show_social_icons",
	        "label" => "Show social icons",
	        "info" => "URL can be added under Theme settings > Social media"
      	],
      	[
	        "type" => "text",
	        "id" => "social_title",
	        "label" => "Heading"
      	],
      	[
	        "type" => "checkbox",
	        "id" => "show_payment_icons",
	        "label" => "Show payment icons"
      	],
      	[
	       "type" => "link_list",
	       "id" => "submenu",
	       "label" => "Menu",
	       "info" => "This menu won't show dropdown items."
      	],
  	]
  ];

  session()->push('schema', $settings);
@endphp