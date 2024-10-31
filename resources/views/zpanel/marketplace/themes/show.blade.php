@extends('layouts.zpanel')


@section('content')
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">storefront</li>
  <li class="breadcrumb-item">marketplace</li>
  <li class="breadcrumb-item active">{{ $theme['name'] }}</li>
</ol>
<!-- END BREADCRUMB --> 

<div class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-block">
        <div class="row">
          <div class="col-sm-4 pt-5">
            <h1>{{ $theme['name'] }}</h1>
            @if($theme['price'] > 0)
              <p class="mb-3"><span class="badge badge-info">LKR {{ number_format($theme['price'], 2) }}</span></p>
            @else
              <p class="mb-3"><span class="badge badge-info">Free</span></p>
            @endif
            
            <p class="mb-4">{{ $theme['description'] }}</p>

            @if($theme['variation_count'])

              <p class="mb-3"><strong>{{ $theme['name'] }} includes {{ $theme['variation_count'] }} styles</strong></p>

              @foreach($theme['variations'] as $variation)
                <button class="btn btn-default btn-sm btn-rounded"><i class="fa fa-circle mr-2" aria-hidden="true" style="color: {{ $variation['accent'] }}"></i> {{ $variation['name'] }}</button>
              @endforeach

            @endif
            @if(!$theme['purchased'])
              <form action="{{ route('theme.purchase') }}" method="post" class="mt-4">
                {{ csrf_field() }}   
                <input type="hidden" name="theme_id" value="{{ $theme['theme_id'] }}">
                <button class="btn btn-info" type="submit">Buy</button>
              </form>
            @else
              <p><a href="{{ route('theme.index') }}" class="btn btn-info btn-xs mt-4">View in my themes</a></p>
            @endif
          </div>
          <div class="col-sm-8">
            <div class="scrollable">
              <div style="height: 400px;">
                  @if($theme['desktop_screenshot'])
                    <img src="{{ $theme['desktop_screenshot'] }}" class="img-fluid" alt="{{ $theme['name'] }}">
                  @else
                    <img src="https://via.placeholder.com/800x400" class="img-fluid" alt="{{ $theme['name'] }}">
                  @endif
              </div>
            </div>
          </div>
        </div>
        <p class="mt-5">Developed and supported by <a href="{{ $theme['author_url'] }}" target="_blank">{{ $theme['author_name'] }}</a></p>
        <hr>
        <div class="row">
          <div class="col-sm-4">
            <h5><span class="semi-bold">Features</span></h5>
            <ul>
              @foreach($theme['features'] as $feature)
                <li>{{ $feature }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
