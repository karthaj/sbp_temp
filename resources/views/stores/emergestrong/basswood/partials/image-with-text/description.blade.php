<div class="col-md-6">
    <div class="lst-category">
        <div class="lst-category-name">
            @if($section['settings']['title'])
                <h4><a href="{{ $section['settings']['title_link'] }}">{{ $section['settings']['title'] }}</a></h4>
            @endif
        </div>
        @if($section['settings']['description'])
            <div class="lst-category-decs">{!! $section['settings']['description'] !!}</div>
        @endif
        <div>
            @if($section['settings']['button_label'])
                <a href="{{ $section['settings']['button_link'] }}" class="lst-category-btn">{{ $section['settings']['button_label'] }}</a>
            @endif
        </div>
    </div>
</div>