@if(count($link['sub']))
	<li class="has-sub dropdown-wrapper from-bottom">
        <a href="{{ $link['url'] }}" target="{{ $link['target'] }}"><span class="top">{{ $link['name'] }}</span>
            @if(count($link['sub']))
                <i class="aapl-chevron-down mr-0"></i>
            @endif
        </a>
        @if(count($link['sub']))
            <div class="dropdown-content sub-holder dropdown-left narrow">
                <div class="dropdown-inner" style="display: none;">
                    <div class="clearfix">
                        <div class="col-xs-12 col-sm-12 ">
                            <div class="menu-item"> 
                                <div class="categories">
                                    <div class="clearfix">
                                        <div class="col-sm-12 hover-menu">
                                            <ul>
                                                @foreach($link['sub'] as $link)
                                                    @include($theme_path.'.partials.menu-item', ['link' => $link])
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
        @endif
    </li> 
@else
    <li>
        <a href="{{ $link['url'] }}"><span class="top">{{ $link['name'] }}</span></a>
    </li>
@endif