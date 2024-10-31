@extends('layouts.zpanel')

@section('content')
	
<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg">
<!-- BEGIN PlACE PAGE CONTENT HERE -->
	<h1 class="semi-bold text-center my-5">Choose a plan for your store.</h1>
	@if($plans->count())
	<div class="card-deck">
		@foreach($plans as $plan)
		  <div class="card text-center">
		    <div class="card-block">
			      <h3 class="font-weight-bold">{{ strtoupper($plan->name) }}</h3>
			      <p>LKR <strong>{{ number_format($plan->monthly, 2) }}</strong>/month</p>
			      <p class="card-text font-weight-bold mb-0">Transaction rate</p>
			      <p class="mb-2">{{ $plan->tdr_rate }}%</p>
			      <p class="card-text font-weight-bold mb-0">Products</p>
			      <p class="mb-2">{{ $plan->products_limit ? 'Up to '.$plan->products_limit: 'unlimited' }}</p>
			      <p class="card-text font-weight-bold mb-0">Staff accounts</p>
			      <p class="mb-2">{{ $plan->accounts_limit }}</p>
			      @if(session('store')->plan_id !== $plan->id)
			      <hr>
				      @if(session('store')->plan->slug === 'trial')
				      <form action="{{ route('plan.change.store', $plan) }}" method="post">
				      	{{ csrf_field() }}   
					      <div class="form-group row align-items-center">
					      	<label for="duration" class="control-label col-md-4">Duration</label>
						      <select name="duration" id="duration" class="form-control col-md-8">
					      		@if($plan->slug === 'member')
					      		<option value="12" selected>12 months - {{ number_format($plan->yearly, 2) }}</option>
					      		<option value="6">6 months - {{ number_format($plan->half_monthly ,2) }}</option>
					      		<option value="3">3 months - {{ number_format($plan->quaterly, 2) }}</option>
					      		<option value="1">1 month - {{ number_format($plan->monthly, 2) }}</option>
					      		@elseif($plan->slug === 'entrepreneur')
					      		<option value="12" selected>12 months - {{ number_format($plan->yearly ,2) }}</option>
					      		<option value="6">6 months - {{ number_format($plan->half_monthly ,2) }}</option>
					      		<option value="3">3 months - {{ number_format($plan->quaterly, 2) }}</option>
					      		<option value="1">1 month - {{ number_format($plan->monthly, 2) }}</option>
					      		@elseif($plan->slug === 'sme')
					      		<option value="12" selected>12 months - {{ number_format($plan->yearly, 2) }}</option>
					      		<option value="6">6 months - {{ number_format($plan->half_monthly, 2) }}</option>
					      		<option value="3">3 months - {{ number_format($plan->quaterly, 2) }}</option>
					      		<option value="1">1 month - {{ number_format($plan->monthly, 2) }}</option>
					      		@endif
				      	</select>
				      	</div>
					    <button class="btn btn-action-save" type="submit">Select plan</button>
					</form>
			      	@else
				      <p class="mt-3">
			      		<a href="{{ route('plan.change.show', $plan) }}" class="btn btn-action-add btn-cons">Select plan</a> 	
				      </p>
				    @endif
			     @else
					<hr>
			      <p class="mt-3">
		      		Current Plan 	
			      </p>
				  @endif
		    </div>
		  </div>
		@endforeach
	</div>
	@endif
<!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->
	

@endsection

