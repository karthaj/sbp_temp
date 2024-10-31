@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('orders.return.index') }}">returns</a></li>
</ol>
<!-- END BREADCRUMB --> 

<div class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header">
        <a href="{{ route('orders.return.index') }}" class="bold"><i class="pg-arrow_left"></i>Returns</a>
        <h1 class="section-title">Return #{{ $return->return_id }}</h1>
      </div>
      <div class="card-block">
        <div class="row">
          <!-- start order overview -->
          <div class="col-lg-12">
            <div data-pages="card" class="card card-default">
              <div class="card-header  ">
                <div class="card-title">
                  <i class="fa fa-clipboard"> return merchandise authorization (RMA)</i>
                </div>
                <div class="card-controls">
                  <ul>
                    <li><a data-toggle="collapse" class="card-collapse" href="#"><i class="card-icon card-icon-collapse"></i></a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card-block">
                <div class="row">
                  <div class="col-md-6">
                    <p><span class="font-weight-bold">Customer:</span> 
                      <a href="{{ route('customers.edit', $return->customer) }}">{{ title_case($return->customer->firstname) }} {{ title_case($return->customer->lastname) }}</a> </p>
                  </div>
                  <div class="col-md-6">
                    <p><span class="font-weight-bold">Order:</span> 
                      <a href="{{ route('orders.edit', $return->order) }}">{{ $return->order->order_id }}</a></p>
                  </div>
                </div>
                <form action="{{ route('orders.return.update', $return) }}" method="post">
                  {{ csrf_field() }}
                  {{ method_field('PATCH') }}
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row align-items-end">
                      <label for="status" class="control-label col-md-2">status</label>
                        <select name="status" id="status" class="form-control col-md-7" data-init-plugin="select2"
                        @if($return->status->name === 'Refunded') disabled @endif>
                          @if($states->count())
                            @foreach($states as $status)
                            @if($status->id === 3 && $return->state === 2) 
                              <option value="{{ $status->id }}" @if($return->state === $status->id) selected @endif
                                @if(!auth()->user()->can('approve returns')) disabled @endif>{{ $status->name }}</option>
                            @elseif($status->id === 6 && $return->state === 2)
                              <option value="{{ $status->id }}" @if($return->state === $status->id) selected @endif
                                @if(!auth()->user()->can('decline returns')) disabled @endif>{{ $status->name }}</option>
                            @elseif($status->id !== 6 && $status->id !== 3 && $status->id !== 5)
                              <option value="{{ $status->id }}" @if($return->state === $status->id) selected @endif>{{ $status->name }}</option>
                            @elseif($status->id === 5 && $return->state === 3)
                              <option value="{{ $status->id }}" @if($return->state === $status->id) selected @endif>{{ $status->name }}</option>
                            @endif
                            @endforeach
                          @endif
                        </select>
                      </div>
                    </div>
                    @if($return->status->name === 'Return Request Approved')
                      <div class="col-md-6">
                        <div class="radio radio-success">
                          <input type="radio" value="1" name="refund_method" id="store_credit">
                          <label for="store_credit">Issue store credits</label>
                          <input type="radio" value="2" name="refund_method" id="discount">
                          <label for="discount">Generate discount code</label>
                          <input type="radio" value="3" name="refund_method" id="cash">
                          <label for="cash">credit cash</label>
                        </div>
                        <span class="font-weight-bold">Refundable:</span> {{ $return->order->payment->order_currency }} {{ $refundable_amount }}
                        @if(!$return->order->cart_discount)
                          <div class="form-group row align-items-end">
                            <label for="amount" class="control-label col-md-5">Amount of your choice</label>
                            <div class="input-group transparent col-md-5">
                              <span class="input-group-addon">{{ $return->order->currency->iso_code }}</span>
                              <input type="text" class="form-control" id="amount" name="amount" value="0">
                            </div>
                          </div>
                        @endif
                      </div>
                    @endif
                  </div>
                  @if($return->order->cart_discount)
                    <p>This order has been partially paid by discount code. Choose the amount you want to refund:</p>
                    <div class="radio radio-success">
                      <input type="radio" value="{{ $amount }}" name="refund_amount" id="refundable_amount">
                      <label for="refundable_amount">Included discount amount: {{ $return->order->payment->order_currency }} {{ $amount }}</label>
                      <input type="radio" value="{{ $refundable_amount }}" name="refund_amount" id="refund_discount_amount" checked>
                      <label for="refund_discount_amount">Excluded discount amount: {{ $return->order->payment->order_currency }} {{ $refundable_amount }}</label>
                    </div>
                    <div class="radio radio-success">
                      <input type="radio" value="0" name="refund_amount" id="refund_custom_amount">
                      <label for="refund_custom_amount">Amount of your choice</label>
                      <div class="input-group transparent col-md-2">
                          <span class="input-group-addon">{{ $return->order->payment->order_currency }}</span>
                          <input type="text" class="form-control" name="amount" value="0">
                      </div>
                    </div>
                   @endif  
                  <div class="form-group">
                    <label for="seller_note">seller note</label>
                    <textarea name="seller_note" id="seller_note" cols="30" rows="10" class="form-control"></textarea>
                  </div>
                  @if(auth()->user()->can('edit returns'))
                    <div class="form-group pull-right">
                      <button type="submit" class="btn btn-info">Update</button>
                    </div>
                  @endif
                </form>
              </div>
            </div>
          </div>
          <!-- end order overciew -->
        </div>

        <!-- return products -->
        <div class="row">
          <div class="col-lg-12">
            <div data-pages="card" class="card card-default" id="card-basic">
              <div class="card-header  ">
                <div class="card-title">
                  <i class="fa fa-shopping-cart"> products</i>
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
                        <th>product</th>
                        <th>SKU</th>
                        <th>Quantity</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($return->details as $item)
                        <tr>
                          <td class="v-align-middle">{{ $item->orderDetail->product_name }}</td>
                          <td class="v-align-middle">{{ $item->orderDetail->product_sku ?: 'n/a' }}</td>
                          <td class="v-align-middle">{{ $item->quantity }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- / return products -->
      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts') 
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
@endsection


