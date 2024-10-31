@php
    use Shopbox\Transformers\StoreFront\MenuCollectionTransformer;
    
    $currencies = explode(',', $settings['currencies']);
    $menu = $mega_menu = [];

    if($section['settings']['header_main_menu'] && $menus->where('slug', $section['settings']['header_main_menu'])->count()) {
      $menu = fractal()
        ->item($menus->where('slug', $section['settings']['header_main_menu'])->first())
        ->transformWith(new MenuCollectionTransformer)
        ->toArray()['data'];
    }

    if($section['settings']['header_enable_secondary_menu'] && $menus->where('slug', $section['settings']['header_secondary_menu'])->count()) {
    $mega_menu = fractal()
      ->item($menus->where('slug', $section['settings']['header_secondary_menu'])->first())
      ->transformWith(new MenuCollectionTransformer)
      ->toArray()['data'];
    }

@endphp
<div class="b-main_menu-wrapper hidden-lg-up">
    <ul class="mobile-top"> 
        <li class="search">
            <form role="search" method="get" id="searchmobform" class="searchform" action="/search">
                <div class="search-holder-mobile">
                    <input type="text" name="q" value="" placeholder="Search" class="form-control">
                    <a class="fa fa-search" onclick="event.preventDefault();document.getElementById('searchmobform').submit();"></a>
                </div>
            </form>
        </li>
    </ul>
    <ul class="categories"> 
        @if(count($menu))
            @foreach($menu['links'] as $link)
                @include($theme_path.'.partials.menu-item', ['link' => $link])
            @endforeach
        @endif
        @if($section['settings']['header_enable_secondary_menu'] && count($mega_menu))
            @foreach($mega_menu['links'] as $link)
                @include($theme_path.'.partials.menu-item', ['link' => $link])
            @endforeach
        @endif
        @if($settings['enable_multicurrency'] && count($currencies))
        <li class="has-sub dropdown-wrapper from-bottom">
            <a href="#"><span class="top">Currency</span><i class="aapl-chevron-down mr-0"></i></a>
            <!-- Sub Menu items -->
            <div class="dropdown-content sub-holder dropdown-left narrow">
                <div class="dropdown-inner" style="display: none;">
                    <div class="clearfix">
                        <div class="col-xs-12 col-sm-12 ">
                            <div class="menu-item"> 
                                <div class="categories">
                                    <div class="clearfix">
                                        <div class="col-sm-12 hover-menu">
                                            <ul>
                                                <li>
                                                    <a href="#" data-code="{{ $store->setting->currency->iso_code }}" class="mobile-menu-currency-link{{ request()->cookie('currency') === $store->setting->currency->iso_code ? ' mobile-menu-currency-selected' : '' }}">{{ $store->setting->currency->iso_code }}</a>
                                                </li>
                                                @foreach($currencies as $currency)
                                                    <li>
                                                        <a href="#" data-code="{{ $currency }}" class="mobile-menu-currency-link{{ request()->cookie('currency') === $currency ? ' mobile-menu-currency-selected' : '' }}">{{ $currency }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </li>
        @endif
        @if(auth()->check())
            <li><a href="/account"><span class="top">Profile</span></a></li>
            <li>
                <a href="/logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><span class="top">Logout</span></a>
            </li>
        @else
            <li><a href="/login"><span class="top">Login / Register</span></a></li>
        @endif
    </ul>
</div>