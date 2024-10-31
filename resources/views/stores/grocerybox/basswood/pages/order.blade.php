@extends($theme_path.'.layouts.theme')

@section('content')

	<div class="container">
		<div class="row align-items-center">
			<div class="col-xl-10 col-lg-10 col-mb-10 col-sm-10 col-xs-10">
				<h2>Order {{ $order->order_id }}</h2>
      			<p>Placed on {{ $order->created_at->toDayDateTimeString() }}</p>
			</div>
			<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-2">
				<p class="pull-right">
      				<a href="{{ route('customer.profile') }}">Return to profile</a>
      			</p>
			</div>
		</div>
		<div class="row clearfix">
			<div class="col-xl-9 col-lg-9 col-mb-8 col-sm-12 col-xs-12">
				<table class="table-responsive table table-condensed">
			        <thead>
			          <tr>
			            <th>Product</th>
			            <th>SKU</th>
			            <th>Price</th>
			            <th>Quantity</th>
			            <th>Total</th>
			          </tr>
			        </thead>
			        <tbody>
			          @foreach($order->details as $detail)
			          <tr class="responsive-table__row" >
			            <td data-label="Product">
			              <a href="{{ route('stores.product.show', $detail->product) }}">{{ $detail->product_name }}</a>
			            </td>
			            <td data-label="Product">{{ $detail->product_sku }}</td>
			            <td data-label="SKU">{{ $detail->order->currency->iso_code }}  {{ number_format($detail->product_price,2) }}</td>
			            <td data-label="Price">{{ $detail->product_quantity }}</td>
			            <td data-label="Total">{{ $detail->order->currency->iso_code }} {{ number_format($detail->product_price * $detail->product_quantity, 2) }}</td>
			          </tr>
			          @endforeach
			        </tbody>
			        <tfoot>
			          <tr class="responsive-table__row">
			            <td colspan="4" class="small--hide">Subtotal</td>
			            <td>{{ $order->currency->iso_code }} {{ number_format($detail->order->total_products,2) }}</td>
			          </tr>

			          @if($order->cart_discount)
			            <tr class="order_summary discount">
			              <td colspan="4" class="small--hide">Discount ({{ $order->cart_discount->name }})</td>
			              <td>{{ $order->currency->iso_code }} -{{ number_format($order->total_discounts,2) }}</td>
			            </tr>
			          @endif

			          <tr>
			            <td colspan="4" class="small--hide">Shipping ({{ $order->carrier->display_name }})</td>
			            <td>{{ $order->currency->iso_code }} {{ number_format($order->total_shipping_tax_excl,2) }}</td>
			          </tr>
			          
			          @php
			            $tax = 0;
			            foreach($order->details as $detail) {
			              if($detail->taxes->count()) {
			                $tax += $detail->taxes->sum('total_amount');
			              }
			            }

			            if($order->invoice) {
			              $tax += $order->invoice->taxes->sum('amount');
			            }
			          @endphp
			          

			          <tr>
			            <td colspan="4" class="small--hide"><strong>Total</strong></td>
			            <td><strong>{{ $order->currency->iso_code }} {{ number_format($order->total_paid,2) }}</strong></td>
			          </tr>
			        </tfoot>
			    </table>
			</div>
			<div class="col-xl-3 col-lg-3 col-mb-4 col-sm-12 col-xs-12 b-sidebar_widget">
				<h5 class="b-widget_title">Billing Address</h5>
				<p><strong>Payment Status:</strong> 
			        @if($order->total_real_paid != 0)
			          Paid
			        @else
			          Pending
			        @endif
			    </p>
			    <p>{{ $order->billing_address->firstname.' ',$order->billing_address->lastname }}
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

			    <h5 class="b-widget_title">Shipping Address</h5>

      			<p><strong>Status:</strong> {{ $order->state->name }}</p>
			      
			    <p>{{ $order->shipping_address->firstname.' ',$order->shipping_address->lastname }}
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

@endsection