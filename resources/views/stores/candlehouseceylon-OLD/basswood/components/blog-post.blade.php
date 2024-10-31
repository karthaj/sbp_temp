@php
    use Shopbox\Transformers\StoreFront\BlogTransformer;
@endphp

@if($section['disabled'] === false)
    @php
        $posts  = fractal()
                        ->collection($store->blogs()->where('active', 1)->latest()->limit($section['settings']['post_limit'])->get())
                        ->transformWith(new BlogTransformer)
                        ->toArray()['data'];
    @endphp
    <div id="shopbox-section-{{ $section_id }}" data-section-id="{{ $section_id }}" data-section-type="blog-post" class="blog-area gray-bg2 mb-85">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="blog-style-2 blog-area-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="section-title text-center mb-50">
                                    @if($section['settings']['title'])
                                        <h2>{{ $section['settings']['title'] }}</h2>
                                    @endif
                                    @if($section['settings']['subtitle'])
                                        <p>{{ $section['settings']['subtitle'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if(count($posts))
                            <div class="row">
                                <div class="blog-slider-wrapper-2">
                                    <div class="blog-slider-1 owl-carousel">
                                        @foreach($posts as $post)
                                        <div class="col-12">
                                            <div class="blog-item-group">
                                                <div class="single-blog blog-style-2 mb-30">
                                                    <div class="blog-info">
                                                        <div class="blog-date-time">
                                                            <span class="blog-month">{{ $post['date']->format('F') }}</span>
                                                            <span class="blog-date">{{ $post['date']->format('d') }}</span>
                                                        </div>
                                                        <div class="single-blog-content">
                                                            <div class="blog-title">
                                                               <h4><a href="{{ $post['url'] }}">{{ $post['title'] }}</a></h4>
                                                            </div>
                                                            <div class="blog-meta">
                                                                <ul>
                                                                    <li><i class="fa fa-calendar"></i>{{ $post['date']->toFormattedDateString() }}</li>
                                                                    <li>-</li>
                                                                    <li><i class="fa fa-user"></i>{{ $post['author'] }}</li>
                                                                </ul>
                                                            </div>
                                                            <p class="blog-description">{!! $post['content'] !!}</p>
                                                            <a class="read-btn" href="{{ $post['url'] }}"> Read More </a>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="blog-slider-wrapper-2">
                                    <div id="#BlogPostSlider-{{ $section_id }}" class="blog-slider-1 owl-carousel">
                                        @for($i = 0; $i < $section['settings']['post_limit']; $i++)
                                            <div class="col-12">
                                                <div class="blog-item-group">
                                                    <div class="single-blog blog-style-2 mb-30">
                                                        <div class="blog-info">
                                                            <div class="blog-date-time">
                                                                <span class="blog-month">{{ date('F') }}</span>
                                                                <span class="blog-date">{{ date('d') }}</span>
                                                            </div>
                                                            <div class="single-blog-content">
                                                                <div class="blog-title">
                                                                   <h4><a href="#">Lorem ipsum</a></h4>
                                                                </div>
                                                                <div class="blog-meta">
                                                                    <ul>
                                                                        <li><i class="fa fa-calendar"></i>{{ date('M d, Y') }}</li>
                                                                        <li>-</li>
                                                                        <li><i class="fa fa-user"></i>Demo</li>
                                                                    </ul>
                                                                </div>
                                                                <p class="blog-description">
                                                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the ...
                                                                </p>
                                                                <a class="read-btn" href="#"> Read More </a>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    @if($section['settings']['image'])
                        <div class="blog-background-image" style="{{ 'background-image:url('.asset('stores/'.$store->domain.'/img/'.$section['settings']['image']).')' }}" style="background-image: {{ $section['settings']['image'] }}"></div>
                    @else
                        <div class="blog-background-image" style="background-image:url(https://via.placeholder.com/885x658/dcdfde/f2f2f2?text=Image)"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@php

    $settings = [
        "name" => "Blog Posts",
        "section" => $section_id, 
        "type" => "content_for_index",
        "disabled" => false,
        "settings" => [
            [
              "type" => "text",
              "id" => "title",
              "label" => "Title"
            ],
            [
              "type" => "text",
              "id" => "subtitle",
              "label" => "subtitle"
            ],
            [
              "type" => "select",
              "id" => "post_limit",
              "label" => "Posts limit",
              "options" => [
                [
                    "value" => "3", 
                    "label" => "3 posts"
                ],
                [
                    "value" => "6", 
                    "label" => "6 posts"
                ],
                [
                    "value" => "9", 
                    "label" => "9 posts"
                ],
                [
                    "value" => "12", 
                    "label" => "12 posts"
                ]
              ]
            ],
            [
              "type" => "image_picker",
              "id" => "image",
              "label" => "Image"
            ]
        ]
    ];

    session()->push('schema', $settings);

@endphp