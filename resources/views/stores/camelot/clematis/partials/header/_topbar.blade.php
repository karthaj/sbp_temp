@if($section['settings']['show_top_bar'])
	<div class="top_line version_1 plus_select">
		<div class="container">
			<div class="row d-flex align-items-center">
				<div class="col-sm-6 col-5">{{ $section['settings']['top_bar_text'] }}</div>
				@if($settings['enable_multicurrency'])
					@php
	                    $currencies = array_filter(explode(',', $settings['currencies']));
                  	@endphp
					<div class="col-sm-6 col-7">
						<ul class="top_links">
							<li>
								 @include($theme_path.'.partials.common._currency_selector', ['currencies' => $currencies])
							</li>
						</ul>
					</div>
				@endif
			</div>
		</div>
	</div>
@endif