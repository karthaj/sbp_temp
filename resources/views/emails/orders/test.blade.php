@component('mail::message')
# Return Request Confirmation for Order No #100

A summary of your return is shown below.

@component('mail::table')
|                        |                 |
| :----------------------|-----------------|
| **Reason:**            | not satisfied   |
@endcomponent

@component('mail::table')
| Items                                         | Qty           |
| :---------------------------------------------|---------------|
| Dummy product    								| 1     		|
                    		     
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent