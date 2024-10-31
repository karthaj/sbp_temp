<div class="col-lg-6">
	<figure>
		@if($section['settings']['image'])
			<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['image']) }}" data-src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['image']) }}" alt="{{ $section['settings']['title'] }}" class="img-fluid lazy">
		@else
			<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/placeholder.jpg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/placeholder.jpg') }}" alt="{{ $section['settings']['title'] }}" class="img-fluid lazy">
		@endif
	</figure>
</div>