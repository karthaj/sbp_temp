@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-red">Stores</div>

                <div class="card-body">
                    <div class="list-group">
                        @foreach($stores as $store)
                            <a href="{{ url('//'.$store->domain.'.'.config('domain.app_domain').'/merchant/dashboard') }}" class="list-group-item">{{ $store->store_name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
