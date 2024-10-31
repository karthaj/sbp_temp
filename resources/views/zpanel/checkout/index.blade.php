@extends('layouts.zpanel-checkout')

@section('content')

	<div class="row mt-5 justify-content-center">
		<div class="col-sm-7">
			<div class="d-flex justify-content-between mb-3 align-items-end">
				<img src="{{ asset('assets/img/ShopBox_Logo.svg') }}" alt="shopox" class="img-fluid">
				<a href="{{ route('dashboard') }}" class="btn btn-info btn-xs">Back</a>
			</div>
			
			
			<div class="card card-default">
              <div class="card-header">
                <span class="font-weight-bold">Order review</span>
                <hr>
              </div>
              <div class="card-block">
                	<ul class="list-group">
                		@foreach($billing->items as $item)
		            	<li class="list-group-item justify-content-between pb-5">
		            		{{ title_case($item->service->name) }}
		            		<span>LKR {{ number_format($item->amount, 2) }}</span>
		            	</li>
		            	@endforeach
		            	@if($billing->reimburse > 0)
		            	<li class="list-group-item justify-content-between">
		            		Reimburse
		            		<span>LKR {{ number_format($billing->reimburse, 2) }}</span>
		            	</li>
		            	@endif
		            	@if($billing->discount_amount > 0 && $discount)
		            	<li class="list-group-item justify-content-between">
		            	    <span>Discount  ({{ $discount->name }}) 
		            	    	<a href="{{ route('store.checkout.discount.destroy', $billing) }}" onclick="event.preventDefault();document.getElementById('removeDiscount').submit();"><i class="aapl-delete"></i></a>
		            	    </span>
		            		
		            		<span>- LKR {{ number_format($billing->discount_amount, 2) }}</span>
		            	</li>
		            	@endif
		            	<li class="list-group-item justify-content-between">
		            		Tax
		            		<span>LKR {{ number_format($billing->tax, 2) }}</span>
		            	</li>
		            	<li class="list-group-item justify-content-between">
		            		Total Payable
		            		<span class="font-weight-bold">LKR {{ number_format($billing->total_payable, 2) }}</span>
		            	</li>
		            </ul>
              </div>
            </div>
		</div>
	</div>

	<div class="row justify-content-center">
		<div class="col-sm-7">
			<div class="card card-default">
				@if($billing->total_payable > 0)
	          	<div class="card-header">
	                <span class="font-weight-bold">Select payment option</span>
	                <hr>
	            </div>
	            @endif
              	<div class="card-block">
              	@if($billing->total_payable > 0)
                	<div class="radio radio-info">
					  	<input type="radio" value="credit_card" name="payment" id="credit_card" checked>
                  		<label for="credit_card">Credit Card (ShopboxPay)</label>
				    </div>
				    @if(0)
				    <div class="radio radio-info">
				    	<input type="radio" value="bank_transfer" name="payment" id="bank_transfer">
                  		<label for="bank_transfer">Bank Transfer</label>
				    </div>
				    @endif
				@endif
				    @if(!($billing->discount_amount > 0 && $discount))
				    <form action="{{ route('store.checkout.discount', $billing) }}" method="post">
		            	{{ csrf_field() }}
						<div class="form-group row mt-4{{ $errors->has('discount') ? ' has-danger' : '' }}">
			            	<div class="col-md-8">
			            		<input type="text" name="discount" id="discount" class="form-control{{ $errors->has('discount') ? ' form-control-danger' : '' }}" autocomplete="off" placeholder="DISCOUNT CODE">
			            	</div>
			            	<div class="col-md-4">
			            		<button type="submit" class="btn btn-info btn-block text-uppercase">apply</button>
			            	</div>
			            	@if($errors->has('discount'))
                                <div class="form-control-feedback">{{ $errors->first('discount') }}</div>
                            @endif
			            </div>
	            	</form>
	            	@endif
	            	<ul class="list-group">
	            		<li class="list-group-item justify-content-between">
		            		<h4>Total</h4>
		            		<h4 class="font-weight-bold">LKR {{ number_format($billing->total_payable, 2) }}</h4>
		            	</li>
	            	</ul>
	            	@if($billing->total_payable > 0)
	            	<form action="{{ route('store.checkout.update', $billing) }}" method="post">
	            		{{ csrf_field() }}
	            		<button id="payment_bank_transfer" class="btn btn-info btn-block payment-option hide text-uppercase">Pay</button>
	            	</form>
		            <form id="shopboxpay" action="{{ config('services.shopboxpay.endpoint') }}" method="post">
				      	<input type="hidden" name="client_id" value="{{ config('services.shopboxpay.key') }}">
				      	<input type="hidden" name="secret" value="{{ config('services.shopboxpay.secret') }}">
				    	<input type="hidden" name="order_id" value="{{ $billing->id }}">
				    	<input type="hidden" name="amount" value="{{ $billing->total_payable * 100 }}">
				    	<input type="hidden" name="signature" value="{{ $signature }}">
				    	<input type="hidden" name="return_url" value="{{ getStoreUrl(session('store')).'/merchant/admin/response' }}">
				    	<input type="hidden" name="item_count" value="{{ $billing->items->count() }}">
				    	<input type="hidden" name="customer_firstname" value="{{ $user->first_name}}">
				    	<input type="hidden" name="customer_lastname" value="{{ $user->last_name }}">
				    	<input type="hidden" name="customer_email" value="{{ $user->email }}">
				    	<input type="hidden" name="customer_phone" value="{{ $user->phone }}">
				    	<input type="hidden" name="customer_address_line_1" value="{{ $billing->store->address1 }}">
				    	<input type="hidden" name="customer_address_line_2" value="{{ $billing->store->address2 }}">
				    	<input type="hidden" name="customer_city" value="{{ $billing->store->city }}">
				    	<input type="hidden" name="customer_state" value="{{ $billing->store->state->iso_code }}">
				    	<input type="hidden" name="customer_country" value="{{ $billing->store->country->iso_code }}">
				    	<input type="hidden" name="customer_postcode" value="{{ $billing->store->postcode }}">
				    	@foreach($billing->items as $item)
				        <input type="hidden" name="item_name{{ $loop->index }}" value="{{ str_limit($item->service->name, 255) }}">  
				        <input type="hidden" name="item_unit_amount{{ $loop->index }}" value="{{ $item->amount * 100 }}">
				        <input type="hidden" name="item_quantity{{ $loop->index }}" value="{{ $item->quantity }}">
				      	@endforeach
				    	<button id="payment_credit_card" type="submit" class="btn btn-info btn-block payment-option text-uppercase">Pay</button>
				    </form>
		            @else
		            <form action="{{ route('store.checkout.place.order', $billing) }}" method="post">
		            	{{ csrf_field() }}
		            	<button type="submit" class="btn btn-info btn-block text-uppercase">Place Order</button>
		            </form>
		            @endif
              	</div>
	        </div>
	    </div>
	</div>

    <form id="removeDiscount" action="{{ route('store.checkout.discount.destroy', $billing) }}" method="post">
    	{{ csrf_field() }}
    	{{ method_field('DELETE') }}
    </form>

@endsection

@section('page_scripts')
	
	<script>
		Shopbox.togglePaymentOption();
	</script>

@endsection

