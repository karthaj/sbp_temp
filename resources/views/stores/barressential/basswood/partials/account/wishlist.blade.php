<div class="tab-pane {{ request()->tab === 'wishlist' ? 'active' : '' }}">
	@if(request()->tab === 'wishlist' && count($products['data']))
	<div class="row">
        <div class="col-12">
            <form action="#">
                <div class="table-content table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="product-remove">remove</th>
                                <th class="product-thumbnail">images</th>
                                <th class="cart-product-name">Product</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($products['data'] as $product)
                            <tr>
                                <td class="product-remove">
									<a href="#" class="remove-wishlist-item" data-product="{{ $product['id'] }}"><i class="zmdi zmdi-close"></i></a>
                                </td>
                                <td class="product-thumbnail">
                                	<a href="{{ $product['url'] }}">
                                		<img src="{{ count($product['cover_image']) ? $product['cover_image']['small'] : '//placehold.it/100x126' }}" alt="{{ $product['name'] }}">
                                	</a>
                                </td>
                                <td class="product-stock-status">
                                    <p><a href="{{ $product['url'] }}">{{ $product['name'] }}</a></p>
                                    @if($product['in_stock'])
                                        <span class="in-stock">in stock</span>
                                    @else
                                        <span class="out-stock">out stock</span>
                                    @endif
                                </td>
                                <td class="font-weight-bold"><span class="amount">{{ $store->setting->currency->iso_code }} {{ number_format($product['price_min'], 2) }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
	@else
	<div class="row">
		<div class="col">
			<div class="alert alert-info" role="alert">
		    	You have no Wish Lists.
		  	</div>
		</div>
	</div>
	@endif
</div>