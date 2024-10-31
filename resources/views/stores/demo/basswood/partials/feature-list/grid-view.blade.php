@if($section['disabled'] === false)
    <div id="shopbox-section-{{ $section_id }}" class="feature-section gray-bg mb-90 pt-80 pb-50 text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-12 mb-30">
                    <div class="basswood-single-feature">
                        <div class="feature-icon">
                            @if($section['settings']['feature_img_1'])
                                <img class="img-fluid" src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['feature_img_1']) }}" alt="feature image" width="80">
                            @else
                                <img class="img-fluid" src="https://via.placeholder.com/80x80/3d3d3d/dcdfde?text=Image" alt="feature image" width="80">
                            @endif
                        </div>
                        <div class="feature-content">
                            @if($section['settings']['feature_title_1'])
                                <h3>{{ $section['settings']['feature_title_1'] }}</h3>
                            @endif
                            @if($section['settings']['feature_subtitle_1'])
                                <p>{{ $section['settings']['feature_subtitle_1'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12 mb-30">
                    <div class="basswood-single-feature">
                        <div class="feature-icon">
                            @if($section['settings']['feature_img_2'])
                                <img class="img-fluid" src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['feature_img_2']) }}" alt="feature image" width="80">
                            @else
                                <img class="img-fluid" src="https://via.placeholder.com/80x80/3d3d3d/dcdfde?text=Image" alt="feature image" width="80">
                            @endif
                        </div>
                        <div class="feature-content">
                            @if($section['settings']['feature_title_2'])
                                <h3>{{ $section['settings']['feature_title_2'] }}</h3>
                            @endif
                            @if($section['settings']['feature_subtitle_2'])
                                <p>{{ $section['settings']['feature_subtitle_2'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12 mb-30">
                    <div class="basswood-single-feature">
                        <div class="feature-icon">
                            @if($section['settings']['feature_img_3'])
                                <img class="img-fluid" src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['feature_img_3']) }}" alt="feature image" width="80">
                            @else
                                <img class="img-fluid" src="https://via.placeholder.com/80x80/3d3d3d/dcdfde?text=Image" alt="feature image" width="80">
                            @endif
                        </div>
                        <div class="feature-content">
                            @if($section['settings']['feature_title_3'])
                                <h3>{{ $section['settings']['feature_title_3'] }}</h3>
                            @endif
                            @if($section['settings']['feature_subtitle_3'])
                                <p>{{ $section['settings']['feature_subtitle_3'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif