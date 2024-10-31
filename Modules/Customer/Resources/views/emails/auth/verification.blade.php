@component('mail::message')

#Verify your account

Hi {{ title_case($customer->firstname) }}, you've created a new customer account at {{ $store->store_name }}. All you have to do is activate it and choose a password.

@component('mail::button', ['url' => url(getStoreUrl($store).'/verification/'.$token)])
	Verify 
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
