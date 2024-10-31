@component('mail::message')

Please verify your account


@component('mail::button', ['url' => url($store_url.'/verification/'.$token)])
	Verify 
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
