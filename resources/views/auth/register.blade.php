@extends('layouts.app')

@section('content')

    <get-started :countries="{{ json_encode($countries) }}" :states="{{ $states }}"></get-started>

@endsection
