@extends('layouts.zpanel')

@section('styles')

<link href="{{ asset('assets/plugins/dropzone/css/dropzone.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item">Modules</li>
  <li class="breadcrumb-item active"><a href="{{ route('plugin.all') }}">All</a></li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header ">
        <div>
            <h1 class="section-title">Modules</h1>
        </div>
      </div>
      <div class="card-block">
        <div class="row">
        @if($plugins->count())
            @foreach($plugins as $plugin)
              <div class="col-lg-4">
                 <form action="{{ $plugin->status ? route('plugin.deactive', $plugin->id) : route('plugin.active', $plugin->id) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div data-pages="card" class="card card-default" id="card-basic">
                      <div class="card-header separator">
                        <div class="card-title">{{ title_case($plugin->plugin_name) }}
                        </div>
                        <div class="card-controls">
                          <ul>
                            <li><a data-toggle="collapse" class="card-collapse" href="javascript:;"><i class="pg-arrow_maximize"></i></a>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="card-block pt-20">
                        <p> {{ $plugin->description }} </p>
                            <p class="small hint-text m-t-5">{{ $plugin->price ? 'Price '.$plugin->price : 'Free' }}</p>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-custom-v1" type="submit"> {{ $plugin->status ? 'Deactivate' : 'Activate' }} </button>
                            <button class="btn btn-default btn-default-custom" type="button" onclick="Shopbox.deletePlugin('{{ route('plugin.destroy', $plugin->id) }}')">Delete</button>
                        </div>
                      </div>
                    </div>
                </form>
              </div>
            @endforeach
        @else
            <div class="col">
                <div data-pages="card" class="card card-default" id="card-basic">
                  <div class="card-block pt-20">
                    <h6 class="text-center"> No Modules found!</h6>
                  </div>
                </div>
            </div>
        @endif
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>
@endsection




