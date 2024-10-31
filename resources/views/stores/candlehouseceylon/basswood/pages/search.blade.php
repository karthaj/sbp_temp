@extends($theme_path.'.layouts.theme')

@section('breadcrum')
<div class="breadcrumb-area pt-20 pb-20">
     <div class="container">
         <div class="row">
             <div class="col-md-12">
                 <div class="breadcrumb-content">
                     <ul>
                         <li><a href="{{ getStoreUrl($store) }}">Home</a></li>
                         @if(request()->has('q'))
                         <li class="active">{{ request()->q }}</li>
                         @else
                         <li class="active">Search</li>
                         @endif
                     </ul>
                 </div>
             </div>
         </div>
     </div>
</div>

<div class="shop-area pt-60">
    <div class="col-12 text-center">
        <div class="section-title">
        @if(request()->has('q'))
            <h2>{{ $pagination['total'] }} {{ $pagination['total'] > 1 ? 'results' : 'result' }} for "{{ request()->q }}"</h2>
        @else
            <h2>Search</h2>
            <form action="/search" method="get" role="search">
                <div class="form-group">
                    <input type="text" name="q" placeholder="Search products..." aria-label="Search products..." class="form-control" autocomplete="off">
                </div>
            </form>
        @endif
        </div>
    </div>
    
    <div class="container">
    @if(count($data))
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 order-2 order-lg-2">
                <div class="shop-layout">
                    <div class="row">
                     @foreach($data as $product)
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="basswood-single-product">
                                <div class="product-img">
                                    <a href="{{ $product['url'] }}">
                                    @if($product['cover_image'])
                                        <img class="first-img" src="{{ $product['cover_image']['standard'] }}" alt="{{ $product['name'] }}">
                                    @else
                                        <img class="first-img" src="https://via.placeholder.com/350x441/f2f2f2/3d3d3d?text=No image" alt="{{ $product['name'] }}">
                                    @endif

                                    @if(count($product['images']))
                                        <img class="hover-img" src="{{ $product['images'][0]['standard'] }}" alt="{{ $product['name'] }}">
                                    @endif
                                    </a>
                                    @if($product['special_price'] > 0)
                                        <span  class="discount-sticker">-{{ floor(($product['price_min'] - 2000) / $product['price_min'] * 100) }}%</span>
                                    @endif
                                </div>
                                <div class="product-content">
                                    <h4><a href="{{ $product['url'] }}">{{ $product['name'] }}</a></h4>
                                    <div class="product-price">
                                    @if($product['special_price'] > 0)
                                        <span class="price money">{{ number_format($product['special_price'], 2) }}</span>
                                    @else
                                        <span class="price money">{{ number_format($product['price_min'], 2) }}</span>
                                    @endif
                                        
                                    @if($product['special_price'] > 0)
                                        <span class="regular-price money">{{ number_format($product['price_min'], 2) }}</span>
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                     
                    @if($pagination['total'] > 15)
                    <div class="shop-product">                          
                        <div class="pagination-product d-md-flex align-items-center justify-content-md-between">
                            <div class="showing-product">
                                <p> Showing {{ $pagination['current_page'] }}-{{ $pagination['total_pages'] }} of {{ $pagination['total'] }} item(s) </p>
                            </div>
                            <div class="page-list">
                                <ul>
                                    <li class="prev{{ !array_key_exists('previous',$pagination['links']) ? ' disabled' : ''}}">
                                        <a href="{{ array_key_exists('previous',$pagination['links']) ? $pagination['links']['previous'] : 'javascript:;' }}"><i class="zmdi zmdi-chevron-left"></i>Previous</a>
                                    </li>
                                    
                                    @for($i = 1; $i <= $pagination['total_pages']; $i++)
                                    <li>
                                        <a href="?page={{ $i }}&q={{ request()->q }}" @if($pagination['current_page'] === $i) class="active" @endif >{{ $i }}</a>
                                    </li>
                                    @endfor

                                    <li class="next{{ !array_key_exists('next',$pagination['links']) ? ' disabled' : ''}}">
                                        <a href="{{ array_key_exists('next',$pagination['links']) ? $pagination['links']['next'] : 'javascript:;' }}">Next<i class="zmdi zmdi-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>    
    @endif
    </div>
 </div>

 @endsection