@extends('layouts.response')

@section('content')
<div class="starter-template">
    <h1>Payment Failed</h1>
    <div class="alert alert-danger" role="alert">
	  	{{ $response['data']['status_desc'] }}
	</div>
	<a href="{{ getStoreUrl($store).'/checkout' }}" class="btn btn-primary">Retry</a>
</div>
@endsection
