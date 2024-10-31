@if(count($payments))
	<div class="payment-icons">
		@foreach($payments as $payment)
			<span class="mr-2"><img class="img-fluid" src="{{ $payment }}"></span>
		@endforeach
	</div>
@endif
