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

@section('breadcrum')
<div class="breadcrumb-area pt-20 pb-20">
     <div class="container">
         <div class="row">
             <div class="col-md-12">
                 <div class="breadcrumb-content">
                     <ul>
                         <li><a href="{{ getStoreUrl($store) }}">Home</a></li>
                         <li class="active"><a href="{{ route('stores.product.show', $product) }}">{{ $product->name }}</a></li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>
</div>
@endsection

@section('content')
    
    @if(auth()->guard('web')->check())
        <product endpoint="{{ route('cart.add') }}" :authenticated="true" :wishlists="{{ auth()->user()->wishlists()->where('store_id', session('store')->id)->pluck('product_id')->toJson() }}"></product>
    @else
        <product endpoint="{{ route('cart.add') }}"></product>
    @endif

    <script type="application/json" id="productJson">
        {!! json_encode($data) !!}
  	</script>

@endsection
