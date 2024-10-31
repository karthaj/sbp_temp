<div class="col-xl-12 col-lg-12 col-mb-4 col-sm-12 col-xs-12 hidden-sm-down hidden-md-down">
    @if(count($mega_menu))
        <div class="b-header_nav">
            <div class="b-menu_top_bar_container">
                <div class="secondary-menu menu-stay-left">
                    <ul class="categories pl-0 mb-0 list-unstyled">
                    @foreach($mega_menu['links'] as $link)
                        <li class="b-has_sub b-dropdown_wrapper from-bottom">
                            <a href="{{ $link['url'] }}" target="{{ $link['target'] }}" class="description">
                            <span class="top">{{ $link['name'] }}</span>
                            @if(count($link['sub']))
                                <i class="aapl-chevron-down"></i>
                            @endif
                            </a>
                            @if(count($link['sub']))
                                <div class="b-dropdown_content">
                                    <div class="dropdown-inner">
                                        <div class="row justify-content-center">
                                            @foreach($link['sub'] as $link2)
                                                <div class="col-sm-2">
                                                    <div class="menu-item">
                                                        <a href="{{ $link2['url'] }}" target="{{ $link2['target'] }}"><h4 class="column-title"><b>{{ $link2['name'] }}</b></h4></a> 
                                                        <div class="categories">
                                                            <div class="row">
                                                                <div class="col-sm-12 hover-menu">
                                                                    <ul> 
                                                                        @foreach($link2['sub'] as $link3)
                                                                          <li>
                                                                            <a href="{{ $link3['url'] }}" target="{{ $link3['target'] }}">{{ $link3['name'] }}</a>
                                                                          </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </li>
                    @endforeach
                    </ul>
                </div>
           </div>
        </div>
    @endif
</div>
