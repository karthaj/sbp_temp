@extends($theme_path.'.layouts.theme')

@section('content')
    
    <div class="container-fluid">
    	@if($page->type === 'contact')
    		<div class="b-contact b-contact_light py-5 my-4">
	            <div class="container">
	               <div class="row clearfix">
	                 @if($page->enable_form)
	                 	@include($theme_path.'.partials.contact-form')
	                 @endif
	                 <div class="col-xl-6 col-lg-6 col-mb-6 col-sm-12 col-xs-12">{!! html_entity_decode($page->body) !!}</div>
	               </div>
	            </div>
          	</div>
          	<div class="row">{!! $page->map !!}</div>
        @else
        	{!! html_entity_decode($page->body) !!}
    	@endif
    </div>

@endsection