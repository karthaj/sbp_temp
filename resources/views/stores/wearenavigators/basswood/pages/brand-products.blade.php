@extends($theme_path.'.layouts.theme')

@section('breadcrum')
<div class="breadcrumb-area pt-20 pb-20">
     <div class="container">
         <div class="row">
             <div class="col-md-12">
                 <div class="breadcrumb-content">
                     <ul>
                         <li><a href="{{ getStoreUrl($store) }}">Home</a></li>
                         <li><a href="{{ route('stores.brands.index') }}">Brands</a></li>
                         <li class="active"><a href="{{ route('stores.brands.brand', $brand) }}">{{ $brand->name }}</a></li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>
</div>
@endsection

@section('content')
    @if(auth()->guard('web')->check())
        <brand-products :authenticated="true" :wishlists="{{ auth()->user()->wishlists()->where('store_id', session('store')->id)->pluck('product_id')->toJson() }}"></brand-products>
    @else
        <brand-products></brand-products>
    @endif

   	<script type="application/json" id="brandJson">
    	{!! $data !!}
   	</script>

@endsection