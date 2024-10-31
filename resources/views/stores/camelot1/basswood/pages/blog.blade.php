@extends($theme_path.'.layouts.theme')

@section('meta')

<meta property="og:site_name" content="{{ $store->store_name }}">
<meta property="og:url" content="{{ route('stores.blog', $blog) }}">
<meta property="og:title" content="{{ $blog->meta_title }}">
<meta property="og:type" content="article">
<meta property="og:description" content="{{ strip_tags(html_entity_decode($blog->content)) }}">
@if($blog->featured_image)
<meta property="og:image" content="{{ asset('stores').'/'.$store->domain.'/blog/'.$blog->featured_image }}">
@endif

<meta name="twitter:site" content="{{ '@'.$store->store_name }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $blog->meta_title }}">
<meta name="twitter:description" content="{{ strip_tags(html_entity_decode($blog->content)) }}">
@if($blog->featured_image)
<meta name="twitter:image" content="{{ asset('stores').'/'.$store->domain.'/blog/'.$blog->featured_image }}"/>
@endif

@endsection

@section('breadcrum')
<div class="breadcrumb-area pt-20 pb-20">
     <div class="container">
         <div class="row">
             <div class="col-md-12">
                 <div class="breadcrumb-content">
                     <ul>
                         <li><a href="{{ getStoreUrl($store) }}">Home</a></li>
                         <li><a href="{{ route('stores.blogs') }}">Blogs</a></li>
                         <li class="active"><a href="{{ route('stores.blog', $blog) }}">{{ $blog->title }}</a></li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>
</div>
@endsection

@section('content')
<div class="blog-area pb-50">
    <div class="container">
        <div class="row"> 
            <div class="col-lg-9 ml-auto mr-auto">
                <div class="blog-wrapper blog-details">
                    <div class="blog-img img-full">
                        @if($blog->featured_image)
                            <img src="{{ asset('stores').'/'.session('store')->domain.'/blog/'.$blog->featured_image }}" alt="{{ $blog->title }}">
                        @else
                            <img src="https://via.placeholder.com/1170x724/f2f2f2/dcdfde?text=No Image-1170x724" alt="{{ $blog->title }}">
                        @endif
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <ul>
                                <li><i class="fa fa-calendar"></i>{{ $blog->created_at->toFormattedDateString() }}</li>
                                <li>-</li>
                                <li><i class="fa fa-user"></i>{{ $blog->author }}</li>
                            </ul>
                        </div>
                        <h3>{{ $blog->title }}</h3>
                        {!! html_entity_decode($blog->content) !!}
                    </div>
                </div>
                
                <div class="common-tag-and-next-prev mt-60">
                    
                    <div class="blog-share">
                        <h6>Share:</h6>
                        <ul>
                            <li><a target="_blank" href="//www.facebook.com/sharer.php?u={{ route('stores.blog', $blog) }}">Facebook,</a></li>
                            <li><a target="_blank" href="//twitter.com/share?text={{ rawurlencode($blog->title)  }}&amp;url={{ route('stores.blog', $blog) }}">Twitter,</a></li>
                            @if($blog->featured_image)
                                <li><a target="_blank" href="//pinterest.com/pin/create/button/?url={{ route('stores.blog', $blog) }}&amp;media={{ asset('stores').'/'.session('store')->domain.'/blog/'.$blog->featured_image }}&amp;description={{ rawurlencode($blog->title) }}">Pinterest</a></li>
                            @else
                                <li><a target="_blank" href="//pinterest.com/pin/create/button/?url={{ route('stores.blog', $blog) }}&amp;media=https://via.placeholder.com/1170x724/f2f2f2/dcdfde?text=No Image&amp;description={{ rawurlencode($blog->title) }}">Pinterest</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
                
             </div>
        </div>
    </div>
</div>
@endsection