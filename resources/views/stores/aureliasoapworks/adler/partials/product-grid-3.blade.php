@php

$hoverImage = 'https://via.placeholder.com/263x336?text=Demo, https://via.placeholder.com/263x336?text=Demo';

$product = array_where($products, function ($product) use ($handle) {
                return $product['handle'] === $handle;
            });

if(count($product)) {
  $product = head($product);
}

if(count($product)) {
  if(array_has($product['images'], 1)) {
    $hoverImage = $product['images'][0]['medium'] . ', ' . $product['images'][1]['medium'];
  } else if(array_has($product['images'], 0)) {
    $hoverImage = $product['images'][0]['medium'] . ', ' . $product['images'][0]['medium'];
  } else if(array_has($product['cover_image'], 'medium')) {
    $hoverImage = $product['cover_image']['medium'] . ', ' . $product['cover_image']['medium'];
  }
}

@endphp
<div class="col-xl-4 col-lg-4 col-mb-4 col-sm-4 col-xs-12">
  @if(count($product))
   <div class="b-product_grid_single">
     <div class="b-product_grid_header">
         <a href="{{ $product['url'] }}">
           <img data-src="{{ $hoverImage }}" src="{{ array_has($product['cover_image'], 'medium') ? $product['cover_image']['medium'] : 'https://via.placeholder.com/263x336?text=Image Coming Soon' }}" class="img-fluid img-switch d-block" alt="{{ $product['name'] }}">
         </a> 
        <div class="b-product_grid_action">
          <a href="javascript:void(0);" data-toggle="modal" data-target="#b-qucik_view" data-src="/api/products/{{ $product['handle'] }}">
            <i data-toggle="tooltip" data-placement="left" title="" class="aapl-zoom-in icons" data-original-title="Quick View"></i>
          </a>
        </div>
        @if(!$product['backorder'] && !$product['preorder'] && !$product['in_stock'])
        <div class="b-product_labels b-labels_rounded b-black">
          <span class="b-product_label">sold out</span>
        </div>
        @endif
     </div>
     <div class="b-product_grid_info">
        <h3 class="product-title">
            <a href="{{ $product['url'] }}">{{ $product['name'] }}</a>
        </h3>
        <div class="clearfix">
          <div class="b-product_grid_toggle float-left">
              @if($product['special_price'] > 0)
                <span class="b-price">
                  <del><span class="money">{{ $product['special_price'] }}</span></del> 
                  <span class="b-accent_color money">{{ $product['special_price'] }}</span>
                </span>
              @else
                <span class="b-price money">{{ $product['price_min'] }}</span>
              @endif
              <quick-action endpoint="/api/products/{{ $product['handle'] }}" :product="{{ json_encode(array_only($product, ['id', 'url', 'type', 'preorder', 'backorder', 'in_stock'])) }}"></quick-action>
          </div>
        </div>
     </div>
   </div>
   @else
   <div class="b-product_grid_single">
      <div class="b-product_grid_header">
        <a href="javascript:void(0);">
           <img data-src="{{ $hoverImage }}" src="https://via.placeholder.com/263x336?text=Product Image" class="img-fluid img-switch d-block" alt="Product Name Here">
        </a> 
        <div class="b-product_grid_action">
          <a href="javascript:void(0);">
            <i data-toggle="tooltip" data-placement="left" title="" class="aapl-zoom-in icons" data-original-title="Quick View"></i>
          </a>
        </div>
      </div>
      <div class="b-product_grid_info">
        <h3 class="product-title">
            <a href="javascript:void(0);">Product Name Here</a>
        </h3>
        <div class="clearfix">
          <div class="b-product_grid_toggle float-left">
              <span class="b-price money">99</span>
              <span class="b-add_cart">
                <i class="aapl-cart"></i>
                <a href="javascript:void(0);">Add to cart</a>
              </span>
          </div>
        </div>
      </div>
    </div>
   @endif
</div>
