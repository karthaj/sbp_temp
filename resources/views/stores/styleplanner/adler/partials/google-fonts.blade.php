@php
  $data = [];
@endphp
@if(str_contains($settings['h1_font'], 'Google'))
  @php 
    $parts = explode('_', $settings['h1_font']);
    $google_url = '//fonts.googleapis.com/css?family='.$parts[1].':'.$parts[2];
    array_push($data, $google_url);
  @endphp
@endif

@if(str_contains($settings['h2_font'], 'Google'))
  @php 
    $parts = explode('_', $settings['h2_font']);
    $google_url = '//fonts.googleapis.com/css?family='.$parts[1].':'.$parts[2];
    array_push($data, $google_url);
  @endphp
@endif

@if(str_contains($settings['h3_font'], 'Google'))
  @php 
    $parts = explode('_', $settings['h3_font']);
    $google_url = '//fonts.googleapis.com/css?family='.$parts[1].':'.$parts[2];
    array_push($data, $google_url);
  @endphp
@endif

@if(str_contains($settings['h4_font'], 'Google'))
  @php 
    $parts = explode('_', $settings['h4_font']);
    $google_url = '//fonts.googleapis.com/css?family='.$parts[1].':'.$parts[2];
    array_push($data, $google_url);
  @endphp
@endif

@if(str_contains($settings['h5_font'], 'Google'))
  @php 
    $parts = explode('_', $settings['h5_font']);
    $google_url = '//fonts.googleapis.com/css?family='.$parts[1].':'.$parts[2];
    array_push($data, $google_url);
  @endphp
@endif

@if(str_contains($settings['h6_font'], 'Google'))
  @php 
    $parts = explode('_', $settings['h6_font']);
    $google_url = '//fonts.googleapis.com/css?family='.$parts[1].':'.$parts[2];
    array_push($data, $google_url);
  @endphp
@endif

@if(str_contains($settings['body_font'], 'Google'))
  @php 
    $type_body_parts = explode('_', $settings['body_font']);
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
