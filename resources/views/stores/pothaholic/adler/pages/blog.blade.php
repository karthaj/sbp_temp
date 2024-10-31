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

@section('content')

<div class="container">
    <div class="row clearfix">
        <div class="col-12">
            <div class="b-blog_default b-blog_grid b-blog_default_list">
                <div class="b-blog_degault_single">   
                     <div class="b-blog_single_info text-center">
                          <h3 class="b-entry_title">{{ $blog->title }}</h3>
                          <div class="b-author_info pb-1">
                              <span class="b-author_name">
                                  By {{ $blog->author }}
                              </span>
                          </div>
                     </div> 
                     @if($blog->featured_image)       
                        <div class="b-blog_single_header">
                            <div class="b-blog_img_wrap">
                                <img src="http://jthemes.org/html/basel/assets/images/blog/home/blog_default_01.jpg" class="img-fluid" alt="">
                            </div>
                            <div class="b-post_time text-center">
                                <span class="b-post_day">{{ $blog->created_at_tz->format('d') }}</span>
                                <span class="b-post_month">{{ $blog->created_at_tz->format('M') }}</span>
                            </div> 
                        </div> 
                    @endif 
                    <div class="b-blog_single_info"> 
                        <div class="b-blog_single_content text-left pt-3">.
                              {!! html_entity_decode($blog->content) !!}
                            <div class="b-footer_socail b-socail_large b-socail_color mt-5">
                                <ul class="list-unstyled clearfix mb-0">
                                    <li><a href="//www.facebook.com/sharer.php?u={{ route('stores.blog', $blog) }}" target="_blank" data-toggle="tooltip" title="" data-original-title="Facebook" class="fa fa-facebook"></a></li>
                                    <li><a href="//twitter.com/share?text={{ rawurlencode($blog->title)  }}&amp;url={{ route('stores.blog', $blog) }}" data-toggle="tooltip" title="" data-original-title="Twitter" target="_blank" class="fa fa-twitter"></a></li>
                                    <li><a href="//pinterest.com/pin/create/button/?url={{ route('stores.blog', $blog) }}&amp;media=https://via.placeholder.com/1170x724/f2f2f2/dcdfde?text=No Image&amp;description={{ rawurlencode($blog->title) }}" data-toggle="tooltip" title="" data-original-title="Pinterest" target="_blank" class="fa fa-pinterest"></a></li>
                                </ul>
                            </div>
                        </div> 
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

@endsection