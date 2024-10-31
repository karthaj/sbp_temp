@extends($theme_path.'.layouts.theme')

@section('content')

<div class="row">
  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
    <div class="b-decent-title-wrap mt-5 mb-0">
      <div class="b-decent-title">
      	@if(request()->has('q'))
        	<span>{{ $pagination['count'] }} {{ $pagination['count'] > 1 ? 'results' : 'result' }} for "{{ request()->q }}"</span>
        @else
        	<span>Search</span>
        	<form class="my-5 mx-5" action="/search" method="get" role="search">
                <div class="form-group">
                    <input type="text" name="q" placeholder="Search products..." aria-label="Search products..." class="form-control" autocomplete="off">
                </div>
            </form>
        @endif
      </div>
    </div>
  </div>
</div>

@if(count($data))
	<div class="b-products b-product_grid b-product_grid_four mb-4">
	  	<div class="container">
	      	<div class="row clearfix">
	      	@foreach($data as $product)
	          	<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
	               	<div class="b-product_grid_single">
	                 	<div class="b-product_grid_header">
	                        <a href="{{ $product['url'] }}">
	                         	@if($product['cover_image'])
	                         		@if(count($product['images']))
	                         			<img data-src="{{ $product['cover_image']['standard'] }}, {{ $product['images'][0]['standard'] }}" src="{{ $product['cover_image']['standard'] }}" class="img-fluid img-switch d-block" alt="{{ $product['name'] }}">
	                         		@else
	                         			<img data-src="{{ $product['cover_image']['standard'] }}, {{ $product['cover_image']['standard'] }}" src="{{ $product['cover_image']['standard'] }}" class="img-fluid img-switch d-block" alt="{{ $product['name'] }}">
	                         		@endif
	                         		
	                         	@else
	                         		<img data-src="https://via.placeholder.com/263x336?text=No image, https://via.placeholder.com/263x336?text=No image" src="https://via.placeholder.com/263x336?text=No image" class="img-fluid img-switch d-block" alt="{{ $product['name'] }}">
	                         	@endif
	             
	                        </a>
	                        @if(count($product['images']))
	                         	<div class="b-hover_img">
		                         	<a href="{{ $product['url'] }}">
		                         		<img src="{{ $product['images'][0]['standard'] }}" class="img-fluid img-switch d-block" alt="{{ $product['name'] }}">
		                         	</a>
	                         	</div> 
	                        @endif
	                        <quickview-button endpoint="/api/products/{{ $product['handle'] }}" pid="{{ $product['id'] }}"></quickview-button>
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
	        	</div>
	        @endforeach
	     	</div>
	  	</div>
		
		@if($pagination['count'] > 15)
	    <div class="b-pagination pt-2 pb-4">
	        <ul class="pl-0 text-center list-unstyled mb-0">
	        	<li>
	        		<a @if(!array_key_exists('previous',$pagination['links'])) class="disabled" @endif href="{{ array_key_exists('previous',$pagination['links']) ? $pagination['links']['previous'] : 'javascript:;' }}"><i class="aapl-chevron-left icons"></i></a>
	        	</li>

	        	@for($i = 1; $i <= $pagination['total_pages']; $i++)
		            <li>
		                <a href="?page={{ $i }}&q={{ request()->q }}" @if($pagination['current_page'] === $i) class="b-current_page" @endif >{{ $i }}</a>
		            </li>
	            @endfor
	            
	            <li>
	            	<a @if(!array_key_exists('next',$pagination['links'])) class="disabled" @endif href="{{ array_key_exists('next',$pagination['links']) ? $pagination['links']['next'] : 'javascript:;' }}"><i class="aapl-chevron-right icons"></i></a>
	            </li>
	        </ul>
		</div>
		@endif
	</div>
@endif

@endsection