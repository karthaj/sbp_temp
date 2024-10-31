@extends($theme_path.'.layouts.theme')

@section('content')

   <brand-products :brand="{{ $brand }}"></brand-products>

@endsection