@if($section['disabled'] === false)
<div id="Slideshow-{{ $section_id }}" data-section-id="{{ $section_id }}" data-section-type="slideshow" data-speed="{{ $section['settings']['slide_show_speed'] }}" class="slider-area mb-60">
    <div class="hero-slider owl-carousel">
        @foreach($section['block_order'] as $block)
            @php
                $image = 'https://via.placeholder.com/1800x894/f2f2f2/dcdfde?text=Image Slide';

                if($section['blocks'][$block]['settings']['image']) {
                    $image = asset('stores/'.$store->domain.'/img/'.$section['blocks'][$block]['settings']['image']);
                }
            @endphp

            <div class="single-slider-2" style="{{ 'background-image: url('.$image.')' }}">   
                <div class="slider-progress"></div>
                <div class="container">
                    <div class="hero-slider-content-2">
                        <h4 style="color: {{ $section['settings']['text_color'] }}">{{ $section['blocks'][$block]['settings']['text_1'] }}</h4>
                        <h1 style="color: {{ $section['settings']['text_color'] }}">{{ $section['blocks'][$block]['settings']['title'] }}</h1>
                        <h3 style="color: {{ $section['settings']['text_color'] }}">{{ $section['blocks'][$block]['settings']['text_3'] }}</h3>
                        <p style="color: {{ $section['settings']['text_color'] }}">{{ $section['blocks'][$block]['settings']['text_2'] }}</p>
                        <div class="slider-btn-2">
                        @if($section['blocks'][$block]['settings']['button_label'])
                            <a class="shop-btn-2" href="{{ $section['blocks'][$block]['settings']['button_link'] }}" style="background: {{  $section['settings']['button_bg_color'] }}; color: {{  $section['settings']['text_color'] }};">{{ $section['blocks'][$block]['settings']['button_label'] }}</a>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif