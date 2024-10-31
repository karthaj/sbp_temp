@php
  use Shopbox\Transformers\StoreFront\MenuCollectionTransformer;

  $menu = [];

  if($section['settings']['menu'] && $menus->where('slug', $section['settings']['menu'])->count()) {
      $menu = fractal()
        ->item($menus->where('slug', $section['settings']['menu'])->first())
        ->transformWith(new MenuCollectionTransformer)
        ->toArray()['data'];
  }

@endphp
	
@if(count($menu))
<nav>
    <ul>
    	@foreach($menu['links'] as $link)
	        <li><a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }} 
		    	@if(count($link['sub']))
		            <i class="zmdi zmdi-chevron-down"></i>
		        @endif
	        </a>
		        @if(count($link['sub']))
		            <ul class="dropdown">
		            	@foreach($link['sub'] as $link)
		                	<li><a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }}</a></li>
		                @endforeach
		            </ul>
		        @endif
	        </li>
        @endforeach
    </ul>
</nav>
@endif