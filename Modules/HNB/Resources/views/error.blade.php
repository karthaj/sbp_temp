@extends('layouts.response')

@section('content')
<div class="starter-template">
    <h1>Payment Failed</h1>
    <div class="alert alert-danger" role="alert">
	  	{{ $data['ReasonCodeDesc'] }}
	</div>
	<a href="/checkout" class="btn btn-primary">Retry</a>
</div>
@endsection
