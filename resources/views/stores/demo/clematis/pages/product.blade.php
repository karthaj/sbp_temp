@extends($theme_path.'.layouts.theme')

@section('meta')

<title>{{ $product->formattedMetaTitle }} &ndash; {{ $store->formattedStoreTitle }}</title>
<meta name="description" content="{{ strip_tags($product->meta_description) }}" >
<meta name="keywords" content="{{ $product->meta_keywords }}" >
<meta property="og:site_name" content="{{ $store->store_name }}">
<meta property="og:url" content="{{ getStoreUrl($store).'/products/'.$product->slug }}">
<meta property="og:title" content="{{ $product->meta_title }}">
<meta property="og:type" content="product">
<meta property="og:description" content="{{ strip_tags($product->meta_description) }}">
<meta property="og:image" content="{{ asset('stores').'/'.$store->domain.'/product/'.$product->image() }}">
<meta name="twitter:site" content="{{ '@'.$store->store_name }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $product->meta_title }}">
<meta name="twitter:description" content="{{ strip_tags($product->meta_description) }}">
<meta name="twitter:image" content="{{ asset('stores').'/'.$store->domain.'/product/'.$product->image() }}"/>

@endsection


@section('content')
    @if(auth()->guard('web')->check())
        <product-detail :authenticated="true" :wishlists="{{ auth()->user()->wishlists()->where('store_id', session('store')->id)->pluck('product_id')->toJson() }}"></product-detail>
    @else
        <product-detail></product-detail>
    @endif

    @if($product->description)
        @include($theme_path.'.partials.product._tabs')
    @endif

    @if(auth()->guard('web')->check())
        <related-products :authenticated="true" :wishlists="{{ auth()->user()->wishlists()->where('store_id', session('store')->id)->pluck('product_id')->toJson() }}"></related-products>
    @else
        <related-products></related-products>
    @endif
    
    @php
        if($data['data']['short_description']) {
            $data['data']['short_description'] = strip_tags($data['data']['short_description']);
        }
    @endphp
    
    <script type="application/json" id="productJson">
        {!! json_encode($data) !!}
  	</script>

@endsection

@if($settings['product_layout_style'] == 'sticky')
    @section('scripts')
    <script src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/js/sticky_sidebar.min.js') }}"></script>
    <script>
        $('#sidebar_fixed').theiaStickySidebar({
            minWidth: 991,
            updateSidebarHeight: false,
            additionalMarginTop: 90
        });
    </script>
    @endsection
@endif