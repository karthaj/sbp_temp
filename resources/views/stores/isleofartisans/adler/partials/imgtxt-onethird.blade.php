  
  <div class="container-fluid">
    <div class="row clearfix align-items-center">
      @if($section['settings']['layout_grid'] === 'one_third_right_text')
        <div class="col-xl-4 col-lg-4 col-mb-4 col-sm-12 col-xs-12 px-0">
          <img src="{{ $section['settings']['image'] ? asset('stores/'.$store->domain.'/img/'.$section['settings']['image']) : 'https://via.placeholder.com/1000x540?text=Image' }}" class="img-fluid d-block m-auto" alt="image">
        </div>
        <div class="col-xl-8 col-lg-8 col-mb-8 col-sm-12 col-xs-12">
          @if($section['settings']['text'])
            <div class="b-about_text">{!! $section['settings']['text'] !!}</div>
          @endif
        </div>
      @elseif($section['settings']['layout_grid'] === 'one_third_right_image')
        <div class="col-xl-4 col-lg-4 col-mb-4 col-sm-12 col-xs-12">
          @if($section['settings']['text'])
            <div class="b-about_text">{!! $section['settings']['text'] !!}</div>
          @endif
        </div>
        <div class="col-xl-8 col-lg-8 col-mb-8 col-sm-12 col-xs-12 px-0">
          <img src="{{ $section['settings']['image'] ? asset('stores/'.$store->domain.'/img/'.$section['settings']['image']) : 'https://via.placeholder.com/1000x540?text=Image' }}" class="img-fluid d-block m-auto" alt="image">
        </div>
      @endif
    </div>
  </div>