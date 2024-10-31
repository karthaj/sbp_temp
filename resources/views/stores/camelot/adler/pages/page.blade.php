@extends($theme_path.'.layouts.theme')

@section('header_scripts')
@if($page->type === 'contact' && $page->enable_form)
    <script>
        function onloadCallback() {
            grecaptcha.render('g-recaptcha', {}, true);
            grecaptcha.execute();
        }
    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"></script>
@endif
@endsection

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