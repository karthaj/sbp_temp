@foreach($items as $item) 
  <li @if(!$item->parent_id) data-step="{{ $loop->iteration }}" data-intro="{{ __('intro.menu.'.$item->alias) }}" data-position="right" @endif>
  		<a href="{{ $item->url ? route($item->url) : 'javascript:;' }}">
  			<span class="title">{{ $item->name }}
          @if($item->alias === 'orders' && $pending_orders)
            <span class="badge badge-orders">{{ $pending_orders }}</span>
          @endif
        </span>
			@if($item->submenus->count())
		      	<span class=" arrow"></span></a>
		    @endif
  		</a>
 		
 		@if($item->icon)
 			<span class="icon-thumbnail"><i class="{{ $item->icon }}"></i></span>
 		@endif
        
      @if($item->submenus->count())
        <ul class="sub-menu">
              @include('layouts.partials._sidebar_items', ['items' => $item->submenus])
        </ul>
      @endif
  </li>
@endforeach