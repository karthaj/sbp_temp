<div id="shopbox-section-header_style_1">
	@if($settings['header_style'] == 1)
		@include($theme_path.'.partials.header.header-style-1', ['section' => $sections['header_style_1']])
	@elseif($settings['header_style'] == 2)
		@include($theme_path.'.partials.header.header-style-2', ['section' => $sections['header_style_2']])
	@elseif($settings['header_style'] == 3)
		@include($theme_path.'.partials.header.header-style-3', ['section' => $sections['header_style_3']])
	@endif
</div>

<search-store></search-store>