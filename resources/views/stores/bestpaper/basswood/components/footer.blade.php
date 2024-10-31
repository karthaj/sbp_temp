<div id="shopbox-section-footer">
    @if($section['settings']['style'] == 1)
        @include($theme_path.'.partials.footer.style-1')
    @else
        @include($theme_path.'.partials.footer.style-2')
    @endif
</div>

@php

    $settings = [
        "name" => "Footer",
        "section" => "footer", 
        "type" => "footer",
        "disabled" => false,
        "settings" => [
            [
              "type" => "radio",
              "id" => "style",
              "label" => "Style",
              "options" => [
                ["value" => "1", "label" => "Style 1"],
                ["value" => "2", "label" => "Style 2"]
              ]
            ],
            [
              "type" => "image_picker",
              "id" => "logo",
              "label" => "Logo"
            ],
            [
              "type" => "link_list",
              "id" => "menu",
              "label" => "Menu"
            ]
        ]
    ];

    session()->push('schema', $settings);

@endphp