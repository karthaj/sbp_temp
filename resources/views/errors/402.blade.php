@extends('layouts.zpanel')

@section('content')

<!-- START JUMBOTRON -->
<div class="jumbotron" data-pages="parallax">
<div class=" container-fluid container-fixed-lg sm-p-l-0 sm-p-r-0">
  <div class="inner">
  </div>
</div>
</div>
<!-- END JUMBOTRON -->
<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg">
<!-- BEGIN PlACE PAGE CONTENT HERE -->
  <div class="row text-center">
    <div class="col-md-12">
      <h2>Plan Upgrade Required!</h2>
      <p>Inorder to access this feature please upgrade to below plan.</p>
      <div class="row flex justify-content-center">
        @foreach(session('plans') as $upgrade)
        <div class="col-md-3 card">
          <div class="card-block">
            @if(session('store')->plan->slug === 'trial')
               <p class="font-weight-bold">{{ strtoupper($upgrade->plan->name) }}</p>
            @else
              <a href="{{ route('plan.change.show', $upgrade->plan) }}" class="btn btn-info">{{ strtoupper($upgrade->plan->name) }}</a>
            @endif
          </div>
        </div>
        @endforeach
        @if(session('store')->plan->slug === 'trial')
          <div class="col-md-12">
            <a href="{{ route('plan.change.index') }}" class="btn btn-action-add">Choose Plan</a>
          </div>
        @endif
      </div>
    </div>
  </div>
<!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->

@endsection