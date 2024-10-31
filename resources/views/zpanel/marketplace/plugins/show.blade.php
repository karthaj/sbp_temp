@extends('layouts.zpanel')

@section('styles')

<link href="{{ asset('assets/plugins/slick/slick.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/slick/slick-theme.css') }}" rel="stylesheet" type="text/css"/>

<style>
  
  .plugin-gallery-nav .slick-prev:before, .slick-next:before {
    color: #d1d7e0;
  }
</style>

@endsection

@section('content')
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">storefront</li>
  <li class="breadcrumb-item">marketplace</li>
  <li class="breadcrumb-item"><a href="{{ route('marketplace.plugins') }}">plugins</a></li>
  <li class="breadcrumb-item"><a href="{{ route('plugin.show', $plugin['handle']) }}">{{ $plugin['name'] }}</a></li>
</ol>
<!-- END BREADCRUMB --> 

<div class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-block">
        <div class="row justify-content-between mb-5 align-items-center">
          <div class="col-sm-6">
            <div class="media">
              @if($plugin['cover'])
                <img class="d-flex mr-3 img-fluid" src="{{ $plugin['cover'] }}" height="96" width="96" alt="{{ $plugin['name'] }}">
              @else
                <img class="d-flex mr-3 img-fluid" src="https://via.placeholder.com/96" height="96" width="96" alt="{{ $plugin['name'] }}">
              @endif
              <div class="media-body">
                <h1 class="mt-0">{{ $plugin['name'] }}</h1>
                <p>by <a href="{{ $plugin['author_url'] }}">{{ $plugin['author_name'] }}</a></p>
                 @if($plugin['price'] > 0)
                  <span class="badge badge-info">LKR {{ number_format($plugin['price'], 2) }}</span>
                 @else
                  <span class="badge badge-info">Free</span>
                 @endif
              </div>
            </div>
            <div class="mt-5">
              @if(!$plugin['eligible'])
                <p>Upgrade required, this plugin is available for below plan(s)</p>
                @foreach($plugin['plans'] as $plan)
                  <a href="{{ route('plan.change.show', $plan['handle']) }}" class="btn btn-info btn-xs">{{ $plan['name'] }}</a>
                @endforeach
              @endif
            </div>
          </div>
          <div class="col-sm-2">
            @if(!$plugin['installed'] && $plugin['eligible'])
            <form action="{{ route('plugin.purchase') }}" method="post">
              {{ csrf_field() }}   
              <input type="hidden" name="plugin_id" value="{{ $plugin['id'] }}">
              <button class="btn btn-info btn-block" type="submit">Buy</button>
            </form>
            @elseif($plugin['installed'])
            <span class="badge badge-info p-2">Installed</span>
            @endif
          </div>
        </div>
        <div class="card">
          <div class="card-block pl-5">
            <div class="row">
              <div class="col-sm-12">
                <h4>Description</h4>
                <p>{{ $plugin['description'] }}</p>
              </div>
            </div> 
            <div class="row">
              <div class="col-sm-8">
                @if($plugin['screenshots'])
                  <h4>Screenshots</h4>
                  <div class="plugin-gallery mb-5">
                    @foreach($plugin['screenshots'] as $screenshot)
                      <div>
                        <img src="{{ $screenshot }}" class="img-fluid" alt="{{ $plugin['name'] }}">
                      </div>
                    @endforeach
                  </div>
                  <div class="plugin-gallery-nav">
                    @foreach($plugin['screenshots'] as $screenshot)
                      <div>
                        <img src="{{ $screenshot }}" class="img-fluid" alt="{{ $plugin['name'] }}" width="180">
                      </div>
                    @endforeach
                  </div>
                @endif
              </dic>
            </div> 
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page_scripts')

<script type="text/javascript" src="{{ asset('assets/plugins/slick/slick.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function(){
      $('.plugin-gallery').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.plugin-gallery-nav',
      });
      $('.plugin-gallery-nav').slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        asNavFor: '.plugin-gallery',
        centerMode: true,
        focusOnSelect: true
      });
    });
</script>

@endsection
