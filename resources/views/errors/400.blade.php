@extends('layouts.app')

@section('content')

<div class="container text-center">
	<i class="fa fa-warning fa-5x"></i>
	<h2>Hold on, you're not authorized to go there</h2>
	<p>You're logged in as {{ auth()->user()->email }}</p>
	<a href="/merchant/dashboard">Return to dashboard</a>
</div>

@endsection
