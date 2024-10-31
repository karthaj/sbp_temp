@extends($theme_path.'.layouts.theme')

@section('meta')

<title>{{ $brand->formattedMetaTitle }} &ndash; {{ $store->formattedStoreTitle }}</title>
<meta name="description" content="{{ $brand->meta_description }}" >
<meta name="keywords" content="{{ $brand->meta_keywords }}" >
<meta property="og:site_name" content="{{ $store->store_name }}">
<meta property="og:url" content="{{ getStoreUrl($store).'/brands/'.$brand->slug }}">
<meta property="og:title" content="{{ $brand->meta_title }}">
<meta property="og:type" content="website">
<meta property="og:description" content="{{ $brand->meta_description }}">
@if($brand->medium_default)
<meta property="og:image" content="{{ asset('stores').'/'.$store->domain.'/brand/'.$brand->medium_default }}">
@endif
<meta name="twitter:site" content="{{ '@'.$store->store_name }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $brand->meta_title }}">
<meta name="twitter:description" content="{{ $brand->meta_description }}">
@if($store->setting->logo)
<meta name="twitter:image" content="{{ asset('stores').'/'.$store->domain.'/brand/'.$brand->cover_image }}"/>
@endif

@endsection

@section('content')
    <div class="top_banner version_2">
        <div class="opacity-mask d-flex align-items-center" @if($settings['show_brand_overlay'] == true) data-opacity-mask="rgba(0, 0, 0, {{ $settings['brand_overlay_opacity'] / 100 }})" @endif>
            <div class="container">
                <div class="d-flex justify-content-center"><h1>{{ $brand->name }}</h1></div>
            </div>
        </div>
        @if($brand->large_default)
            <img src="{{ asset('stores/'.$store->domain.'/brand/'.$brand->large_default) }}" class="img-fluid" alt="{{ $brand->name }}">
        @endif
    </div>
    <product-sorter></product-sorter>
    @if(auth()->guard('web')->check())
        <products :authenticated="true" :wishlists="{{ auth()->user()->wishlists()->where('store_id', session('store')->id)->pluck('product_id')->toJson() }}" endpoint="{{ url('api/brands/'.$brand->slug.'/products') }}"></products>
    @else
        <products endpoint="{{ url('api/brands/'.$brand->slug.'/products') }}"></products>
    @endif
    
@endsection