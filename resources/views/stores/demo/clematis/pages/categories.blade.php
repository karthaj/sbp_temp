@extends($theme_path.'.layouts.theme')

@section('meta')

<title>Categories &ndash; {{ $store->formattedStoreTitle }}</title>
<meta name="description" content="{{ $store->setting->meta_description }}">
<meta name="keywords" content="{{ $store->setting->meta_keywords }}">
<meta property="og:site_name" content="{{ $store->store_name }}">
<meta property="og:url" content="{{ getStoreUrl($store).'/categories' }}">
<meta property="og:title" content="Categories">
<meta property="og:type" content="website">
<meta property="og:description" content="{{ $store->setting->meta_description ?: $store->store_name.' store powered by ShopBox.' }}"/>
@if($store->setting->logo)
<meta property="og:image" content="{{ asset('stores').'/'.$store->domain.'/img/'.$store->setting->logo }}"/>
<meta property="og:image:alt" content="{{ $store->store_name }}"/>
@endif
<meta name="twitter:site" content="{{ '@'.$store->store_name }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Categories">
<meta property="twitter:description" content="{{ $store->setting->meta_description ?: $store->store_name.' store powered by ShopBox.' }}"/>
@if($store->setting->logo)
<meta property="twitter:image" content="{{ asset('stores').'/'.$store->domain.'/img/'.$store->setting->logo }}"/>
<meta property="twitter:image:alt" content="{{ $store->store_name }}"/>
@endif
    
@endsection

@section('content')
    <div class="container mt-4">
        <div class="breadcrumbs">
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/categories">Categories</a></li>
            </ul>
        </div>
        <div class="d-flex justify-content-center">
            <h1>Categories</h1>
        </div>
    </div>
@endsection