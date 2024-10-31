
<div class="container-fluid">
    <div class="row clearfix align-items-center">
      @if($section['settings']['layout_grid'] === 'half_right_text')
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
          <img src="{{ $section['settings']['image'] ? asset('stores/'.$store->domain.'/img/'.$section['settings']['image']) : 'https://via.placeholder.com/540x540?text=Image' }}" class="img-fluid d-block m-auto" alt="image">
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
          @if($section['settings']['text'])
            <div class="b-about_text">{!! $section['settings']['text'] !!}</div>
          @endif
        </div>
      @elseif($section['settings']['layout_grid'] === 'half_right_image')
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
          @if($section['settings']['text'])
            <div class="b-about_text">{!! $section['settings']['text'] !!}</div>
          @endif
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
          <img src="{{ $section['settings']['image'] ? asset('stores/'.$store->domain.'/img/'.$section['settings']['image']) : 'https://via.placeholder.com/540x540?text=Image' }}" class="img-fluid d-block m-auto" alt="image">
        </div>
      @endif
    </div>
</div>
