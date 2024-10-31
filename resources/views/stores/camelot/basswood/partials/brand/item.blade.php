@php
	$brand = array_where($brands, function ($brand) use ($handle) {
	                return $brand['handle'] === $handle;
	            });

	if(count($brand)) {
	  $brand = head($brand);
	}
@endphp

@if(count($brand))
	<div class="single-brand">
	  <a href="{{ $brand['url'] }}">
	    @if($brand['image']['medium'])
	        <img src="{{ $brand['image']['medium'] }}" alt="{{ $brand['name'] }}" style="width: 170px">
	    @else
	        <img src="https://via.placeholder.com/195x110/c4c6c8/f2f2f2?text=Image" alt="{{ $brand['name'] }}">
	    @endif
	  </a>
	</div>
@else
	<div class="single-brand">
	  <a href="#">
	   <img src="https://via.placeholder.com/195x110/c4c6c8/f2f2f2?text=Image" alt="Demo">
	  </a>
	</div>
@endif