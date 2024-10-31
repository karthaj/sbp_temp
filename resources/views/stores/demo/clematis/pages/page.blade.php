@extends($theme_path.'.layouts.theme')

@section('meta')

<title>{{ $page->formattedMetaTitle }} &ndash; {{ $store->formattedStoreTitle }}</title>
<meta name="description" content="{{ strip_tags($page->meta_description) }}" >
<meta name="keywords" content="{{ $page->meta_keywords }}" >
<meta property="og:site_name" content="{{ $store->store_name }}">
<meta property="og:url" content="{{ getStoreUrl($store).'/pages/'.$page->slug }}">
<meta property="og:title" content="{{ $page->meta_title ?: $page->title }}">
<meta property="og:type" content="website">
<meta property="og:description" content="{{ strip_tags($page->meta_description) }}">
@if($store->setting->logo)
<meta property="og:image" content="{{ asset('stores').'/'.$store->domain.'/img/'.$store->setting->logo }}"/>
<meta property="og:image:alt" content="{{ $store->store_name }}"/>
@endif

<meta name="twitter:site" content="{{ '@'.$store->store_name }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $page->meta_title ?: $page->title }}">
<meta name="twitter:description" content="{{ strip_tags($page->meta_description) }}">
@if($store->setting->logo)
    <meta property="twitter:image" content="{{ asset('stores').'/'.$store->domain.'/img/'.$store->setting->logo }}"/>
    <meta property="twitter:image:alt" content="{{ $store->store_name }}"/>
@endif

@endsection

@section('header_scripts')
@if($page->type === 'contact' && $page->enable_form)
    <script>
        function onloadCallback() {
            grecaptcha.render('g-recaptcha', {}, true);
            grecaptcha.execute();
        }
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"></script>
@endif
@endsection

@section('content')
    
    <div class="container margin_60">
        <div class="main_title">
            <h2>{{ $page->title }}</h2>
        </div>               
    </div>

    <div class="bg_white">
        <div class="container margin_60_35">
            @if($page->type === 'contact' && $page->enable_form)
                <div class="row">
                    <div class="col-md-12">{!! html_entity_decode($page->body) !!}</div>   
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 add_bottom_25">
                        <h4 class="pb-3">{{ $settings['contact_title'] }}</h4>
                         @include($theme_path.'.partials.contact._index')
                    </div>
                    @if($page->map)
                        <div class="col-lg-8 col-md-6 add_bottom_25">{!! $page->map !!}</div>
                    @endif
                </div>
            @else
                <div class="row">
                    <div class="col-md-12"> {!! html_entity_decode($page->body) !!}</div>   
                </div>
            @endif
        </div>
    </div>

@endsection