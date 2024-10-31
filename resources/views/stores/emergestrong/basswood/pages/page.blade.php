@extends($theme_path.'.layouts.theme')

@section('breadcrum')
<div class="breadcrumb-area pt-20 pb-20">
     <div class="container">
         <div class="row">
             <div class="col-md-12">
                 <div class="breadcrumb-content">
                     <ul>
                         <li><a href="{{ getStoreUrl($store) }}">Home</a></li>
                         <li class="active"><a href="{{ route('stores.page', $page) }}">{{ $page->title }}</a></li>
                     </ul>
                 </div>
             </div>
         </div>
     </div>
</div>
@endsection

@section('content')
   
@if($page->type === 'contact') 
<div class="contact-us-area pt-90 pb-40">
	<div class="container">
		<contact route="{{ route('stores.contact', $page) }}" :enable-form="{{ $page->enable_form }}"></contact>
		@if($page->map)
			<div class="row">{!! $page->map !!}</div>
		@endif
	</div>
</div>
@else
<div class="container">
    <div class="row">
        <div class="col-md-12">{!! html_entity_decode($page->body) !!}</div>
    </div>
</div>
@endif

@endsection