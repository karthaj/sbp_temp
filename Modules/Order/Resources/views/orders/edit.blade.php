@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">orders</a></li>
</ol>
<!-- END BREADCRUMB --> 

<div class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <h1 class="section-title">Order {{ $order->order_id }}</h1><span id="order_status" class="label label-success" style="background-color: {{ $order->state->color }}">{{ $order->state->name }}</span>
      </div>
      <div class="card-block">
        <div class="row">
          <!-- start order overview -->
          <div class="col-lg-8">
            <div data-pages="card" class="card card-default" id="card-basic">
              <div class="card-header  ">
                <div class="card-title">
                  <i class="aapl-receipt text-uppercase"> order</i>
                </div>
                <div class="card-controls">
                  <ul>
                    <li><a data-toggle="collapse" class="card-collapse" href="#"><i class="card-icon card-icon-collapse"></i></a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card-block">
                @if(!$order->archived)
                  <div class="row align-items-end">
                    <div class="form-group col-md-8">
                      <label for="status">Status</label>
                      <select name="status" id="status" class="form-control full-width" data-init-plugin="select2">
                        @foreach($statuses as $status)
                          @if($status->slug != 'pending' && $status->slug != 'ready-for-pickup')
                          <option value="{{ $status->slug }}" @if($order->current_state == $status->id) selected @endif>{{ $status->name }}</option>
                          @elseif($status->slug == 'ready-for-pickup' && $order->shipper && !$order->shipper->carrier)
                          <option value="{{ $status->slug }}" @if($order->current_state == $status->id) selected @endif>{{ $status->name }}</option>
                          @endif
                        @endforeach
                        <option value="" disabled="disabled"></option>
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                        <button type="button" id="btnStatus" class="btn btn-info btn-sm" data-url="{{ route('orders.status', $order) }}">Update</button>
                    </div>
                  </div>
                @endif
                @if($order->history->count())
                <p class="card-title my-3">Timeline</p>
                <ul class="list-group list-group-flush">
                  @foreach($order->history as $history)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="label label-success" style="background-color: {{ $history->state->color }}">{{ $history->state->name }}</span>
                        <span>{{ $history->created_at->toDayDateTimeString() }}</span>
                    </li>
                  @endforeach
                </ul>
                @endif
              </div>
            </div>

            <!-- order carrier -->
            <div data-pages="card" class="card card-default" id="card-basic">
              <div class="card-header  ">
                <div class="card-title">
                  <i class="aapl-truck text-uppercase"> shipping</i>
                </div>
                <div class="card-controls">
                  <ul>
                    <li><a data-toggle="collapse" class="card-collapse" href="#"><i class="card-icon card-icon-collapse"></i></a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card-block">
              @if($order->shipper)
                <div class="table-responsive">
                  <table class="table table-hover" id="condensedTable">
                    <thead>
                      <tr>
                        <th style="width:30%">Carrier</th>
                        <th style="width:20%">Weight</th>
                        <th style="width:20%">Cost</th>
                        <th style="width:30%">Tracking Number</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="v-align-middle">{{ $order->shipper->name }}</td>
                        @if(session('store')->setting->weight->weight_code == 'kg')
                          <td class="v-align-middle">{{ $order->shipper->weight / 1000 }} kg</td>
                        @elseif(session('store')->setting->weight->weight_code == 'g')
                          <td class="v-align-middle">{{ $order->shipper->weight }} g</td>
                        @elseif(session('store')->setting->weight->weight_code == 'lb')
                          <td class="v-align-middle">{{ $order->shipper->weight / 453.592 }} lb</td>
                        @elseif(session('store')->setting->weight->weight_code == 'oz')
                          <td class="v-align-middle">{{ $order->shipper->weight / 28.35 }} oz</td>
                        @elseif(session('store')->setting->weight->weight_code == 't')
                          <td class="v-align-middle">{{ $order->shipper->weight / 907185 }} t</td>
                        @endif
                        
                        <td class="v-align-middle">{{ $order->currency->iso_code }} {{ number_format($order->shipper->shipping_cost_tax_incl, 2) }}</td>
                        <td class="v-align-middle">{{ $order->shipper->tracking_number }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <button class="btn btn-info btn-xs mt-3" type="button" data-toggle="modal" data-target="#shippingModal">
                  <i class="aapl-pencil"></i> <span class="bold">Edit</span>
                </button>
              @else
              <p>N/A</p>
              @endif
              </div>
            </div>
            <!-- / order carrier -->

          </div>
          <!-- end order overciew -->

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
                    <strong>Billing Address</strong>
                    <p>{{ $order->billing_address->firstname.' ',$order->billing_address->lastname }} ({{ $order->customer->customerEmail }})
                      <br>{{ $order->billing_address->address }}
                      @if($order->billing_address->address2)
                        <br> {{ $order->billing_address->address2 }}
                      @endif
                      @if($order->billing_address->city)
                        <br> {{ $order->billing_address->city }} 
                        @if($order->billing_address->state)
                          {{ $order->billing_address->iso_code }}
                        @endif
                      @endif
                      @if($order->billing_address->zip_code)
                        <br> {{ $order->billing_address->zip_code }}
                      @endif
                      <br> {{ $order->billing_address->country->name }}
                      <br>{{ $order->billing_address->phone }}
                    </p>

                    <hr>
                    @if($order->requiredShipping())
                      <strong>Shipping Address</strong>
                      <p>{{ $order->shipping_address->firstname.' ',$order->shipping_address->lastname }} ({{ $order->customer->customerEmail }})
                        <br>{{ $order->shipping_address->address }}
                        @if($order->shipping_address->address2)
                          <br> {{ $order->shipping_address->address2 }}
                        @endif
                        @if($order->shipping_address->city)
                          <br> {{ $order->shipping_address->city }} 
                          @if($order->shipping_address->state)
                            {{ $order->shipping_address->iso_code }}
                          @endif
                        @endif
                        @if($order->shipping_address->zip_code)
                          <br> {{ $order->shipping_address->zip_code }}
                        @endif
                        <br> {{ $order->shipping_address->country->name }}
                        <br>{{ $order->shipping_address->phone }}
                      </p>
                    @else
                      <strong>Virtual Item</strong>
                      <p>This order contains downloadable item and will be sent to the customer once the order status is marked as 'Completed'.</p>
                    @endif
                  </div>
                </div>
              </div>
              <!-- end customer overview -->
              @if($order->messages->count())
              <!-- start messages -->
              <div class="col-md-12">
                <div class="card card-default">
                  <div class="card-header ">
                    <div class="card-title">
                      <i class="aapl-envelope-open text-uppercase"> Order Message</i>
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
                    <hr>
                    @foreach($order->messages as $thread)
                      <p>{{ $thread->message }}</p>
                    @endforeach
                  </div>
                </div>
              </div>
              @endif
              <!-- end messages -->
            </div>
          </div>
        </div>
        @if($order->payment)
        <!-- order payment -->
        <div class="row">
          <div class="col-lg-12">
            <div data-pages="card" class="card card-default" id="card-basic">
              <div class="card-header  ">
                <div class="card-title">
                  <i class="aapl-bag-dollar text-uppercase"> payment</i>
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
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>date</th>
                        <th>payment method</th>
                        <th>trnc id</th>
                        <th>trnc amt</th>
                        <th>order amt</th>
                        <th>status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="v-align-middle">{{ $order->created_at->toDateTimeString() }}</td>
                        <td class="v-align-middle">{{ $order->payment_method }}</td>
                        <td class="v-align-middle">{{ $order->payment->transaction_id ?: 'n/a' }}</td>
                        <td class="v-align-middle">{{ $order->payment->trans_currency }} {{ number_format($order->payment->trans_amount, 2) }}</td>
                        <td class="v-align-middle" id="order_amount">{{ $order->payment->order_currency }} {{ number_format($order->payment->order_amount, 2) }}</td>
                        <td class="v-align-middle"><span class="label" id="payment_status">{{ $order->total_real_paid > 0 ? 'received' : 'pending' }}</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                @if($manual_payment)
                  <form id="paymentForm" action="{{ route('orders.payment', $order) }}" method="post">
                    <br>
                    <div class="row align-items-end">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="transaction_id">transaction id</label>
                          <input type="text" id="transaction_id" name="transaction_id" class="form-control" autocomplete="off">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="currency">Currency</label>
                          <select name="currency" id="currency" class="form-control full-width" data-init-plugin="select2">
                            @foreach($currencies as $currency)
                              <option value="{{ $currency->iso_code }}">{{ $currency->iso_code }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label for="amount">Amount</label>
                          <input type="text" class="form-control autonumeric" id="amount" name="amount" autocomplete="off">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <button type="button" class="btn btn-info" id="btnPayment">save</button>
                        </div>
                      </div>
                    </div>
                  </form>
                @endif
              </div>
            </div>
          </div>
        </div>
        <!-- / order payment -->
        @endif
        
        @if($order->details->count())
        <!-- order product -->
        <div class="row">
          <div class="col-lg-12">
            <div data-pages="card" class="card card-default" id="card-basic">
              <div class="card-header  ">
                <div class="card-title">
                  <i class="aapl-bag2 text-uppercase"> products</i>
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
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th style="width:10%"></th>
                        <th>product</th>
                        <th>price</th>
                        <th>qty</th>
                        <th class="text-right">total</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($order->details as $detail)
                        <tr>
                          <td class="v-align-middle">
                            <img src="{{ $detail->image }}" alt="{{ $detail->product_name }}" class="img-fluid img-thumbnail">
                          </td>
                          <td class="v-align-middle">
                            @if($detail->product_quantity === $detail->product_quantity_refunded)
                              <del>{{ $detail->product_name }}</del> 
                              <span>(Refunded)</span>
                            @elseif($detail->product_quantity_refunded > 0 && $detail->product_quantity_refunded !== $detail->product_quantity)
                              {{ $detail->product_name }}
                              <span>({{ $detail->product_quantity_refunded }} Refunded)</span>
                            @elseif($detail->product->type === 'virtual')
                              {{ $detail->product_name }}
                              <span>(Virtual Item)</span>
                            @else
                              {{ $detail->product_name }}
                            @endif
                          </td>
                          <td class="v-align-middle">{{ $detail->order->currency->iso_code }} {{ number_format($detail->unit_price, 2) }}</td>
                          <td class="v-align-middle">{{ $detail->product_quantity }}</td>
                          <td class="v-align-middle text-right">{{ $detail->order->currency->iso_code }} {{ number_format($detail->total_price,2) }}</td>
                        </tr>
                      @endforeach
                      <tr>
                        <td colspan="4" class="text-right">Subtotal</td>
                        <td class="text-right">{{ $order->currency->iso_code }} {{ number_format($order->total_products, 2) }}</td>
                      </tr>
                      @if($order->total_discounts > 0)
                        <tr>
                          <td colspan="4" class="text-right">Discount</td>
                          <td>{{ $order->currency->iso_code }} ({{ number_format($order->total_discounts, 2) }})</td>
                        </tr>
                      @endif
                      @if($order->shipper)
                        <tr>
                          <td colspan="4" class="text-right">Shipping ({{ $order->shipper->name }})</td>
                          <td class="text-right">{{ $order->currency->iso_code }} {{ number_format($order->total_shipping, 2) }}</td>
                        </tr>   
                      @endif
                      <tr>
                        <td colspan="4" class="text-right">Tax</td>
                        <td class="text-right">{{ $order->currency->iso_code }} {{ number_format($order->total_products_wt - $order->total_products, 2) }}</td>
                      </tr>
                      @if($order->surcharge > 0)
                        <tr>
                          <td colspan="4" class="text-right">Surcharge</td>
                          <td class="text-right">{{ $order->currency->iso_code }} {{ number_format($order->surcharge, 2) }}</td>
                        </tr>
                      @endif
                      @if($order->store_credits > 0)
                        <tr>
                          <td colspan="4" class="text-right">Store Credit</td>
                          <td class="text-right">{{ $order->currency->iso_code }} {{ number_format($order->store_credits, 2) }}</td>
                        </tr>
                      @endif
                      <tr>
                        <td colspan="4" class="text-right">Grandtotal</td>
                        <td class="text-right">{{ $order->currency->iso_code }} {{ number_format($order->total_paid, 2) }}</td>
                      </tr>
                      @if($order->payment && $order->payment->refund > 0)
                      <tr>
                        <td colspan="4" class="text-right">Refunded</td>
                        <td class="text-right">-{{ $order->currency->iso_code }} {{ number_format($order->payment->refund, 2) }}</td>
                      </tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- / order product -->
        @endif
      </div>
    </div>
  </div>
</div>

@if($order->shipper)
<div class="modal fade slide-up disable-scroll" id="shippingModal" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5><span class="semi-bold">Shipping</span></h5>
        </div>
        <div class="modal-body">
           <div class="form-group row">
            <label for="tracking_number" class="col-sm-4 col-form-label">tracking number</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="tracking_number" value="{{ $order->shipper->tracking_number }}">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-complete" id="btnTrack" data-url="{{ route('orders.tracking.number', $order) }}">Save</button>
        </div>  
      </div>
    </div>
  </div>
</div>
@endif

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-autonumeric/autoNumeric.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/order.js') }}"></script>
@endsection


@section('page_scripts')
<script>
  $(document).ready(function() {
      $('.autonumeric').autoNumeric('init');
  });
</script>
@endsection

