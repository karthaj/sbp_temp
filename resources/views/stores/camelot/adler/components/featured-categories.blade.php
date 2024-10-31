@php
use Shopbox\Transformers\StoreFront\CategoryCollectionTransformer;

$block1 = $block2 = $block3 = $block4 = [];
$categories = $store->categories()->where('is_root_category', 0)->where('status', 1)->get();

if($categories->where('slug', $section['settings']['ft_cat_1'])->count()) {
	$block1 = fractal()
        ->item($categories->where('slug', $section['settings']['ft_cat_1'])->first())
        ->transformWith(new CategoryCollectionTransformer)
        ->toArray()['data'];
}

if($categories->where('slug', $section['settings']['ft_cat_2'])->count()) {
	$block2 = fractal()
        ->item($categories->where('slug', $section['settings']['ft_cat_2'])->first())
        ->transformWith(new CategoryCollectionTransformer)
        ->toArray()['data'];
}

if($categories->where('slug', $section['settings']['ft_cat_3'])->count()) {
	$block3 = fractal()
        ->item($categories->where('slug', $section['settings']['ft_cat_3'])->first())
        ->transformWith(new CategoryCollectionTransformer)
        ->toArray()['data'];
}

if($categories->where('slug', $section['settings']['ft_cat_4'])->count()) {
	$block4 = fractal()
        ->item($categories->where('slug', $section['settings']['ft_cat_4'])->first())
        ->transformWith(new CategoryCollectionTransformer)
        ->toArray()['data'];
}

@endphp
@if($section['disabled'] === false)
<div id="shopbox-section-{{ $section_id }}">
	<section data-section-id="{{ $section_id }}" class="mb-5"> 
	  <div class="b-section_title text-center">
	  	@if($section['settings']['ft_cat_sub_heading'])
	  		<span style="color: {{  $section['settings']['ft_cat_sub_heading_color'] }}">{{ $section['settings']['ft_cat_sub_heading'] }}</span>
	  	@endif
	    @if($section['settings']['title'])
	    	<h4 class="text-uppercase">
		      {{ $section['settings']['title'] }}
		      <span class="b-title_separator"><span></span></span>
		     </h4>
	    @endif
	    @if($section['settings']['ft_cat_text'])
	    	<p class="b-section_text" style="color: {{ $section['settings']['ft_cat_text_color'] }}">{{ $section['settings']['ft_cat_text'] }}</p>
	    @endif
	  </div>
	  <div class="b-featured_cat">
	    <div class="container d-none d-sm-block">
	      <div class="row">
	        <div class="col-xl-6 col-lg-6 col-mb-6 col-sm-6 col-xs-12">
	          <div class="b-featured_cat_in">
	            <a href="{{ count($block1) ? $block1['url'] : '#' }}">
	            	@if($section['settings']['ft_cat_1_img'])
	            		<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_1_img']) }}" class="img-fluid" alt="{{ count($block1) > 0 ? $block1['name'] : 'category image'}}">
	            	@else
	            		<img src="https://via.placeholder.com/540x540?text=Category" class="img-fluid" alt="category image">
	            	@endif
	            </a>
	            @if($section['settings']['ft_cat_show_category_label'])
		            <div class="b-cat_mask">
		              <a href="{{ count($block1) ? $block1['url'] : '#' }}" class="category-link-overlay">{{ count($block1) > 0 ? $block1['name'] : 'category' }}</a> 
		            </div>
	            @endif
	          </div>
	        </div>

	        <div class="col-xl-3 col-lg-3 col-mb-3 col-sm-3 col-xs-12">
	          <div class="b-featured_cat_in mb-4">
	            <a href="{{ count($block2) ? $block2['url'] : '#' }}">
	            	@if($section['settings']['ft_cat_2_img'])
	            		<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_2_img']) }}" class="img-fluid" alt="{{ count($block2) > 0 ? $block2['name'] : 'category image'}}">
	            	@else
	            		<img src="https://via.placeholder.com/255x255?text=Category" class="img-fluid" alt="category image">
	            	@endif
	            </a>
	            @if($section['settings']['ft_cat_show_category_label'])
		            <div class="b-cat_mask">
		              <a href="{{ count($block2) ? $block2['url'] : '#' }}" class="category-link-overlay">{{ count($block2) > 0 ? $block2['name'] : 'category' }}</a> 
		            </div>
		        @endif
	          </div>

	          <div class="b-featured_cat_in">
	            <a href="{{ count($block3) ? $block3['url'] : '#' }}">
	            	@if($section['settings']['ft_cat_3_img'])
	            		<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_3_img']) }}" class="img-fluid" alt="{{ count($block3) > 0 ? $block3['name'] : 'category image'}}">
	            	@else
	            		<img src="https://via.placeholder.com/255x255?text=Category" class="img-fluid" alt="category image">
	            	@endif
	            </a>
	            @if($section['settings']['ft_cat_show_category_label'])
		            <div class="b-cat_mask">
		              <a href="{{ count($block3) ? $block3['url'] : '#' }}" class="category-link-overlay">{{ count($block3) > 0 ? $block3['name'] : 'category' }}</a> 
		            </div>
		        @endif
	          </div>

	        </div> 
	        <div class="col-xl-3 col-lg-3 col-mb-3 col-sm-3 col-xs-12">
	          <div class="b-featured_cat_in">
	            <a href="{{ count($block4) ? $block4['url'] : '#' }}">
	            	@if($section['settings']['ft_cat_4_img'])
	            		<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_4_img']) }}" class="img-fluid" alt="{{ count($block4) > 0 ? $block4['name'] : 'category image'}}">
	            	@else
	            		<img src="https://via.placeholder.com/263x555?text=Category" class="img-fluid" alt="category image">
	            	@endif
	            </a>
	            @if($section['settings']['ft_cat_show_category_label'])
		            <div class="b-cat_mask">
		              <a href="{{ count($block4) ? $block4['url'] : '#' }}" class="category-link-overlay">{{ count($block4) > 0 ? $block4['name'] : 'category' }}</a> 
		            </div>
		        @endif
	          </div>
	        </div>
	      </div>
	    </div>

	    <div class="container d-block d-sm-none">
	      <div class="row1 text-center">
	      	<div class="col-xs-12">
	      		<div class="b-featured_cat_in mb-4">
		            <a href="{{ count($block1) ? $block1['url'] : '#' }}">
		            	@if($section['settings']['ft_cat_1_img'])
		            		<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_1_img']) }}" class="img-fluid" alt="{{ count($block1) > 0 ? $block1['name'] : 'category image'}}">
		            	@else
		            		<img src="https://via.placeholder.com/540x540?text=Category" class="img-fluid" alt="category image">
		            	@endif
		            </a>
		            @if($section['settings']['ft_cat_show_category_label'])
			            <div class="b-cat_mask">
			              <a href="{{ count($block1) ? $block1['url'] : '#' }}" class="category-link-overlay">{{ count($block1) > 0 ? $block1['name'] : 'category' }}</a> 
			            </div>
		            @endif
		         </div>
	      	</div>

	      	<div class="col-xs-12">
	      		<div class="b-featured_cat_in mb-4">
		            <a href="{{ count($block2) ? $block2['url'] : '#' }}">
		            	@if($section['settings']['ft_cat_2_img'])
		            		<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_2_img']) }}" class="img-fluid" alt="{{ count($block2) > 0 ? $block2['name'] : 'category image'}}">
		            	@else
		            		<img src="https://via.placeholder.com/255x255?text=Category" class="img-fluid" alt="category image">
		            	@endif
		            </a>
		            @if($section['settings']['ft_cat_show_category_label'])
			            <div class="b-cat_mask">
			              <a href="{{ count($block2) ? $block2['url'] : '#' }}" class="category-link-overlay">{{ count($block2) > 0 ? $block2['name'] : 'category' }}</a> 
			            </div>
			        @endif
	          </div>
	      	</div>

	      	<div class="col-xs-12">
	      		<div class="b-featured_cat_in mb-4">
		            <a href="{{ count($block3) ? $block3['url'] : '#' }}">
		            	@if($section['settings']['ft_cat_3_img'])
		            		<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_3_img']) }}" class="img-fluid" alt="{{ count($block3) > 0 ? $block3['name'] : 'category image'}}">
		            	@else
		            		<img src="https://via.placeholder.com/255x255?text=Category" class="img-fluid" alt="category image">
		            	@endif
		            </a>
		            @if($section['settings']['ft_cat_show_category_label'])
			            <div class="b-cat_mask">
			              <a href="{{ count($block3) ? $block3['url'] : '#' }}" class="category-link-overlay">{{ count($block3) > 0 ? $block3['name'] : 'category' }}</a> 
			            </div>
			        @endif
	          	</div>
	      	</div>
	        <div class="col-xs-12">
	        	<div class="b-featured_cat_in mb-4">
		            <a href="{{ count($block4) ? $block4['url'] : '#' }}">
		            	@if($section['settings']['ft_cat_4_img'])
		            		<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_4_img']) }}" class="img-fluid" alt="{{ count($block4) > 0 ? $block4['name'] : 'category image'}}">
		            	@else
		            		<img src="https://via.placeholder.com/263x555?text=Category" class="img-fluid" alt="category image">
		            	@endif
		            </a>
		            @if($section['settings']['ft_cat_show_category_label'])
			            <div class="b-cat_mask">
			              <a href="{{ count($block4) ? $block4['url'] : '#' }}" class="category-link-overlay">{{ count($block4) > 0 ? $block4['name'] : 'category' }}</a> 
			            </div>
			        @endif
         		</div>
	        </div>
	      </div>
	    </div>
	  </div>
	</section>  
</div>
@endif

@php
	$settings = [
	  "name" =>  "Featured Categories",
	  "section" =>  $section_id, 
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
		    "id" => "ft_cat_sub_heading",
		    "label" => "Sub heading"
	    ],
	    [
		    "type" => "color",
		    "id" => "ft_cat_sub_heading_color",
		    "label" => "Sub heading text color"
	    ],
	    [
		    "type" => "text",
		    "id" => "ft_cat_text",
		    "label" => "Paragraph"
	    ],
	    [
		    "type" => "color",
		    "id" => "ft_cat_text_color",
		    "label" => "Paragraph text color",
		    "divider" => "true"
	    ],
	    [
		    "id" => "ft_cat_1",
		    "type" => "category",
		    "label" => "Featured category 1"
	    ],
	    [
	      	"type" => "image_picker",
	      	"id" => "ft_cat_1_img",
	      	"label" => "Image",
	      	"info" => "Recommended dimension 540x540"
	    ],
	    [
		    "id" => "ft_cat_2",
		    "type" => "category",
		    "label" => "Featured category 2"
	    ],
	    [
	      "type" => "image_picker",
	      "id" => "ft_cat_2_img",
	      "label" => "Image",
	      "info" => "Recommended dimension 255x255"
	    ],
	    [
		    "id" => "ft_cat_3",
		    "type" => "category",
		    "label" => "Featured category 3"
	    ],
	    [
	      	"type" => "image_picker",
	      	"id" => "ft_cat_3_img",
	      	"label" => "Image",
	      	"info" => "Recommended dimension 255x255"
	    ],
	    [
		    "id" => "ft_cat_4",
		    "type" => "category",
		    "label" => "Featured category 4"
	    ],
	    [
	      	"type" => "image_picker",
	      	"id" => "ft_cat_4_img",
	      	"label" => "Image",
	      	"info" => "Recommended dimension 263x555"
	    ],
	    [
	        "type" => "checkbox",
	        "id" => "ft_cat_show_category_label",
	        "label" => "Show category label"
	    ]
	  ]
	];

	session()->push('schema', $settings);

@endphp