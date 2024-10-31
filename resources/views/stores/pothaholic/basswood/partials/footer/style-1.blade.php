<footer>
    <div class="footer-container">
        <div class="footer-top-area ptb-50 text-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 offset-lg-1 col-12">
                        <div class="footer-logo">
						    <a href="{{ getStoreUrl($store) }}">
                                @if($section['settings']['logo'])
                                    <img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['logo']) }}" width="123" class="img-fluid" alt="{{ $store->store_name }}">
                                @else
                                    <img src="https://via.placeholder.com/123x123.png?text=Logo" class="img-fluid" alt="{{ $store->store_name }}">
                                @endif
						    </a>
						</div>
						
                        <div class="footer-nav">
                            @include($theme_path.'.partials.footer.menu')
                        </div>
                        
                        @include($theme_path.'.partials.footer.social')

                        @include($theme_path.'.partials.footer.payment-icon')
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom-area">
            <div class="container text-center">
                <p>©  {{ date('Y') }}. All Rights Reserved.</p>
                @if($store->setting->show_branding)
                    <span class="powered">Powered by <a href="https://shopbox.lk">Shopbox</a></span>
                @endif
            </div>
        </div>
    </div>
</footer>