	
@if(auth()->check())
  <product-detail-default section="{{ $section_id }}" :authenticated="true" :wishlists="{{ auth()->user()->wishlists()->where('store_id', session('store')->id)->pluck('product_id')->toJson() }}"></product-detail-default>
@else
  <product-detail-default section="{{ $section_id }}"></product-detail-default>
@endif

@php
	$settings = [
      "name" => "Product Detail",
      "section" => $section_id,
      "type" => "product_detail", 
      "settings" => [
        [
	        "type" => "checkbox",
	        "id" => "enable_social_sharing",
	        "label" => "Enable social sharing"
	      ]
      ]
  ];

  session()->push('schema', $settings);
@endphp