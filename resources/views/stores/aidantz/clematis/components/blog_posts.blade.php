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
	<div id="shopbox-section-{{ $section_id }}">
		<div class="container margin_60_35">
			@if($section['settings']['title'] || $section['settings']['sub_title'])
				<div class="main_title">
				@if($section['settings']['title'])
					<h2>{{ $section['settings']['title'] }}</h2>
				@endif
				@if($section['settings']['title2'])
					<span>{{ $section['settings']['title2'] }}</span>
				@endif
				@if($section['settings']['sub_title'])	
					<p>{{ $section['settings']['sub_title'] }}</p>
				@endif
				</div>
			@endif

			@if(count($posts))
				<div class="row">
					@foreach($posts as $post)
					 	<div class="col-lg-6">
							<a class="box_news" href="{{ $post['url'] }}">
								<figure>
									@if($post['featured_image'])
										<img src="{{ $post['featured_image'] }}" data-src="{{ $post['featured_image'] }}" alt="{{ $post['title'] }}" width="400" height="266" class="lazy">
									@else
										<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/blog-thumb-placeholder.jpg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/blog-thumb-placeholder.jpg') }}" alt="Blog placeholder" width="400" height="266" class="lazy">
									@endif
									@if($section['settings']['show_date'])
										<figcaption><strong>{{ $post['created']->format('d') }}</strong>{{ $post['created']->format('M') }}</figcaption>
									@endif
								</figure>
								@if(($section['settings']['show_author'] && $post['author']) || $section['settings']['show_date'])
									<ul>
									@if($section['settings']['show_author'] && $post['author'])
										<li>by {{ title_case($post['author']) }}</li>
									@endif
									@if($section['settings']['show_date'])
										<li>{{ $post['created']->format('d.m.Y') }}</li>
									@endif
									</ul>
								@endif
								<h4>{{ $post['title'] }}</h4>
								<p>{{ str_limit(strip_tags($post['content']), 100) }}</p>
							</a>
						</div>
					 @endforeach
				</div>
			@else
				<div class="row">
					@for($i = 0; $i < $section['settings']['post_limit']; $i++)
						<div class="col-lg-6">
							<a class="box_news" href="#">
								<figure>
									<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/blog-thumb-placeholder.jpg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/blog-thumb-placeholder.jpg') }}" alt="Blog placeholder" width="400" height="266" class="lazy">
									<figcaption><strong>28</strong>Dec</figcaption>
								</figure>
								<ul>
									<li>by John Doe</li>
									<li>20.11.2017</li>
								</ul>
								<h4>Pri oportere scribentur eu</h4>
								<p>Cu eum alia elit, usu in eius appareat, deleniti sapientem honestatis eos ex. In ius esse ullum vidisse....</p>
							</a>
						</div>
					@endfor
				</div>
			@endif
		</div>
	</div>
@endif

@php

    $settings = [
        "name" => "Blog posts",
        "section" => $section_id, 
        "type" => "content_for_index",
        "disabled" => false,
        "settings" => [
            [
              "type" => "text",
              "id" => "title",
              "label" => "Heading"
            ],
            [
              "type" => "text",
              "id" => "title2",
              "label" => "Title 2"
            ],
            [
              "type" => "text",
              "id" => "sub_title",
              "label" => "Subheading"
            ],
        	[
	    		"type" => "select",
	          	"id" => "post_limit",
	          	"label" => "Number of articles",
	          	"options" =>  [
	            	[ "value" => "1", "label" => "1" ],
	            	[ "value" => "2", "label" => "2" ],
	            	[ "value" => "3", "label" => "3" ],
	            	[ "value" => "4", "label" => "4" ]
	          	]
	    	],
            [
              "type" => "checkbox",
              "id" => "show_author",
              "label" => "Show author"
            ],
            [
              "type" => "checkbox",
              "id" => "show_date",
              "label" => "Show date"
            ]
        ]
    ];

    session()->push('schema', $settings);

@endphp