@extends('layouts.zpanel')

@section('styles')

@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
	<li class="breadcrumb-item">storefront</li>
	<li class="breadcrumb-item">menu</li>
	<li class="pull-right"><a href="javascript:void(0);" class="btn guide-me"><i class="aapl-lifebuoy"></i> Guide me</a></li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header">
    <div class="row justify-content-between align-items-center">
      <div class="col-sm-4">
        <h1 class="section-title">Menu</h1>
      </div>
      @if(auth()->user()->can('add menus'))
      <div class="col-sm-3">
        <a href="{{ route('menus.create') }}" class="btn btn-primary btn-action-add">Create New Menu</a>
      </div>
      @endif
    </div>
    
  </div>
  <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
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
      <div class="col-lg-8 sm-no-padding">
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default">
                      <div class="card-block">
                        @if($menus->count())
                          <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Menu Name</th>
                                        <th>Menu Items</th>
                                    </tr>  
                                </thead>
                                <tbody>
                                @foreach($menus as $menu)
                                  <tr> 
                                    <td class="v-align-middle">
                                      @if(auth()->user()->can('edit menus'))
                                        <a href="{{ route('menus.edit', $menu) }}"> <i class="aapl-pencil4"></i> {{ $menu->name }}</a>
                                      @else
                                        <span>{{ $menu->name }}</span>
                                      @endif
                                    </td>
                                    <td class="v-align-middle">
                                    @if($menu->items->count())
                                      @foreach($menu->items as $item)
                                      <span class="btn btn-tag">{{ $item->name }}</span>
                                      @endforeach
                                    @endif
                                    </td>  
                                  </tr>
                                @endforeach
                                </tbody>
                            </table>
                          </div>
                        @else
                        <p class="text-center">No menus created.</p>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>    
            </div>
        </div>
      </div>
  </div>
</div>

@endsection

@section('scripts') 

@endsection

@section('page_scripts')
<script>
  $(document).ready(function() {
    
  });
</script>
@endsection
