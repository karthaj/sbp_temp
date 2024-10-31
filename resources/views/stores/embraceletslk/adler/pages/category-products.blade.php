@extends($theme_path.'.layouts.theme')

@section('content')

   	<category-products handle="{{ $category->slug }}"></category-products>

   	<script type="application/json" id="categoryJson">
    	{!! $data !!}
   	</script>

@endsection
