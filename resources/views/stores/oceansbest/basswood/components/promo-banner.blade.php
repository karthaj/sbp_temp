@if($section['disabled'] === false)
  @php
    $image = 'https://via.placeholder.com/1920x600/3d3d3d/5d5d5d?text=Image';

    if($section['settings']['image']) {
        $image = asset('stores/'.$store->domain.'/img/'.$section['settings']['image']);
    }
  @endphp

  <div id="shopbox-section-{{ $section_id }}"  class="call-to-action-area mb-85" style="{{ 'background-image: url('.$image.')' }}">
      <div class="container">
          <div class="contact-static text-center">
            <div class="action-logo mb-20">
              @if($section['settings']['logo'])
                <img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['logo']) }}" alt="logo">
              @else
                <img src="https://via.placeholder.com/211x175/f2f2f2/5d5d5d?text=Logo" alt="logo">
              @endif
            </div>
            <h2>{{ $section['settings']['title'] }}</h2>
            <h3>{{ $section['settings']['subtite'] }}</h3>
            @if($section['settings']['button_link'])
               <a class="call-action-btn" href="{{ $section['settings']['button_link'] }}"  style="color: {{  $section['settings']['button_color'] }}; border-color: {{  $section['settings']['button_color'] }};">{{ $section['settings']['button_label'] }}</a>
            @endif
          </div>  
      </div>
  </div>
@endif

@php

  $settings = [
    "name" => "Promo Banner",
    "section" => $section_id, 
    "type" => "content_for_index",
    "disabled" => false,
    "settings" => [
      [
        "type" => "image_picker",
        "id" => "image",
        "label" => "Promo Image"
      ],
      [
        "type" => "image_picker",
        "id" => "logo",
        "label" => "Logo"
      ],
      [
        "type" => "text",
        "id" => "title",
        "label" => "Promo Title"
      ],
      [
        "type" => "text",
        "id" => "subtite",
        "label" => "Promo Subtitle"
      ],
      [
        "type" => "color",
        "id" => "button_color",
        "label" => "Button color"
      ],
      [
        "type" => "text",
        "id" => "button_label",
        "label" => "Button label"
      ],
      [
        "type" => "text",
        "id" => "button_link",
        "label" => "Button link"
      ]
    ]
  ];

  session()->push('schema', $settings);

@endphp