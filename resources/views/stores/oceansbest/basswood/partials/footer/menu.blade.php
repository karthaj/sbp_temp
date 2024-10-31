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
	
<nav>
    @if(count($menu))
        <ul>
            @foreach($menu['links'] as $link)
                <li><a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }}</a></li>
            @endforeach
        </ul>
    @else
        <ul>
            @for ($i = 1; $i < 5; $i++)
                <li><a href="#">Menu {{ $i }}</a></li>
            @endfor
        </ul>
    @endif
</nav>
