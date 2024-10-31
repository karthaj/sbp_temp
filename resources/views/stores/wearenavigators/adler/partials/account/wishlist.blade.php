<div class="tab-pane {{ request()->tab === 'wishlist' ? 'active' : '' }}">
	@if(request()->tab === 'wishlist' && count($products['data']))
	<div class="row">
        <div class="col-12">
            <div class="b-cart b-cart_default b-wishlist_table pb5">
                <div class="table-responsive">
                    <table id="cart" class="table table-condensed">
                        <thead>
                          <tr>
                            <th style="width:35%" class="border-0"><h6><b>PRODUCT NAME</b></h6></th>
                            <th style="width:25%" class="text-center border-0"><h6><b>UNIT PRICE</b></h6></th>
                            <th style="width:25%" class="text-center border-0"><h6><b>STOCK STATUS</b></h6></th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach($products['data'] as $product)
                           <tr>
                             <td>
                               <div class="b-wish_product mx-auto">
                                 <i class="aapl-cross-circle remove-wishlist-item" data-product="{{ $product['id'] }}"></i>
                                 <img src="{{ count($product['cover_image']) ? $product['cover_image']['small'] : '//placehold.it/55x70' }}" class="ml-5 mx-2 img-fluid" width="70" alt="{{ $product['name'] }}">
                                 <a href="{{ $product['url'] }}">{{ $product['name'] }}</a>
                               </div>
                             </td>
                             <td class="text-center"><span class="b-price">{{ $store->setting->currency->iso_code }} {{ number_format($product['price_min'], 2) }}</span></td>
                             <td class="text-center">
                                @if($product['in_stock'])
                                    <span class="b-stock_status b-in_stock">IN STOCK</span>
                                @else
                                    <span class="b-stock_status b-out_off_stock">OUT OFF STOCK</span>
                                @endif
                                
                            </td>
                           </tr>
                        @endforeach
                        </tbody> 
                    </table>
                </div>
            </div>
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