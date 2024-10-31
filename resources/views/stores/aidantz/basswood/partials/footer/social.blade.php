<div class="footer-social">
    <ul>
    	@if($settings['social_facebook_link'])
    		<li><a href="{{ $settings['social_facebook_link'] }}"><i class="fa fa-facebook"></i></a></li>
    	@endif
    	@if($settings['social_twitter_link'])
    		<li><a href="{{ $settings['social_twitter_link'] }}"><i class="fa fa-twitter"></i></a></li>
    	@endif
    	@if($settings['social_youtube_link'])
    		<li ><a href="{{ $settings['social_youtube_link'] }}"><i class="fa fa-youtube"></i></a></li>
    	@endif
    	@if($settings['social_google_plus_link'])
    		<li><a href="{{ $settings['social_google_plus_link'] }}"><i class="fa fa-google-plus"></i></a></li>
    	@endif
    	@if($settings['social_instagram_link'])
    		<li><a href="{{ $settings['social_instagram_link'] }}"><i class="fa fa-instagram"></i></a></li>
    	@endif
    </ul>
</div>