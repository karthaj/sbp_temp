@extends($theme_path.'.layouts.theme')

@section('meta')

<title>{{ $category->formattedMetaTitle }} &ndash; {{ $store->formattedStoreTitle }}</title>
<meta name="description" content="{{ strip_tags($category->meta_description) }}" >
<meta name="keywords" content="{{ $category->meta_keywords }}" >
<meta property="og:site_name" content="{{ $store->store_name }}">
<meta property="og:url" content="{{ getStoreUrl($store).'/categories/'.$category->slug }}">
<meta property="og:title" content="{{ $category->meta_title }}">
<meta property="og:type" content="website">
<meta property="og:description" content="{{ strip_tags($category->meta_description) }}">
@if($category->cover_image)
<meta property="og:image" content="{{ asset('stores').'/'.$store->domain.'/category/'.$category->cover_image }}">
@endif
<meta name="twitter:site" content="{{ '@'.$store->store_name }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $category->meta_title }}">
<meta name="twitter:description" content="{{ strip_tags($category->meta_description) }}">
@if($store->setting->logo)
<meta name="twitter:image" content="{{ asset('stores').'/'.$store->domain.'/category/'.$category->cover_image }}"/>
@endif

@endsection

@section('content')
    <div class="top_banner version_2">
        <div class="opacity-mask d-flex align-items-center" @if($settings['show_category_overlay'] == true) data-opacity-mask="rgba(0, 0, 0, {{ $settings['category_overlay_opacity'] / 100 }})" @endif>
            <div class="container">
                <div class="d-flex justify-content-center"><h1>{{ $category->name }}</h1></div>
            </div>
        </div>
        @if($category->cover_image)
            <img src="{{ asset('stores/'.$store->domain.'/category/'.$category->cover_image) }}" class="img-fluid" alt="{{ $category->name }}">
        @endif
    </div>
    <product-sorter></product-sorter>
    @if(auth()->guard('web')->check())
        <products :authenticated="true" :wishlists="{{ auth()->user()->wishlists()->where('store_id', session('store')->id)->pluck('product_id')->toJson() }}" endpoint="{{ url('api/categories/'.$category->slug.'/products') }}"></products>
    @else
        <products endpoint="{{ url('api/categories/'.$category->slug.'/products') }}"></products>
    @endif
@endsection