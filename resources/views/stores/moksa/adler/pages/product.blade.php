@extends($theme_path.'.layouts.theme')

@section('meta')

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
<meta name="twitter:creator" content="{{ '@'.$store->store_name }}" />

@endsection

@section('content')
	
	@include($theme_path.'.components.product-detail', ['section_id' => 'product_detail', 'section' => $settings['sections']['product_detail'],  'product' => $data])

    <script type="application/json" id="productJson">
    	{!! json_encode($data) !!}
  	</script>

@endsection

@section('scripts')
<script src="{{ asset('stores/'.$store->domain.'/themes/'.$store->template->theme->slug.'/assets/owl.carousel2.thumbs.js') }}" async="async"></script>
@endsection