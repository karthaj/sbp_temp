<div id="shopbox-section-{{ $section_id }}">
  @if($section['settings']['style'] == 1)
    @include($theme_path.'.partials.slideshow.slider-style-1')
  @elseif($section['settings']['style'] == 2)
    @include($theme_path.'.partials.slideshow.slider-style-2')
  @elseif($section['settings']['style'] == 3)
    @include($theme_path.'.partials.slideshow.slider-style-3')
  @elseif($section['settings']['style'] == 4)
    @include($theme_path.'.partials.slideshow.slider-style-4')
  @endif
</div>
	
	
@php

  $settings = [
      "name" => "Slideshow",
      "section" => $section_id, 
      "type" => "content_for_index",
      "disabled" => false,
      "settings" => [
        [
          "type" => "radio",
          "id" => "style",
          "label" => "Slideshow Style",
          "options" => [
            ["value" => "1", "label" => "Slideshow Style 1"],
            ["value" => "2", "label" => "Slideshow Style 2"],
            ["value" => "3", "label" => "Slideshow Style 3"],
            ["value" => "4", "label" => "Slideshow Style 4"]
          ]
        ],
        [
          "type" => "number",
          "id" => "slide_show_speed",
          "label" => "Slider speed",
          "info" => "1000 = 1 second"
        ],
        [
          "type" => "color",
          "id" => "text_color",
          "label" => "Text color"
        ],
        [
          "type" => "color",
          "id" => "button_bg_color",
          "label" => "Button background color"
        ]
      ],
      "blocks" => [
        [
          "type" => "image",
          "name" => "Image slide",
          "settings" => [
            [
              "type" => "image_picker",
              "id" => "image",
              "label" => "Image"
            ],
            [
              "type" => "text",
              "id" => "title",
              "label" => "Title"
            ],
            [
              "type" => "text",
              "id" => "text_1",
              "label" => "Text 1"
            ],
            [
              "type" => "text",
              "id" => "text_2",
              "label" => "Text 2"
            ],
            [
              "type" => "text",
              "id" => "text_3",
              "label" => "Text 3"
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
        ]
      ],
      "defaults" => [
        "blocks" => [
          [
            "type" => "image",
            "settings" => [
                "image" => "",
                "title" => "Image Slide",
                "text_1" => "Lorem ipsum",
                "text_2" => "Lorem ipsum",
                "text_3" => "Lorem ipsum",
                "button_label" => "Button",
                "button_link" => ""
            ]
          ]
        ]
      ]
  ];

  session()->push('schema', $settings);

@endphp