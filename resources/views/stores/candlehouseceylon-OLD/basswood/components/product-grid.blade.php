<div id="shopbox-section-{{ $section_id }}">
    @if($section['settings']['style'] == 1)
        @include($theme_path.'.partials.product-grid.grid', ['section' => $section, 'section_id' => $section_id])
    @elseif($section['settings']['style'] == 2)
        @include($theme_path.'.partials.product-grid.carousel', ['section' => $section, 'section_id' => $section_id])
    @endif
</div>