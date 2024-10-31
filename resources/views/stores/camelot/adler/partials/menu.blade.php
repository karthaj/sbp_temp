<div class="col-xl-5 col-lg-5 col-mb-4 col-sm-12 col-xs-12 hidden-sm-down p-static hidden-md-down">
@if(count($menu))
  <div class="b-header_nav b-header_nav_center">
    <div class="b-menu_top_bar_container">
        <div class="b-main_menu menu-stay-left">
            <ul class="categories pl-0 mb-0 list-unstyled">
            @foreach($menu['links'] as $link)
                <li class="b-has_sub b-dropdown_wrapper from-bottom">
                    <a href="{{ $link['url'] }}" target="{{ $link['target'] }}" class="description">
                    <span class="top">{{ $link['name'] }}</span>
                    @if(count($link['sub']))
                        <i class="aapl-chevron-down"></i>
                    @endif
                    </a>
                    @if(count($link['sub']))
                        <div class="b-dropdown_content sub-holder dropdown-left glyphicon-arrow-down hidden">
                            <div class="dropdown-inner">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12">
                                        <div class="menu-item">
                                            <div class="categories">
                                                <div class="row">
                                                    <div class="col-sm-12 hover-menu">
                                                        <ul> 
                                                            @foreach($link['sub'] as $link)
                                                                <li>
                                                                    <a href="{{ $link['url'] }}" target="{{ $link['target'] }}">{{ $link['name'] }}</a>
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
                    @endif
                </li>
            @endforeach
            </ul>
        </div>
    </div> 
  </div>
@endif
</div>
