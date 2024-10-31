@if($section['settings']['style'] == 1)
	@include($theme_path.'.partials.instagram.style-1', ['section' => $section, 'section_id' => $section_id])
@elseif($section['settings']['style'] == 2)
	@include($theme_path.'.partials.instagram.style-2', ['section' => $section, 'section_id' => $section_id])
@endif