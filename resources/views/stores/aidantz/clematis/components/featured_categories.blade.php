@php
use Shopbox\Transformers\StoreFront\CategoryCollectionTransformer;

$block1 = $block2 = $block3 = $block4 = [];
$block_1_products = $block_2_products = $block_3_products = $block_4_products = [];
$categories = $store->categories()->where('is_root_category', 0)->where('status', 1)->get();

if($section['disabled'] === false) {
	if($categories->where('slug', $section['settings']['ft_cat_1'])->count()) {
		$block1 = fractal()
	        ->item($categories->where('slug', $section['settings']['ft_cat_1'])->first())
	        ->transformWith(new CategoryCollectionTransformer)
	        ->toArray()['data'];
	    $block_1_products = $categories->where('slug', $section['settings']['ft_cat_1'])->first()->products->count();
	}

	if($categories->where('slug', $section['settings']['ft_cat_2'])->count()) {
		$block2 = fractal()
	        ->item($categories->where('slug', $section['settings']['ft_cat_2'])->first())
	        ->transformWith(new CategoryCollectionTransformer)
	        ->toArray()['data'];
	    $block_2_products = $categories->where('slug', $section['settings']['ft_cat_2'])->first()->products->count();
	}

	if($categories->where('slug', $section['settings']['ft_cat_3'])->count()) {
		$block3 = fractal()
	        ->item($categories->where('slug', $section['settings']['ft_cat_3'])->first())
	        ->transformWith(new CategoryCollectionTransformer)
	        ->toArray()['data'];
	    $block_3_products = $categories->where('slug', $section['settings']['ft_cat_3'])->first()->products->count();
	}

	if($categories->where('slug', $section['settings']['ft_cat_4'])->count()) {
		$block4 = fractal()
	        ->item($categories->where('slug', $section['settings']['ft_cat_4'])->first())
	        ->transformWith(new CategoryCollectionTransformer)
	        ->toArray()['data'];
	    $block_4_products = $categories->where('slug', $section['settings']['ft_cat_4'])->first()->products->count();
	}
}

@endphp

@if($section['disabled'] === false)
<div id="shopbox-section-{{ $section_id }}">
	<div class="container margin_60_35">
		<div class="row small-gutters categories_grid">
			<div class="col-sm-12 col-md-6">
				<a href="{{ count($block1) ? $block1['url'] : '#' }}">
					@if($section['settings']['ft_cat_1_img'])
						<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_1_img']) }}" data-src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_1_img']) }}" alt="{{ count($block1) > 0 ? $block1['name'] : 'Category image'}}" class="img-fluid lazy">
					@else
						<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/img_cat_home_1.jpg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/img_cat_home_1.jpg') }}" alt="Category image" class="img-fluid lazy">
					@endif
					@if($section['settings']['show_cat_title'])
						<div class="wrapper">
							<h2>{{ count($block1) > 0 ? $block1['name'] : 'Category title' }}</h2>
							<p>{{ $block_1_products ?: rand(10, 100) }} Products</p>
						</div>
					@endif
				</a>
			</div>
			<div class="col-sm-12 col-md-6">
				<div class="row small-gutters mt-md-0 mt-sm-2">
					<div class="col-sm-6">
						<a href="{{ count($block2) ? $block2['url'] : '#' }}">
							@if($section['settings']['ft_cat_2_img'])
								<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_2_img']) }}" data-src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_2_img']) }}" alt="{{ count($block2) > 0 ? $block2['name'] : 'Category image'}}" class="img-fluid lazy">
							@else
								<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/img_cat_home_2.jpg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/img_cat_home_2.jpg') }}" alt="Category image" class="img-fluid lazy">
							@endif
							@if($section['settings']['show_cat_title'])
								<div class="wrapper">
									<h2>{{ count($block2) > 0 ? $block2['name'] : 'Category title' }}</h2>
									<p>{{ $block_2_products ?: rand(10, 100) }} Products</p>
								</div>
							@endif
						</a>
					</div>
					<div class="col-sm-6">
						<a href="{{ count($block3) ? $block3['url'] : '#' }}">
							@if($section['settings']['ft_cat_3_img'])
								<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_3_img']) }}" data-src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_3_img']) }}" alt="{{ count($block3) > 0 ? $block3['name'] : 'Category image'}}" class="img-fluid lazy">
							@else
								<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/img_cat_home_2.jpg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/img_cat_home_2.jpg') }}" alt="Category image" class="img-fluid lazy">
							@endif
							@if($section['settings']['show_cat_title'])
								<div class="wrapper">
									<h2>{{ count($block3) > 0 ? $block3['name'] : 'Category title' }}</h2>
									<p>{{ $block_3_products ?: rand(10, 100) }} Products</p>
								</div>
							@endif
						</a>
					</div>
					<div class="col-sm-12 mt-sm-2">
						<a href="{{ count($block4) ? $block4['url'] : '#' }}">
							@if($section['settings']['ft_cat_4_img'])
								<img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_4_img']) }}" data-src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['ft_cat_4_img']) }}" alt="{{ count($block4) > 0 ? $block4['name'] : 'Category image'}}" class="img-fluid lazy">
							@else
								<img src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/img_cat_home_4.jpg') }}" data-src="{{ asset('stores/'.$store->domain.'/themes/clematis/assets/img/img_cat_home_4.jpg') }}" alt="Category image" class="img-fluid lazy">
							@endif
							@if($section['settings']['show_cat_title'])
								<div class="wrapper">
									<h2>{{ count($block4) > 0 ? $block4['name'] : 'Category title' }}</h2>
									<p>{{ $block_4_products ?: rand(10, 100) }} Products</p>
								</div>
							@endif
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
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
	          	"type" => "checkbox",
	          	"id" => "show_cat_title",
	          	"label" => "Show category title"
	    	],
		    [
		      	"type" => "image_picker",
		      	"id" => "ft_cat_1_img",
		      	"label" => "Image",
		      	"info" => "Recommended dimension 700x604"
		    ],
		    [
		    	"type" => "category",
			    "id" => "ft_cat_1",
			    "label" => "Featured category"
		    ],
		    [
		      	"type" => "image_picker",
		      	"id" => "ft_cat_2_img",
		      	"label" => "Image",
		      	"info" => "Recommended dimension 560x480"
		    ],
		    [
			    "type" => "category",
			    "id" => "ft_cat_2",
			    "label" => "Featured category"
		    ],
		    [
		      	"type" => "image_picker",
		      	"id" => "ft_cat_3_img",
		      	"label" => "Image",
		      	"info" => "Recommended dimension 560x480"
		    ],
		    [
			    "type" => "category",
			    "id" => "ft_cat_3",
			    "label" => "Featured category"
		    ],
		    [
		      	"type" => "image_picker",
		      	"id" => "ft_cat_4_img",
		      	"label" => "Image",
		      	"info" => "Recommended dimension 800x343"
		    ],
		    [
			    "id" => "ft_cat_4",
			    "type" => "category",
			    "label" => "Featured category"
		    ]
	  	]
	];

	session()->push('schema', $settings);

@endphp