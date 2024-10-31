<div class="header-top-menu text-right">
    <ul>
        <li class="drodown-show"><a href="#">My account <i class="zmdi zmdi-chevron-down"></i></a>
            <ul class="ht-dropdown">
            	@if(auth()->guard('web')->check())
        		 	<li><a href="/account">Dashboard</a></li>
                	<li>
                		<a href="/logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Sign Out</a>
                		<form id="logout-form" action="/logout" method="POST" style="display: none;"></form>
                	</li>
            	@else
            		<li><a href="/login">Login</a></li>
                	<li><a href="/register">Register</a></li>
            	@endif               
            </ul>
        </li>
        @if($settings['show_multiple_currencies'])
     	@php
	        $currencies = array_filter(explode(',', $settings['supported_currencies']));
      	@endphp
        <li class="drodown-show currency"><a href="#" class="current-currency">{{ request()->cookie('currency') }} <i class="zmdi zmdi-chevron-down"></i></a>
            <ul class="ht-dropdown">
            	<li data-currency="{{ $store->setting->currency->iso_code }}"><a href="#">{{ $store->setting->currency->iso_code }}</a></li>
            	@foreach($currencies as $currency)
                	<li data-currency="{{ $currency }}"><a href="#">{{ $currency }}</a></li>
                @endforeach
            </ul>
        </li>
        @endif
    </ul>
</div>