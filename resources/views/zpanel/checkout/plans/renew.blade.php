@extends('layouts.zpanel-checkout')

@section('content')
	
<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg">
<!-- BEGIN PlACE PAGE CONTENT HERE -->
	<h1 class="semi-bold text-center my-5">Renew plan for your store.</h1>
	<div class="row justify-content-center">
		<div class="col-md-4">
			<div class="card-deck">
				<div class="card text-center">
				    <div class="card-block">
					    <h5 class="card-title font-weight-bold">{{ title_case($plan->name) }}</h5>
					    <p>LKR <strong>{{ number_format($plan->monthly, 2) }}</strong>/month</p>
					    <p class="card-text font-weight-bold">Transaction rate</p>
					    <span>{{ $plan->tdr_rate }}%</span>
					    <p class="card-text font-weight-bold">Products</p>
					    <span>{{ $plan->products_limit ?: 'unlimited' }}</span>
					    <p class="card-text font-weight-bold">Staff accounts</p>
					    <span>{{ $plan->accounts_limit }}</span>
					    <hr>
				      	<form action="{{ route('plan.update', $plan) }}" method="post">
					      	{{ csrf_field() }}   
						    <div class="form-group row align-items-center{{ $errors->has('duration') ? ' has-danger' : '' }}">
						      	<label for="duration" class="control-label col-md-4">Duration</label>
						      	<select name="duration" id="duration" class="form-control col-md-8{{ $errors->has('duration') ? ' form-control-danger' : '' }}">
						      		<option value="12" @if($duration === 12) selected @endif>12 months - {{ number_format($plan->yearly, 2) }}</option>
						      		<option value="6" @if($duration === 6) selected @endif>6 months - {{ number_format($plan->half_monthly, 2) }}</option>
						      		<option value="3" @if($duration === 3) selected @endif>3 months - {{ number_format($plan->quaterly, 2) }}</option>
						      		<option value="1" @if($duration === 1) selected @endif>1 month - {{ number_format($plan->monthly, 2) }}</option>
					      		</select>
			      		 		@if($errors->has('duration'))
                                  <div class="form-control-feedback">{{ $errors->first('duration') }}</div>
                              	@endif
					      	</div>
					      	<hr>
						    <button class="btn btn-action-save" type="submit">Renew plan</button>
						</form>
				    </div>
			  	</div>
			</div>
		</div>
	</div>
<!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->
	

@endsection

