@extends($theme_path.'.layouts.theme')

@section('meta')

<title>Blogs &ndash; {{ $store->formattedStoreTitle }}</title>
<meta name="description" content="{{ $store->setting->meta_description }}">
<meta name="keywords" content="{{ $store->setting->meta_keywords }}">
<meta property="og:type" content="website" />
<meta property="og:site_name" content="{{ $store->store_name }}"/>
<meta property="og:title" content="Blogs"/>
<meta property="og:description" content="{{ $store->setting->meta_description ?: $store->store_name.' store powered by ShopBox.' }}"/>
@if($store->setting->logo)
<meta property="og:image" content="{{ asset('stores').'/'.$store->domain.'/img/'.$store->setting->logo }}"/>
<meta property="og:image:alt" content="{{ $store->store_name }}"/>
@endif
<meta property="og:url" content="{{ getStoreUrl($store).'/blogs' }}"/>
<meta property="twitter:card" content="summary_large_image"/>
<meta property="twitter:site" content="{{ '@'.$store->domain }}"/>
<meta property="twitter:title" content="Blogs"/>
<meta property="twitter:description" content="{{ $store->setting->meta_description ?: $store->store_name.' store powered by ShopBox.' }}"/>
@if($store->setting->logo)
<meta property="twitter:image" content="{{ asset('stores').'/'.$store->domain.'/img/'.$store->setting->logo }}"/>
<meta property="twitter:image:alt" content="{{ $store->store_name }}"/>
@endif

@endsection