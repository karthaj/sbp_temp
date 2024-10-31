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
<div class="main-menu">
    <div id="header_menu">
        <a href="index.html"><img src="//via.placeholder.com/100x35" alt="" width="100" height="35"></a>
        <a href="#" class="open_close" id="close_in"><i class="ti-close"></i></a>
    </div>
    @if(count($menu))
        <ul class="text-center">
        @foreach($menu['links'] as $link)
            @php
                $menu_item = [];

                if(array_has($section, 'blocks')) {
                    $menu_item = array_first($section['blocks'], function ($block, $key) use ($link) { 
                        return $block['settings']['menu_item'] == $link['name']; 
                    });
                }
            @endphp
            @if(count($link['sub']) && count($section['blocks']) && count($menu_item))
                <li class="megamenu submenu">
                    <a href="{{ $link['url'] }}" target="{{ $link['target'] }}" class="show-submenu-mega">{{ $link['name'] }}</a>
                    <div class="menu-wrapper">
                        <div class="row small-gutters">
                            @foreach($link['sub'] as $link)
                                <div class="col-lg-3">
                                    <h3>{{ $link['name'] }}</h3>
                                    @if(count($link['sub']))
                                    <ul>
                                    @foreach($link['sub'] as $link2)
                                        <li><a href="{{ $link2['url'] }}" target="{{ $link2['target'] }}">{{ $link2['name'] }}</a></li>
                                    @endforeach
                                    </ul>
                                    @endif
                                </div>
                            @endforeach
                            @if($menu_item['settings']['image'])
                                <div class="col-lg-3 d-xl-block d-lg-block d-md-none d-sm-none d-none">
                                    <div class="banner_menu">
                                        <a href="{{ $menu_item['settings']['image_link'] ?: '#' }}">
                                            <img src="{{ asset('stores/'.$store->domain.'/img/'.$menu_item['settings']['image']) }}" data-src="{{ asset('stores/'.$store->domain.'/img/'.$menu_item['settings']['image']) }}" width="400" height="550" alt class="img-fluid lazy">
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
            @elseif(count($link['sub']))
                <li class="submenu">
                    <a href="{{ $link['url'] }}" target="{{ $link['target'] }}" class="show-submenu">{{ $link['name'] }}</a>
                    <ul>
                        @foreach($link['sub'] as $link)
                            <li><a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }}</a></li>
                        @endforeach
                    </ul>
                </li>
            @else
                <li>
                    <a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }}</a>
                </li>
            @endif
        @endforeach
        </ul>
    @endif
</div>
<!--/main-menu -->