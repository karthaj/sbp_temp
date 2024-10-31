@php

	$hoverImage = 'https://via.placeholder.com/350x441/f2f2f2/3d3d3d?text=Product Image';

	$product = array_where($products, function ($product) use ($handle) {
	                return $product['handle'] === $handle;
	            });

	if(count($product)) {
	  $product = head($product);
	}

	if(count($product)) {
	  	if(count($product['images'])) {
		    $hoverImage = $product['images'][0]['standard'];
	  	} else if(count($product['cover_image'])) {
		    $hoverImage = $product['cover_image']['standard'];
	  	}
	}

@endphp

@if(count($product))
	<div class="basswood-single-product">
	    <div class="product-img">
	        <a href="{{ $product['url'] }}">
	        	@if($product['cover_image'])
	        		<img class="first-img" src="{{ $product['cover_image']['standard'] }}" alt="{{ $product['name'] }}">
	        	@else
	        		<img class="first-img" src="https://via.placeholder.com/350x441/f2f2f2/3d3d3d?text=Product Image" alt="{{ $product['name'] }}">
	        	@endif
	            
	            <img class="hover-img" src="{{ $hoverImage }}" alt="{{ $product['name'] }}">
	        </a>
	        @if($product['preorder'])
	        	<span class="na-sticker">pre order</span>
	        @elseif(!$product['in_stock'] && !$product['backorder'])
	        	<span class="na-sticker">{{ $product['outofstock_label'] }}</span>
	        @elseif($product['special_price'] > 0)
	        	<span class="discount-sticker">-{{ floor(($product['price_min'] - $product['special_price']) / $product['price_min'] * 100) }}%</span>
	        @endif
	        
	        @if(auth()->guard('web')->check())
	        	 <product-action :product="{{ json_encode(array_only($product, ['id', 'handle', 'type', 'preorder', 'backorder', 'in_stock'])) }}" :authenticated="true" :wishlists="{{ auth()->user()->wishlists()->where('store_id', session('store')->id)->pluck('product_id')->toJson() }}"></product-action>
	        @else
	        	 <product-action :product="{{ json_encode(array_only($product, ['id', 'handle', 'type', 'preorder', 'backorder', 'in_stock'])) }}"></product-action>
	        @endif
	    </div>
	    <div class="product-content">
	        <h4><a href="{{ $product['url'] }}">{{ $product['name'] }}</a></h4>
	        <div class="product-price">
	        	@if($product['special_price'] > 0)
	        		<span class="price money">{{ $product['special_price'] }}</span>
	        	@else
	        		<span class="price money">{{ $product['price_min'] }}</span>
	        	@endif
	            @if($product['special_price'] > 0)
	            	<span class="regular-price">{{ $product['price_min'] }}</span>
	            @endif
	        </div>
	    </div>
	</div>
@else
	<div class="basswood-single-product">
	    <div class="product-img">
	        <a href="javascript:;">
	        	<img class="first-img" src="https://via.placeholder.com/350x441/f2f2f2/3d3d3d?text=Product Image" alt="Product Image">
	            <img class="hover-img" src="{{ $hoverImage }}" alt="Product Image">
	        </a>
	        <div class="product-action">
			    <ul>
			        <li>
			        	<a href="javascript:;" class="action-btn cart cart-item" title="Add To Cart">
			        		<i class="zmdi zmdi-shopping-cart-plus"></i>
			        	</a>
			        </li>
			        <li><a href="javascript:;" title="Quick view"><i class="zmdi zmdi-search"></i></a></li>
			        <li>
			        	<a href="javascript:;" title="Wishlist">
			        		<i class="zmdi zmdi-favorite-outline"></i>
			        	</a>
			        </li>
			    </ul>
			</div>
	    </div>
	    <div class="product-content">
	        <h4><a href="javascript:;">Product Name Here</a></h4>
	        <div class="product-price">
	        	<span class="price money">99</span>
	        </div>
	    </div>
	</div>
@endif