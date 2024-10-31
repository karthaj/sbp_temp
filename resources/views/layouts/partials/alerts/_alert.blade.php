@if(session()->has('success'))

	@component('layouts.partials.alerts._alert_components', ['type' => 'success'])
	
		{{ session('success') }}

	@endcomponent

@endif

@if(session()->has('error'))
	
	@component('layouts.partials.alerts._alert_components', ['type' => 'danger'])
	
		{{ session('error') }}

	@endcomponent

@endif