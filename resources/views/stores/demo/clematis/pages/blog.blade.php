@extends($theme_path.'.layouts.theme')

@section('meta')

<title>{{ $blog->formattedMetaTitle }} &ndash; {{ $store->formattedStoreTitle }}</title>
<meta name="description" content="{{ strip_tags($blog->meta_description) }}" >
<meta name="keywords" content="{{ $blog->meta_keywords }}" >
<meta property="og:site_name" content="{{ $store->store_name }}">
<meta property="og:url" content="{{ route('stores.blog', $blog) }}">
<meta property="og:title" content="{{ $blog->meta_title }}">
<meta property="og:type" content="article">
<meta property="og:description" content="{{ strip_tags($blog->meta_description) }}">
@if($blog->featured_image)
<meta property="og:image" content="{{ asset('stores').'/'.$store->domain.'/blog/'.$blog->featured_image }}">
@endif

<meta name="twitter:site" content="{{ '@'.$store->store_name }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $blog->meta_title }}">
<meta name="twitter:description" content="{{ strip_tags($blog->meta_description) }}">
@if($blog->featured_image)
<meta name="twitter:image" content="{{ asset('stores').'/'.$store->domain.'/blog/'.$blog->featured_image }}"/>
@endif

@endsection

@section('content')

  <div class="container margin_30">
    <div class="page_header">
      <div class="breadcrumbs">
        <ul>
          <li><a href="/">Home</a></li>
          <li><a href="/blogs">Blogs</a></li>
          <li>{{ $blog->title }}</li>
        </ul>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="singlepost">
          @if($blog->featured_image)
            <figure>
              <img class="img-fluid" src="{{ asset('stores/'.$store->domain.'/blog/'.$blog->featured_image) }}" alt="img-fluid">
            </figure>
          @endif
          <h1>{{ $blog->title }}</h1>
          <div class="postmeta">
            <ul>
              <li><i class="ti-calendar"></i> {{ $blog->created_at_tz->format('d/m/Y') }}</li>
              @if($blog->author)
              <li><i class="ti-user"></i> {{ $blog->author }}</li>
              @endif
            </ul>
          </div>
          <div class="post-content">{!! html_entity_decode($blog->content) !!}</div>
        </div>
        <hr>
        <div class="follow_us text-center">
          <ul>
            <li>
              <a href="//twitter.com/share?text={{ rawurlencode($blog->title)  }}&amp;url={{ route('stores.blog', $blog) }}" target="_blank">
              <img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/twitter_icon.svg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/twitter_icon.svg') }}" alt="Twitter" class="lazy"></a>
            </li>
            <li>
              <a href="//www.facebook.com/sharer.php?u={{ route('stores.blog', $blog) }}" target="_blank">
              <img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/facebook_icon.svg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/facebook_icon.svg') }}" alt="Facebook" class="lazy"></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

@endsection