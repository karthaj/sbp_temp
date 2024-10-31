@php
use Carbon\Carbon;
@endphp

<div class="grid_item">
	<figure>
        @if(!$product['in_stock'] && !$product['backorder'])
        	<span class="ribbon sold">{{ $product['outofstock_label'] ?: 'Sold out' }}</span>
        @elseif($product['special_price'] > 0)
        	<span class="ribbon off">-{{ floor(($product['price_min'] - $product['special_price']) / $product['price_min'] * 100) }}%</span>
        @elseif(Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'))->lessThanOrEqualTo(Carbon::createFromFormat('Y-m-d', $product['created'])->addDays($settings['new_tag_days'])))
        	<span class="ribbon new">New</span>
        @endif
		<a href="{{ $product['url'] }}">
			@if($product['cover_image'])
				<img class="img-fluid lazy" src="{{ $product['cover_image']['medium'] }}" data-src="{{ $product['cover_image']['medium'] }}" alt="{{ $product['cover_image']['alt_text'] }}">
			@else
				<img class="img-fluid lazy" src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/product_placeholder_square_medium.jpg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/product_placeholder_square_medium.jpg') }}" alt="{{ $product['name'] }}">
			@endif

			@if(count($product['images']))
				<img class="img-fluid lazy" src="{{ $product['images'][0]['medium'] }}" data-src="{{ $product['images'][0]['medium'] }}" alt="{{ $product['images'][0]['alt_text'] }}">
			@elseif($product['cover_image'])
				<img class="img-fluid lazy" src="{{ $product['cover_image']['medium'] }}" data-src="{{ $product['cover_image']['medium'] }}" alt="{{ $product['cover_image']['alt_text'] }}">
			@else
				<img class="img-fluid lazy" src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/product_placeholder_square_medium.jpg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/product_placeholder_square_medium.jpg') }}" alt="{{ $product['name'] }}">
			@endif
		</a>
		@if($product['special_price'] > 0 && $product['special_active_from'] && $product['special_active_to'] && !Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'))->greaterThan(Carbon::parse($product['special_active_to'])))
			<div data-countdown="{{ Carbon::parse($product['special_active_to'])->format('Y/m/d H:i:s') }}" class="countdown"></div>
		@endif
	</figure>
	<a href="{{ $product['name'] }}">
		<h3>{{ $product['name'] }}</h3>
	</a>
	<div class="price_box">
		@if($product['special_price'] > 0)
			<span class="new_price money">{{ $product['special_price'] }}</span>
			<span class="old_price money">{{ $product['price_min'] }}</span>
		@else
			<span class="new_price money">{{ $product['price_min'] }}</span>
		@endif
	</div>
	@if(auth()->guard('web')->check())
    	 <product-action :product="{{ json_encode(array_only($product, ['id', 'handle', 'type', 'preorder', 'backorder', 'in_stock'])) }}" :authenticated="true" :wishlists="{{ auth()->user()->wishlists()->where('store_id', session('store')->id)->pluck('product_id')->toJson() }}"></product-action>
    @else
    	 <product-action :product="{{ json_encode(array_only($product, ['id', 'handle', 'type', 'preorder', 'backorder', 'in_stock'])) }}"></product-action>
    @endif
</div>