<div class="shoppingcart-search-item float-right">
  	<ul>
      	<li><a href="#"><i class="zmdi zmdi-menu"></i></a>
	      	<ul class="currency-dropdown">
				@if($settings['show_multiple_currencies'])
					@php
				        $currencies = array_filter(explode(',', $settings['supported_currencies']));
			      	@endphp
	              	<li class="currency"><a href="#" class="current-currency">{{ request()->cookie('currency') }} <i class="fa fa-angle-down"></i></a>
	                  	<ul>
	                  		<li data-currency="{{ $store->setting->currency->iso_code }}"><a href="#">{{ $store->setting->currency->iso_code }}</a></li>
	                  		@foreach($currencies as $currency)
	                      		<li data-currency="{{ $currency }}"><a href="#">{{ $currency }}</a></li>
	                      	@endforeach
	                  	</ul>
	              	</li>
	            @endif
	          	<li><a href="#">My account <i class="fa fa-angle-down"></i></a>
	                <ul>
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
	      	</ul>
      	</li>
      	<li><a class="sidebar-trigger-search" href="#"><i class="zmdi zmdi-search"></i></a></li>
      	<li><a href="#"><i class="zmdi zmdi-shopping-cart-plus"></i> <span class="item-total bigcounter">0</span></a>
	          <mini-cart></mini-cart>
      	</li>
  	</ul>
</div>