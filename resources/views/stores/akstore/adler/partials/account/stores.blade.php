<div class="tab-pane {{ request()->tab === 'store_list' ? 'active' : '' }}">

	@if(session()->has('success'))
	<div class="row">
		<div class="col">
			<div class="alert alert-info" role="alert">{!! session('success') !!}</div>
		</div>
	</div>
	@endif

	@if(auth()->user()->stores->count())
	<div class="row">
		<div class="col-12 card-group">
			@foreach(auth()->user()->stores as $store)
			<div class="col-sm-2">
				<div class="card">
				    <img class="card-img-top img-fluid" src="{{ $store->setting->logo ?: '//placehold.it/230?text=No Image' }}" alt="Card image cap">
				    <div class="card-block">
				      <h6 class="card-title font-weight-bold">{{ $store->store_name }}</h6>
				      @if($store->credits()->where('customer_id', auth()->user()->id)->count())
				      <small class="card-text">Store Credits: {{ number_format($store->credits()->where('customer_id', auth()->user()->id)->sum('balance'), 2) }}</small>
				      @else
				      <p class="card-text"></p>
				      @endif
				    </div>
				    <div class="card-footer">
				      <a href="{{ route('customer.account.unsubscribe', $store) }}">Unsubscribe</a>
				    </div>
				</div>
			</div>
			@endforeach
		</div>
	</div>
	@else
	<div class="row">
		<div class="col">
			<div class="alert alert-info" role="alert">
		    	You haven't subscribe to any stores.
		  	</div>
		</div>
	</div>
	@endif
</div>