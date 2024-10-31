@php
  $data = [];
@endphp

@if($settings['body_font'])
  @php 
    $type_body_parts = explode('_', $settings['body_font']);
    $google_url = '//fonts.googleapis.com/css?family='.$type_body_parts[1].':'.$type_body_parts[2];
    array_push($data, $google_url);
  @endphp
@endif

@if($settings['promo_title_font'])
  @php 
    $type_body_parts = explode('_', $settings['promo_title_font']);
    $google_url = '//fonts.googleapis.com/css?family='.$type_body_parts[1].':'.$type_body_parts[2];
    array_push($data, $google_url);
  @endphp
@endif

@if($settings['promo_subtitle_font'])
  @php 
    $type_body_parts = explode('_', $settings['promo_subtitle_font']);
    $google_url = '//fonts.googleapis.com/css?family='.$type_body_parts[1].':'.$type_body_parts[2];
    array_push($data, $google_url);
  @endphp
@endif

@php
  $data = array_unique($data);
@endphp

@foreach($data as $font)
  <link href="{{ $font }}" rel="stylesheet"> 
@endforeach
