@extends('layouts.zpanel-checkout')

@section('content')

	<div class="row mt-5 justify-content-center">
		<div class="col-sm-7">
			<img src="{{ asset('assets/img/ShopBox_Logo.svg') }}" alt="shopox" class="img-fluid mb-3">
			<div class="card card-default">
              <div class="card-block">
              	<div class="alert alert-success" role="alert">{{ $message }}</div>
              	<a href="{{ route('dashboard') }}" class="btn btn-info">Return to dashboard</a>
              </div>
            </div>
		</div>
	</div>

	<div class="row justify-content-center">
		<div class="col-sm-7">
			<div class="card card-default">
	          <div class="card-header">
	            <span class="font-weight-bold">Summary</span>
	          </div>
	          <div class="card-block">
	            <ul class="list-group">
	            	@foreach($billing->items as $item)
	            	<li class="list-group-item justify-content-between">
	            		{{ $item->service->name }}
	            		<span>LKR {{ number_format($item->amount, 2) }}</span>
	            	</li>
	            	@endforeach
	            	@if($billing->reimburse > 0)
	            	<li class="list-group-item justify-content-between">
	            		Reimburse
	            		<span>LKR {{ number_format($billing->reimburse, 2) }}</span>
	            	</li>
	            	@endif
	            	@if($billing->discount_amount > 0)
	            	<li class="list-group-item justify-content-between">
	            		Discount ({{ $billing->discount->name }})
	            		<span>- LKR {{ $billing->discount_amount }}</span>
	            	</li>
	            	@endif
	            	<li class="list-group-item justify-content-between">
	            		Tax
	            		<span>LKR {{ number_format($billing->tax, 2) }}</span>
	            	</li>
	            	<li class="list-group-item justify-content-between">
	            		Total
	            		<span class="font-weight-bold">LKR {{ number_format($billing->total_payable, 2) }}</span>
	            	</li>
	            </ul>
	          </div>
	        </div>
		</div>
	</div>
	
@endsection

