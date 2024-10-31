<footer id="footer2">
    <div class="footer-container">
        <div class="footer-top-area pt-60">
            <div class="container-fluid pl-60 pr-60">
                <div class="row">
                    <div class="col-lg-4 col-sm-3">
                        <div class="single-footer single-footer-4">
                            <div class="footer-logo-4">
                                 <a href="{{ getStoreUrl($store) }}">
                                    @if($section['settings']['logo'])
                                        <img src="{{ asset('stores/'.$store->domain.'/img/'.$section['settings']['logo']) }}" width="123" class="img-fluid" alt="{{ $store->store_name }}">
                                    @else
                                        <img src="https://via.placeholder.com/123x36.png?text=Logo" class="img-fluid" alt="{{ $store->store_name }}">
                                    @endif      
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-5">
                        <div class="single-footer single-footer-4 text-center">
                            @include($theme_path.'.partials.footer.payment-icon')
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4">
                        <div class="single-footer single-footer-4 text-right">
                            @include($theme_path.'.partials.footer.social')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom-area-2">
            <div class="container-fluid pl-60 pr-60">
                <div class="footer-bottom-item">
                    <div class="row align-items-center">
                        <div class="col-md-4 col-12 pb-2">
                            <div class="footer-bottom-menu footer-bottom-menu-4">
                                @include($theme_path.'.partials.footer.menu')
                            </div>
                        </div>
                        <div class="col-md-4 col-12 text-center pb-2">
                            <div class="copyright-text">
                                <p>© {{ date('Y') }}. All Rights Reserved.</p> 
                                
                            </div>
                        </div>
                        @if($store->setting->show_branding)
                            <div class="col-md-4 col-12 text-center pb-2">
                                <span class="powered">Powered by <a href="https://www.shopbox.lk">ShopBox</a></span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>