@extends('layouts.zpanel')

@section('content')

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg">
<!-- BEGIN PlACE PAGE CONTENT HERE -->
	<h1 class="semi-bold text-center my-5">Plan Change Checklist.</h1>
	
	@if ($errors->count())
		<div class="alert alert-info bordered" role="alert">
	      <p class="pull-left"><strong>Warning</p>
	      <button class="close" data-dismiss="alert"></button>
	      <div class="clearfix"></div>
	      <ul>
	        @foreach ($errors->all() as $error)
	            <li>{{ $error }}</li>
	        @endforeach
	      </ul>
	    </div>
    @endif

	<div class="card-deck">
	  	<div class="col-md-4">
	  		<div class="card text-center">
			    <div class="card-block">
				      <h5 class="card-title">{{ title_case($current_plan->name) }}</h5>
				      <p>Monthly rate</p>
				      <span class="font-weight-bold">LKR {{ $current_plan->monthly }}</span>
				      <hr>
				      <p class="card-text">Transaction rate</p>
				      <span class="font-weight-bold">{{ $current_plan->tdr_rate }}%</span>
				      <hr>
				      <p class="card-text">Products</p>
				      <span class="font-weight-bold">{{ $current_plan->products_limit ?: 'unlimited' }} ({{ session('store')->products->count() }})</span>
				      <hr>
				      <p class="card-text">Staff accounts</p>
				      <span class="font-weight-bold">{{ $current_plan->accounts_limit }} ({{ session('store')->users->count() }})</span>
				      <hr>
				      <p class="card-text">Abandoned Cart</p>
				      	@if($current_plan->permissions->contains('name', 'edit carts'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      <hr>
				      <p class="card-text">Discounts</p>
				      	@if($current_plan->permissions->contains('name', 'view discounts'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      <hr>
				      <p class="card-text">Returns</p>
				      	@if($current_plan->permissions->contains('name', 'accept returns'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      <hr>
				      <p class="card-text">Stock Management</p>
				      	@if($current_plan->permissions->contains('name', 'add stock requests') && $current_plan->permissions->contains('name', 'add stock transfers') && $current_plan->permissions->contains('name', 'add stock returns'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      <hr>
				      <p class="card-text">Store Location</p>
				      	@if($current_plan->permissions->contains('name', 'add locations'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
			    </div>
			</div>
	  	</div>
	  	<div class="col-md-4">
	  		<div class="card text-center">
			    <div class="card-block">
				      <h5 class="card-title font-weight-bold">Checklist</h5>
				      <p>Monthly rate
					  <i class="aapl-checkmark-circle text-success"></i>
				      </p>
				      	@if($current_plan->monthly < $plan->monthly)
				      		<span class="font-weight-bold text-danger">LKR {{ number_format($plan->monthly - $current_plan->monthly, 2) }}</span>
				      	@else
				      		<span class="font-weight-bold text-success">LKR {{ number_format($plan->monthly - $current_plan->monthly, 2) }}</span>
				      	@endif
				      	@if($current_plan->monthly < $plan->monthly)
				      		<i class="aapl-arrow-up text-danger"></i>
				      	@else
				      		<i class="aapl-arrow-down text-success"></i>
				      	@endif
				  	  <hr>
				      <p class="card-text">Transaction rate
				      	<i class="aapl-checkmark-circle text-success"></i>
				      </p>
				      	@if($current_plan->tdr_rate < $plan->tdr_rate)
				      		<span class="font-weight-bold text-danger">{{ $plan->tdr_rate - $current_plan->tdr_rate }}%</span>
				      	@else
				      		<span class="font-weight-bold text-success">{{ $plan->tdr_rate - $current_plan->tdr_rate }}%</span>
				      	@endif
				      	@if($current_plan->tdr_rate < $plan->tdr_rate)
				      		<i class="aapl-arrow-up text-danger"></i>
				      	@else
				      		<i class="aapl-arrow-down text-success"></i>
				      	@endif
				      <hr>
				      <p class="card-text">Products
						@if(session('store')->products->count() <= $plan->products_limit)
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      </p>
				      	@if(session('store')->products->count() <= $plan->products_limit)
				      		<span class="font-weight-bold text-success">{{ $plan->products_limit - session('store')->products->count() }}</span>
				      	@else
				      		<span class="font-weight-bold text-danger">{{ $plan->products_limit - session('store')->products->count() }}</span>
				      	@endif
				      <hr>
				      <p class="card-text">Staff accounts
						@if(session('store')->users->count() <= $plan->accounts_limit)
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      </p>
				      	@if(session('store')->users->count() <= $plan->accounts_limit)
				      		<span class="font-weight-bold text-success">{{ $plan->accounts_limit - session('store')->users->count()}}</span>
				      	@else
				      		<span class="font-weight-bold text-danger">{{ $plan->accounts_limit - session('store')->users->count() }}</span>
				      	@endif
				      <hr>
				      <p class="card-text">Abandoned Cart</p>
				      	@if($plan->permissions->contains('name', 'edit carts'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      <hr>
				      <p class="card-text">Discounts</p>
				      	@if($plan->permissions->contains('name', 'view discounts'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      <hr>
				      <p class="card-text">Returns</p>
				      	@if($plan->permissions->contains('name', 'accept returns'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      <hr>
				      <p class="card-text">Stock Management</p>
				      	@if($plan->permissions->contains('name', 'add stock requests') && $plan->permissions->contains('name', 'add stock transfers') && $plan->permissions->contains('name', 'add stock returns'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      <hr>
				      <p class="card-text">Store Location</p>
				      	@if($plan->permissions->contains('name', 'add locations'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif			      	
			    </div>
			</div>
	  	</div>
	  	<div class="col-md-4">
	  		<div class="card text-center">
			    <div class="card-block">
				      <h5 class="card-title font-weight-bold">{{ title_case($plan->name) }}</h5>
				      <p>Monthly rate</p>
				      <span class="font-weight-bold">LKR {{ $plan->monthly }}</span>
				      <hr>
				      <p class="card-text">Transaction rate</p>
				      <span class="font-weight-bold">{{ $plan->tdr_rate }}%</span>
				      <hr>
				      <p class="card-text">Products</p>
				      <span class="font-weight-bold">{{ $plan->products_limit ?: 'unlimited' }}</span>
				      <hr>
				      <p class="card-text">Staff accounts</p>
				      <span class="font-weight-bold">{{ $plan->accounts_limit }}</span>
				      <hr>
				      <p class="card-text">Abandoned Cart</p>
				      	@if($plan->permissions->contains('name', 'edit carts'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      <hr>
				      <p class="card-text">Discounts</p>
				      	@if($plan->permissions->contains('name', 'view discounts'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      <hr>
				      <p class="card-text">Returns</p>
				      	@if($plan->permissions->contains('name', 'accept returns'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      <hr>
				      <p class="card-text">Stock Management</p>
				      	@if($plan->permissions->contains('name', 'add stock requests') && $plan->permissions->contains('name', 'add stock transfers') && $plan->permissions->contains('name', 'add stock returns'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
				      <hr>
				      <p class="card-text">Store Location</p>
				      	@if($plan->permissions->contains('name', 'add locations'))
				      		<i class="aapl-checkmark-circle text-success"></i>
				      	@else
				      		<i class="aapl-cross-circle text-danger"></i>
				      	@endif
			    </div>
			</div>
	  	</div>
	  	<form action="{{ route('plan.change.store', $plan) }}" method="post">
			{{ csrf_field() }}   
		    <a href="{{ route('plan.change.index') }}" class="btn btn-action-save mr-2">Back</a>
		    <button class="btn btn-action-save" type="submit" @if($errors->count()) disabled @endif>Accept & Continue</button>
		</form>
	</div>
<!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->
@endsection
