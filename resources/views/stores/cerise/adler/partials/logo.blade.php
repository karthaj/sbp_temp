@php

  if($settings['align_logo'] === 'left') {
    $alignment = 'text-left';
  } elseif($settings['align_logo'] === 'center') {
    $alignment = 'text-sm-left text-lg-center text-xl-center';
  }
@endphp
<div class="col-xl-3 col-lg-3 col-mb-4 col-sm-4 col-xs-6">
  <div class="b-logo {{ $alignment }}">
    <a href="{{ getStoreUrl($store) }}" class="d-inline-block">
    @if($settings['logo'])
    	<img src="{{ asset('stores/'.$store->domain.'/img/'.$settings['logo']) }}" class="img-fluid d-block" alt="{{ $store->store_name }}">
    @else
    	<img src="https://via.placeholder.com/231x56?text=Logo" class="img-fluid d-block" alt="{{ $store->store_name }}">
    @endif
    </a>
  </div>
</div>
