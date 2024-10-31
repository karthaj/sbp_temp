<div class="row">
  <div class="col-md-6 product_img">
      <div class="owl-carousel owl-theme" id="b-product_pop_slider">
        @if($product['cover_image'])
        <div>
          <img src="{{ $product['cover_image']['medium'] }}" alt="{{ $product['cover_image']['alt_text'] }}" class="img-fluid d-block m-auto">
        </div>
        @else
        <div><img src="{{ asset('assets/img/ProductDefault.gif') }}" alt="image coming soon" class="img-fluid d-block m-auto"></div>
        @endif
        
        @if(count($product['images']))
          @foreach($product['images'] as $image)
            <div>
              <img src="{{ $image['medium'] }}" alt="{{ $image['alt_text'] }}" class="img-fluid d-block m-auto">
            </div>
          @endforeach
        @endif
      </div>
  </div>
  <div class="col-md-6 product_content pr-5 pt-4">
     <div class="b-product_single_summary">
        <h1>{{ $product['name'] }}</h1>
        <p class="b-price">
          <span class="b-amount">{{ $product['price_min'] }}</span>
        </p>
        <div class="b-produt_description">{!! $product['short_description'] !!}</div>
        @if($product['type'] === 'variant')
        <div class="b-product_attr">
          <product-variation :option-selectors="{{ $product['options'] }}" :variants="{{ $product['variants'] }}" :product-handle="{{ $product['handle'] }}"/>
        </div>
        @endif
        <add-to-cart/>
        <div class="b-product_single_option">
          <ul class="pl-0 list-unstyled"> 
            <li><b class="text-uppercase">Sku</b>: {{ $product['sku'] }}</li>
          </ul>
        </div>
     </div>
  </div>
</div>
<script type="application/json" id="productJson">{!! json_encode($product) !!}</script>
<script>
  Vue.component(
  'add-to-cart',
  () => import({{ request()->themePath }}'/partials/add-to-cart')
)
</script>