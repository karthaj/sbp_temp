<div class="col-md-6">
    @if($section['settings']['image'])
        <img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['image']) }}" alt="custom image" class="img-fluid">
    @else
        <img src="https://via.placeholder.com/825x610/dcdfde/f2f2f2?text=Image" alt="custom image" class="img-fluid">
    @endif
</div>