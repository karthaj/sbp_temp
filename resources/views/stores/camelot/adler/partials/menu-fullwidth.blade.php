 @if(count($mega_menu)) 
  <div class="b-page_title b-page_title_default text-center b-shop_filter hidden-sm-down hidden-md-down">
    <div class="col-sm-12">
      <ul class="adler-product-categories list-inline text-center">
        @foreach($mega_menu['links'] as $link)
        <li>
          <a href="{{ $link['url'] }}" target="{{ $link['target'] }}" @if($link['target'] == '_self' && request()->url() == $link['url']) class="b-active" @endif>{{ $link['name'] }}</a>
          @if(count($link['sub']))
            <ul class="children">
              @foreach($link['sub'] as $link2)
                <li class="cat-item"><a href="{{ $link2['url'] }}" target="{{ $link2['target'] }}" @if($link2['target'] == '_self' && request()->url() == $link2['url']) class="b-active" @endif>{{ $link2['name'] }}</a></li>
              @endforeach
            </ul>
          @endif
        </li>
        @endforeach
      </ul>
    </div> 
  </div>
@endif