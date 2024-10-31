<div class="tab-pane {{ request()->tab === 'order_list' || request()->tab === 'view_order' ? 'active' : '' }}">
@if(request()->tab === 'order_list')
  <div class="row">
    @if(auth()->user()->orders->count())
      <div class="col-12 table-responsive">
        <table class="table">
            <thead class="thead-dark">
              <tr>
                <th>DATE</th>
                <th>NO</th>
                <th>STORE</th>
                <th>AMOUNT</th>
                <th>STATUS</th>
                <th><i class="fa fa-eye"></i></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            @foreach(auth()->user()->orders()->where('store_id', session('store')->id)->get() as $order)
              <tr>
                <th>{{ $order->created_at->toFormattedDateString() }}</th>
                <td>{{ $order->store->id }}-{{ $order->order_id }}</td>
                <td>{{ $order->store->store_name }}</td>
                <td>{{ $order->currency->iso_code }} {{ number_format($order->total_paid, 2) }}</td>
                <td>
                  <span class="badge text-white" style="background-color: {{ $order->state->color }}">{{ $order->state->name }}</span>
                </td>
                <td>
                  <a href="/account/?tab=view_order&order_id={{ $order->id }}">view</a>
                </td>
                @if($order->state->slug === 'completed' && ($order->store->setting->enable_returns || $order->store->setting->enable_partial_returns))
                <td><a href="/account?tab=return&order_id={{ $order->id }}">return</a></td>
                @endif
              </tr>
            @endforeach
            </tbody>
          </table>
      </div>
    @else
    <div class="col">
      <div class="alert alert-info" role="alert">
        You haven't placed any orders.
      </div>
    </div>
    @endif
  </div>
@elseif(request()->tab === 'view_order')
<div class="row">
  <div class="col-lg-8">
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="thead-default">
          <tr>
            <th scope="col">Product</th>
            <th scope="col">Price</th>
            <th scope="col">Quantity</th>
            <th scope="col">Total</th>
          </tr>
        </thead>
        <tbody>
        @foreach($order->details as $detail)
          <tr>
            <td width="50%">
              <p class="mb-0">{{ $detail->product_name }}</p>
              @if($detail->return_detail)
              <small>({{ $detail->return_detail->quantity }} refunded)</small>
              @endif
              @if($detail->product->file && $detail->product->file->maximum_downloads == 0)
                <a href="{{ route('download.item', ['order' => $order, 'file' => $detail->product->file]) }}">download</a>
              @elseif($detail->product->file && $detail->download_nb < $detail->product->file->maximum_downloads)
                <a href="{{ route('download.item', ['order' => $order, 'file' => $detail->product->file]) }}">download</a>
              @elseif($detail->product->file && $detail->product->file->maximum_downloads == $detail->download_nb)
                <p>(File has expired)</p>       
              @endif
            </td>
            <td>{{ $detail->order->currency->iso_code }}  {{ number_format($detail->product_price,2) }}</td>
            <td>{{ $detail->product_quantity }}</td>
            <td>{{ $detail->order->currency->iso_code }} {{ number_format($detail->product_price * $detail->product_quantity, 2) }}</td>
          </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="text-right">Subtotal</td>
            <td>{{ $order->currency->iso_code }} {{ number_format($detail->order->total_products,2) }}</td>
          </tr>

          @if($order->cart_discount)
            <tr>
              <td colspan="3" class="text-right">Discount ({{ $order->cart_discount->name }})</td>
              <td>{{ $order->currency->iso_code }} -{{ number_format($order->total_discounts,2) }}</td>
            </tr>
          @endif
          
          @if($order->shipper)
          <tr>
            <td colspan="3" class="text-right">Shipping ({{ $order->shipper->name }})</td>
            <td>{{ $order->currency->iso_code }} {{ number_format($order->total_shipping_tax_excl,2) }}</td>
          </tr>
          @endif
          
          <tr>
            <td colspan="3" class="text-right"><strong>Tax</strong></td>
            <td><strong>{{ $order->currency->iso_code }} {{ number_format($order->total_paid_tax_incl - $order->total_paid_tax_excl,2) }}</strong></td>
          </tr>

          <tr>
            <td colspan="3" class="text-right"><strong>Total</strong></td>
            <td><strong>{{ $order->currency->iso_code }} {{ number_format($order->total_paid,2) }}</strong></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="row">
      <div class="col-lg-12 col-sm-6">
        <div class="card mb-4">
          <div class="card-header">Billing Address</div>
          <div class="card-block">
            <p class="card-title"><span class="font-weight-bold">Payment status:</span> 
                @if($order->total_real_paid != 0)
                  Paid
                @else
                  Pending
                @endif
            </p>
            <p class="card-text">{{ $order->billing_address->firstname.' ',$order->billing_address->lastname }}
                <br>{{ $order->billing_address->address }}
                @if($order->billing_address->address2)
                  <br> {{ $order->billing_address->address2 }}
                @endif
                <br> {{ $order->billing_address->city }} 
                @if($order->billing_address->state)
                    {{ $order->billing_address->state->iso_code }}
                @endif
                <br> {{ $order->billing_address->zip_code }}
                <br> {{ $order->billing_address->country->name }}
            </p>
          </div>
        </div>
      </div>
      @if($order->shipper)
      <div class="col-lg-12 col-sm-6">
        <div class="card mb-4">
          <div class="card-header">Shipping Address</div>
          <div class="card-block">
            <p class="card-title"><span class="font-weight-bold">Order status:</span> {{ $order->state->name }}</p>
            <p class="card-text">{{ $order->shipping_address->firstname.' ',$order->shipping_address->lastname }}
                <br>{{ $order->shipping_address->address }}
                @if($order->shipping_address->address2)
                  <br> {{ $order->shipping_address->address2 }}
                @endif
                <br> {{ $order->shipping_address->city }} 
                @if($order->shipping_address->state)
                    {{ $order->shipping_address->state->iso_code }}
                @endif
                <br> {{ $order->shipping_address->zip_code }}
                <br> {{ $order->shipping_address->country->name }}
            </p>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endif
</div>