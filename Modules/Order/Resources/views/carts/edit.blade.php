@extends('layouts.zpanel')


@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">orders</a></li>
  <li class="breadcrumb-item"><a href="{{ route('orders.abandoned.carts.index') }}">abandoned carts</a></li>
</ol>
<!-- END BREADCRUMB --> 

<div class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <h1 class="section-title">Abandoned Cart</h1>
      </div>
      <div class="card-block">
        <div class="row">
          <div class="col-lg-8">
            <!-- start cart overview -->
            <div data-pages="card" class="card card-default" id="card-basic">
              <div class="card-header  ">
                <div class="card-title">
                  <i class="fa fa-shopping-cart"> Cart Value</i>
                </div>
                <div class="card-controls">
                  <ul>
                    <li><a data-toggle="collapse" class="card-collapse" href="#"><i class="card-icon card-icon-collapse"></i></a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card-block">
                <div class="row align-items-center justify-content-between">
                  <div class="col-md-5">
                    <span><b>Cart Total:</b> {{ $checkout['cart']['currency'] }} {{ number_format($checkout['grand_total'], 2) }}</span>
                  </div>
                  <div class="col-md-5 col-sm-5 btn-group">
                    @if(auth()->user()->can('delete carts'))
                    <button type="button" id="btnCartDelete" class="btn btn-danger btn-xs" onclick="event.preventDefault();document.getElementById('destroyCart').submit();">Delete cart</button>
                    @endif
                    @if(auth()->user()->can('send recover notification') && $checkout['cart']['email'])
                    <button type="button" id="btnCartRecovery" class="btn btn-info btn-xs" data-toggle="modal" data-target="#sendEmailModal">Send email</button>
                    @endif
                    @if($checkout['cart']['stock_reserved'])
                    <button type="button" id="btnRestock" class="btn btn-info btn-xs" onclick="event.preventDefault();document.getElementById('restock').submit();">Restock products</button>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <!-- end cart overview -->
          </div>
          <div class="col-lg-4">
            <div class="row">
               <!-- start customer overview -->
              <div class="col-md-12">
                <div class="card card-default">
                  <div class="card-header ">
                    <div class="card-title">
                      <i class="aapl-user text-uppercase"> Customer </i>
                    </div>
                    <div class="card-controls">
                      <ul>
                        <li>
                          <a data-toggle="collapse" class="card-collapse" href="#"><i class="card-icon card-icon-collapse"></i></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="card-block">
                    @if(count($checkout['billing_address']) || count($checkout['shipping_address']))
                      <strong>Billing Address</strong>
                      <p>{{ $checkout['billing_address']['firstname'].' ',$checkout['billing_address']['lastname'] }} ({{ $checkout['customer']['email'] }})
                        <br>{{ $checkout['billing_address']['address1'] }}
                        @if($checkout['billing_address']['address2'])
                          <br> {{ $checkout['billing_address']['address2'] }}
                        @endif
                        @if($checkout['billing_address']['city'])
                          <br> {{ $checkout['billing_address']['city'] }} 
                          @if($checkout['billing_address']['state'])
                            {{ $checkout['billing_address']['state']}}
                          @endif
                        @endif
                        @if($checkout['billing_address']['postcode'])
                          <br> {{ $checkout['billing_address']['postcode'] }}
                        @endif
                        <br> {{ $checkout['billing_address']['country'] }}
                        <br>{{ $checkout['billing_address']['phone'] }}
                      </p>

                      <hr>
                      
                      @if($checkout['cart']['need_shipping'])
                        <strong>Shipping Address</strong>
                        <p>{{ $checkout['shipping_address']['firstname'].' ',$checkout['shipping_address']['lastname'] }} ({{ $checkout['customer']['email'] }})
                          <br>{{ $checkout['shipping_address']['address1'] }}
                          @if($checkout['shipping_address']['address2'])
                            <br> {{ $checkout['shipping_address']['address2'] }}
                          @endif
                          @if($checkout['shipping_address']['city'])
                            <br> {{ $checkout['shipping_address']['city'] }} 
                            @if($checkout['shipping_address']['state'])
                              {{ $checkout['shipping_address']['state'] }}
                            @endif
                          @endif
                          @if($checkout['shipping_address']['postcode'])
                            <br> {{ $checkout['shipping_address']['postcode'] }}
                          @endif
                          <br> {{ $checkout['shipping_address']['country'] }}
                          <br>{{ $checkout['shipping_address']['phone'] }}
                        </p>
                      @else
                        <strong>Virtual Item</strong>
                        <p>This cart contains downloadable item.</p>
                      @endif
                    @else
                      <p>Customer information not available.</p>
                    @endif
                  </div>
                </div>
              </div>
              <!-- end customer overview -->
            </div>
          </div>
        </div>

        <!-- order product -->
        <div class="row">
          <div class="col-lg-12">
            <div data-pages="card" class="card card-default" id="card-basic">
              <div class="card-header  ">
                <div class="card-title">
                  <i class="fa fa-tag"> products</i>
                </div>
                <div class="card-controls">
                  <ul>
                    <li><a data-toggle="collapse" class="card-collapse" href="#"><i class="card-icon card-icon-collapse"></i></a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card-block">
                <div class="table-responsive">
                  <table class="table table-hover table-condensed">
                    <thead>
                      <tr>
                        <th style="width:10%"></th>
                        <th>product</th>
                        <th>price</th>
                        <th>qty</th>
                        <th class="text-center">total</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($checkout['cart']['items'] as $item)
                        <tr>
                          <td class="v-align-middle">
                           <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="img-fluid img-thumbnail">
                          </td>
                          <td class="v-align-middle">{{ $item['name'] }}</td>
                          <td class="v-align-middle">{{ $checkout['cart']['currency'] }} {{ number_format($item['selling_price'],2) }}</td>
                          <td class="v-align-middle">{{ $item['quantity'] }}</td>
                          <td class="v-align-middle text-right">{{ $checkout['cart']['currency']}} {{ number_format($item['line_price'],2) }}</td>
                        </tr>
                      @endforeach
                      <tr>
                        <td class="text-right" colspan="4">Subtotal</td>
                        <td class="text-right">{{ $checkout['cart']['currency'] }} {{ number_format($checkout['subtotal'], 2) }}</td>
                      </tr>
                      @if($checkout['cart']['total_discount'] > 0)
                        <tr>
                          <td class="text-right" colspan="4">Discount</td>
                          <td class="text-right">{{ $checkout['cart']['currency'] }} ({{ number_format($checkout['cart']['total_discount'], 2) }})</td>
                        </tr>
                      @endif
                      @if($checkout['cart']['need_shipping'] && count($checkout['consignment']))
                      <tr>
                        <td class="text-right" colspan="4">Shipping ({{ $checkout['consignment']['name'] }})</td>
                        <td class="text-right">{{ $checkout['cart']['currency'] }} {{ number_format($checkout['consignment']['rate'], 2) }}</td>
                      </tr>
                       @endif
                      <tr>
                        <td class="text-right" colspan="4">{{ $checkout['tax']['name'] }}</td>
                        <td class="text-right">{{ $checkout['cart']['currency'] }} {{ number_format($checkout['tax']['amount'], 2) }}</td>
                      </tr>
                      @if($checkout['surcharge'] > 0)
                        <tr>
                          <td class="text-right" colspan="4">Surcharge</td>
                          <td class="text-right">{{ $checkout['cart']['currency'] }} ({{ number_format($checkout['surcharge'], 2) }})</td>
                        </tr>
                      @endif
                      <tr class="font-weight-bold">
                        <td class="text-right" colspan="4">Grandtotal</td>
                        <td class="text-right">{{ $checkout['cart']['currency'] }} {{ number_format($checkout['grand_total'], 2) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- / order product -->
      </div>
    </div>
  </div>
</div>

<div class="modal fade slide-up disable-scroll" id="sendEmailModal" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5>Recovery <span class="semi-bold">Email</span></h5>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="message">Message</label>
            <textarea name="message" id="message" cols="30" rows="10" class="form-control">{{ $store->emails()->where('slug', 'abandoned-cart-notification')->first()->email_body }}</textarea>
          </div>
          <div class="form-group">
            <button type="button" id="btnSend" data-url="{{ route('orders.abandoned.carts.recovery.email', $checkout['id']) }}" class="btn btn-complete">Send</button>
          </div>
        </div>  
      </div>
    </div>
  </div>
</div>

<form id="destroyCart" action="{{ route('orders.abandoned.carts.destroy', $checkout['id']) }}" method="POST" style="display: none;">
  {{ csrf_field() }}
  {{ method_field('DELETE') }}
</form>

<form id="restock" action="{{ route('orders.abandoned.cart.restock', $checkout['id']) }}" method="POST" style="display: none;">
  {{ csrf_field() }}
</form>

@endsection

@section('scripts') 
<script type="text/javascript" src="{{ asset('assets/js/order.js') }}"></script>
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
@endsection


