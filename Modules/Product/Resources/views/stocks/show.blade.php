@extends('layouts.zpanel')

@section('styles')

@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('product.stocks') }}">product stocks</a></li>
  <li class="breadcrumb-item">stock</li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header ">
     <h1 class="section-title">Stocks</h1>
  </div>
  <div class="m-0 card-block">
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default">
                      <div class="card-block">
                          <div class="row">
                            <div class="col-md-8">
                              <div class="card card-transparent">
                                <div class="card-block">
                                  <h5><b>{{ $stock->product->name }}</b></h5>
                                  @if($stock->productAttribute)
                                    @foreach($stock->productAttribute->combinations as $combination)
                                      <p>
                                        <span><strong>{{ title_case($combination->option->attribute->name) }}:</strong> {{ $combination->option->name }}</span>
                                        @if (!$loop->last) , @endif
                                      </p>
                                    @endforeach
                                  @endif
                                  
                                  <div class="row">
                                    <div class="col-md-6">
                                      @if($stock->productAttribute && $stock->productAttribute->image)
                                        <img src="{{ asset('stores').'/'.session('store')->domain.'/product/'.$stock->productAttribute->image->medium_default }}" alt="{{ $stock->product->name }}" class="img-fluid">
                                      @elseif($stock->product->image())
                                        <img src="{{ asset('stores').'/'.session('store')->domain.'/product/'.$stock->product->image() }}" alt="{{ $stock->product->name }}" class="img-fluid">
                                      @else 
                                        <img src="{{ asset('assets/img/ProductDefault.gif') }}" alt="Product Image" class="img-fluid">
                                      @endif
                                    </div>
                                    <div class="col-md-6">
                                      <div class="text-justify">{!! html_entity_decode($stock->product->description) !!}</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="card card-default bg-complete">
                                <div class="card-block">
                                  <h5><strong>Total stock : {{ $total_stock }}</strong></h5>
                                  <p><strong>Master stock : {{ $stock->available_quantity }}</strong> </p>
                                    @if($store_locations->count())
                                      @foreach($store_locations as $location)
                                      <div class="row">
                                        <div class="col-md-6">
                                          <p>{{ $location->name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                          <p>
                                            @if($stock->storeStocks->count())

                                              @if($stock->storeStocks()->where('store_location_id', $location->id)->count())
                                                 {{ $stock->storeStocks()->where('store_location_id', $location->id)->first()->quantity }}
                                              @else
                                                0
                                              @endif

                                            @else
                                              0
                                            @endif
                                          </p>
                                        </div>
                                      </div>
                                      @endforeach
                                    @endif
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-block no-padding">
                      <div class="card-block no-padding">
                        <div class="card card-default">
                          <div class="card-block">
                          </div>
                        </div>
                      </div>
                  </div>
                </div>
            </div>    
        </div>
  </div>
</div>

<!-- END PLACE PAGE CONTENT HERE -->


@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('assets/js/stock.js') }}"></script>
@endsection

@section('page_scripts')
<script>
  $(document).ready(function() {
   
  });
</script>
@endsection





