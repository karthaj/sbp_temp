@extends('layouts.zpanel-checkout')

@section('content')

	<h1 class="semi-bold text-center my-5">Choose a plan to continue.</h1>
	@if($plans->count())
	<div class="card-deck">
		@foreach($plans as $plan)
		  <div class="card text-center">
		    <div class="card-block">
		    	<form action="{{ route('plan.store') }}" method="post">
		    	  {{ csrf_field() }}
			      <h5 class="card-title font-weight-bold">{{ title_case($plan->name) }}</h5>
			      <p>LKR <strong>{{ $plan->monthly }}</strong>/month</p>
			      <p class="card-text font-weight-bold">Transaction rate</p>
			      <span>{{ $plan->tdr_rate }}%</span>
			      <p class="card-text font-weight-bold">Products</p>
			      <span>{{ $plan->products_limit ?: 'unlimited' }}</span>
			      <p class="card-text font-weight-bold">Staff accounts</p>
			      <span>{{ $plan->accounts_limit }}</span>
			      <hr>
			      <div class="form-group">
			      	<select name="period" id="{{ $plan->slug }}_period" class="form-control" data-plan="{{ $plan->slug }}">
			      		@if($plan->slug === 'member')
			      		<option value="{{ $plan->yearly }}|12" selected>12 months</option>
			      		<option value="{{ $plan->half_monthly }}|6">6 months</option>
			      		<option value="{{ $plan->quaterly }}|3">3 months</option>
			      		<option value="{{ $plan->monthly }}|1">1 month</option>
			      		@elseif($plan->slug === 'entrepreneur')
			      		<option value="{{ $plan->yearly }}|12" selected>12 months</option>
			      		<option value="{{ $plan->half_monthly }}|6">6 months</option>
			      		<option value="{{ $plan->quaterly }}|3">3 months</option>
			      		<option value="{{ $plan->monthly }}|1">1 month</option>
			      		@elseif($plan->slug === 'sme')
			      		<option value="{{ $plan->yearly }}|12" selected>12 months</option>
			      		<option value="{{ $plan->half_monthly }}|6">6 months</option>
			      		<option value="{{ $plan->quaterly }}|3">3 months</option>
			      		<option value="{{ $plan->monthly }}|1">1 month</option>
			      		@endif
			      	</select>
			      </div>
			      <hr>
			      <p class="mt-3">
			      	@if($plan->slug === 'member')
			      		<input type="hidden" name="plan" value="{{ $plan->id }}">
			      		<button type="submit" class="btn btn-action-add btn-cons">LKR <span id="{{ $plan->slug }}_cost">{{ number_format($plan->yearly, 2) }}</span> <br>Pay now</button>
			      	@elseif($plan->slug === 'entrepreneur')
			      		<input type="hidden" name="plan" value="{{ $plan->id }}">
			      		<button type="submit" class="btn btn-action-add btn-cons">LKR <span id="{{ $plan->slug }}_cost">{{ number_format($plan->yearly, 2) }}</span> <br>Pay now</button>
			      	@elseif($plan->slug === 'sme')
			      		<input type="hidden" name="plan" value="{{ $plan->id }}">
			      		<button type="submit" class="btn btn-action-add btn-cons">LKR <span id="{{ $plan->slug }}_cost">{{ number_format($plan->yearly, 2) }}</span> <br>Pay now</button>
			      	@endif
			      	
			      </p>
		  		</form>
		    </div>
		  </div>
		@endforeach
	</div>
	@endif

@endsection

@section('page_scripts')

<script>
	Shopbox.planSubscription();
</script>

@endsection
