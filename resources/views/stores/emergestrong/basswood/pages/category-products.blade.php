@extends($theme_path.'.layouts.theme')

@section('breadcrum')
<div class="breadcrumb-area pt-20 pb-20">
     <div class="container">
         <div class="row">
             <div class="col-md-12">
                 <div class="breadcrumb-content">
                     <ul>
                         <li><a href="{{ getStoreUrl($store) }}">Home</a></li>
                         <li class="active"><a href="{{ route('stores.categories.index') }}">Categories</a></li>
                         <li class="active"><a href="{{ route('stores.categories.category', $category) }}">{{ $category->name }}</a></li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>
</div>
@endsection

@section('content')
    
    @if(auth()->guard('web')->check())
        <category-products :authenticated="true" :wishlists="{{ auth()->user()->wishlists()->where('store_id', session('store')->id)->pluck('product_id')->toJson() }}"></category-products>
    @else
        <category-products></category-products>
    @endif
   
    
    <script type="application/json" id="categoryJson">
        {!! $data !!}
    </script>
    
@endsection