@extends($theme_path.'.layouts.theme')

@section('meta')

<title>Search &ndash; {{ $store->formattedStoreTitle }}</title>
<meta name="description" content="{{ $store->setting->meta_description }}">
<meta name="keywords" content="{{ $store->setting->meta_keywords }}">
<meta property="og:site_name" content="{{ $store->store_name }}">
<meta property="og:url" content="{{ getStoreUrl($store).'/search' }}">
<meta property="og:title" content="Search">
<meta property="og:type" content="website">
<meta property="og:description" content="{{ $store->setting->meta_description ?: $store->store_name.' store powered by ShopBox.' }}"/>
@if($store->setting->logo)
<meta property="og:image" content="{{ asset('stores').'/'.$store->domain.'/img/'.$store->setting->logo }}"/>
<meta property="og:image:alt" content="{{ $store->store_name }}"/>
@endif
<meta name="twitter:site" content="{{ '@'.$store->store_name }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Search">
<meta property="twitter:description" content="{{ $store->setting->meta_description ?: $store->store_name.' store powered by ShopBox.' }}"/>
@if($store->setting->logo)
<meta property="twitter:image" content="{{ asset('stores').'/'.$store->domain.'/img/'.$store->setting->logo }}"/>
<meta property="twitter:image:alt" content="{{ $store->store_name }}"/>
@endif

@endsection

@section('content')

  <div class="container margin_30">
    <div class="page_header">
      <div class="breadcrumbs">
        <ul>
          <li><a href="/">Home</a></li>
          <li>Search</li>
        </ul>
      </div>
      @if(request()->has('q'))
        <div class="d-flex justify-content-center"><h1>{{ $pagination['count'] }} {{ $pagination['count'] > 1 ? 'results' : 'result' }} for "{{ request()->q }}"</h1></div>
      @endif
    </div>
    @if(count($data))
      <div class="container margin_30">
        <div class="row small-gutters">
          @foreach($data as $product)
            <div class="col-6 col-md-4 col-xl-3">
              @include($theme_path.'.partials.product._grid_item')
            </div>
          @endforeach
        </div>
        @if($pagination['total'] > 15)
          <div class="pagination__wrapper">
            <ul class="pagination">
              <li><a href="{{ array_key_exists('previous',$pagination['links']) ? $pagination['links']['previous'] : '#0' }}" class="prev{{ !array_key_exists('previous',$pagination['links']) ? ' disabled' : ''}}" title="previous page">&#10094;</a></li>
              
              @for($i = 1; $i <= $pagination['total_pages']; $i++)
                <li>
                  <a href="#0" @if($pagination['current_page'] === $i) class="active" @endif>{{ $i }}</a>
                </li>
              @endfor

              <li><a href="{{ array_key_exists('next',$pagination['links']) ? $pagination['links']['next'] : '#0' }}" class="next{{ !array_key_exists('next',$pagination['links']) ? ' disabled' : ''}}" title="next page">&#10095;</a></li>
            </ul>
          </div>
        @endif
      </div>
    @endif
  </div>

@endsection