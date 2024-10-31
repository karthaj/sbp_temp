@extends('layouts.zpanel')

@section('content')		

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg cDiv">
	
		
			<img class="img-fluid" src="{{ asset('assets/img/pos_cs.png') }}" width="400"><hr>
			<h1>Coming Soon</h1>
	@if(auth()->user()->email === 'demo@aidantz.com')
			<a href="{{ route('pos.index') }}">Launch</a>  
	@endif

	
</div>
<!-- END CONTAINER FLUID -->


@endsection
