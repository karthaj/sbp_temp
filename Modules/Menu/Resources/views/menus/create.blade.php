@extends('layouts.zpanel')

@section('styles')

@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
 	<li class="breadcrumb-item">storefront</li>
 	<li class="breadcrumb-item"><a href="{{ route('menus.index') }}">menu</a></li>
	
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header ">
      <div>
          <h1 class="section-title">Add menu</h1>
      </div>
  </div>
  <div class="row card-block">
    <div class="col-md-4">
      <h6 class="ui-subheader">
       Menus
      </h6>
      <div class="p-r-30">
        <p>
         Menus, or link lists, help your customers navigate around your online store.
        </p>
        <p>
          You can also create nested menus to display drop-down menus, and group products or pages together.
        </p>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card">
        <div class="card-block">
          <form action="{{ route('menus.store') }}" method="post" autocomplete="off" class="sodirty">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('menu_name') ? ' has-danger' : '' }}">
              <label for="menu_name">Menu Name</label>
              <input type="text" class="form-control{{ $errors->has('menu_name') ? ' form-control-danger' : '' }}" name="menu_name" value="{{ old('menu_name') }}" required>

              @if($errors->has('menu_name'))
              <div class="form-control-feedback">{{ $errors->first('menu_name') }}</div>
              @endif
            </div>
            @include ('zpanel.partials._form_actions', ['path' => route('menus.index')])
          </form>
        </div>
      </div>      
    </div>
  </div>
</div>

@endsection

@section('scripts') 

@endsection

