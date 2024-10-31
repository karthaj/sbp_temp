@extends('layouts.zpanel')

@section('styles')

@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">preferences</li>
</ol>
<!-- END BREADCRUMB --> 
<form action="{{ route('store.preferences.update', $store) }}" method="post" autocomplete="off">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
  <div  class="card card-transparent">
    <div class="card-header">
      <h1 class="section-title">Preferences</h1>
    </div>
    
    <!-- start seo -->
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Search Engine Optimization
          </h6>
          <div class="p-r-30">
            <p>
             The title and meta description help define how your store shows up on search engines.
            </p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div class="card card-default">
                        <div class="card-block">
                          <div class="col-sm-12 form-group{{ $errors->has('page_title') ? ' has-danger' : '' }}">
                            <label for="store_name">page title</label>
                            <input type="text" class="form-control{{ $errors->has('page_title') ? ' form-control-danger' : '' }}" id="page_title" name="page_title" value="{{ old('page_title', $store->setting->meta_title) }}" required>
                            @if($errors->has('page_title'))
                                <div class="form-control-feedback">{{ $errors->first('page_title') }}</div>
                            @endif
                          </div>
                          <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="The meta keywords to display in the header of each page on your site. These words should explain what you sell online. Separate each word with a comma, such as <em>action dvd's, comedy dvd's</em>, etc." data-html="true">
                              <i class="fa fa-question-circle"></i>
                            </span>
                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $store->setting->meta_keywords) }}">
                          </div> 
                          <div class="form-group">
                            <label for="meta_description">meta description</label>
                            <textarea name="meta_description" id="meta_description" cols="30" rows="5" class="form-control">{{ old('meta_description', $store->setting->meta_description) }}</textarea>
                          </div>   
                        </div>
                      </div>
                    </div>
                  </div>    
              </div>
          </div>
        </div>
    </div>
    <!-- end seo -->

    <!-- start analytics -->
    <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
            <h6 class="ui-subheader">
             Google Analytics
            </h6>
            <div class="p-r-30">
              <p>
				  Visit your <a href="https://analytics.google.com/" target="_blank">Google Analytics Account</a> to set up or locate your Tracking ID.
              </p>
              <div class="alert alert-info">
                <strong>Note:</strong> Your ShopBox store automatically creates and stores an XML sitemap. To access it, go to <b>yourdomain.com/sitemap.xml</b>. This will be the link used when submitting your sitemap to search engines.
              </div>
            </div>
      </div>
      <div class="col-lg-8 sm-no-padding">
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div class="card card-default">
                      <div class="card-block">
                        <div class="form-group{{ $errors->has('google_analytics') ? ' has-danger' : '' }}">
                          <label for="google_analytics">google analytics</label>
                          <span class="ml-2" data-placement="right" data-toggle="tooltip" data-html=
                          "true" data-original-title="<p>Please enter your Tracking ID provided by Google from your Google Analytics account. It may resemble the following:<br> UA-XXXXXXXX-X</p>">
                            <i class="fa fa-question-circle"></i>
                          </span>
                          <input name="google_analytics" id="google_analytics" class="form-control{{ $errors->has('google_analytics') ? ' form-control-danger' : '' }}" value="{{ old('google_analytics', $store->setting->google_analytics) }}">
                          @if($errors->has('google_analytics'))
                            <div class="form-control-feedback">{{ $errors->first('google_analytics') }}</div>
                          @endif
                        </div>   
                      </div>
                    </div>
                  </div>
                </div>    
            </div>
        </div>
      </div>
    </div>
    <!-- end analytics -->
	
	<!-- Facebook Pixel -->
    <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
            <h6 class="ui-subheader">
             Facebook Pixel
            </h6>
            <div class="p-r-30">
              <p>
				  Visit your <a href="https://www.facebook.com/ads/manager/pixel/facebook_pixel" target="_blank">Facebook Ad Manager Account</a>  to set up or locate your Pixel ID.
              </p>
            </div>
      </div>
      <div class="col-lg-8 sm-no-padding">
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div class="card card-default">
                      <div class="card-block">
                        <div class="form-group{{ $errors->has('facebook_pixel_id') ? ' has-danger' : '' }}">
                          <label for="facebook_pixel_id">facebook pixel id</label>
                          <span class="ml-2" data-placement="right" data-toggle="tooltip" data-html="true" data-original-title="<p>Please enter your Pixel ID provided by Facebook from your Facebook Ad Manager account. It may resemble a sixteen (16) digit number.</p>">
                            <i class="fa fa-question-circle"></i>
                          </span>
                          <input name="facebook_pixel_id" id="facebook_pixel_id" class="form-control{{ $errors->has('facebook_pixel_id') ? ' form-control-danger' : '' }}" value="{{ old('facebook_pixel_id', $store->setting->facebook_pixel_id) }}">
                          @if($errors->has('facebook_pixel_id'))
                            <div class="form-control-feedback">{{ $errors->first('facebook_pixel_id') }}</div>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>    
            </div>
        </div>
      </div>
    </div>
    <!-- end Facebook Pixel -->

    <!-- Google recaptcha -->
    <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
            <h6 class="ui-subheader">
             Security
            </h6>
            <div class="p-r-30">
              <p>
                reCAPTCHA field be displayed on contact form in your store reCAPTCHA fields help reduce comment spam. Register reCAPTCHA v3 keys <a href="https://g.co/recaptcha/v3" target="_blank"><strong>Here</strong></a>
              </p>
              <div class="alert alert-info">
                <strong>Note:</strong> Own domain stores need to register for reCAPTCHA v3 keys as the ShopBox reCAPTCHA keys will not work.
              </div>
            </div>
      </div>
      <div class="col-lg-8 sm-no-padding">
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent">
                  <div class="card-block no-padding">
                    <div class="card card-default">
                      <div class="card-block">
                        <div class="form-group{{ $errors->has('captcha_site_key') ? ' has-danger' : '' }}">
                          <label for="captcha_site_key">reCAPTCHA Site Key</label>
                          <span class="ml-2" data-placement="right" data-toggle="tooltip" data-html="true" data-original-title="<strong>reCAPTCHA Site Key?</strong><p>By entering the reCAPTCHA keys, these keys will be used for all reCAPTCHA in your store. If these keys are not specified, the ShopBox reCAPTCHA keys will be used instead.</p>">
                            <i class="fa fa-question-circle"></i>
                          </span>
                          <input name="captcha_site_key" id="captcha_site_key" class="form-control{{ $errors->has('captcha_site_key') ? ' form-control-danger' : '' }}" value="{{ old('captcha_site_key', $store->setting->captcha_site_key) }}">
                          @if($errors->has('captcha_site_key'))
                            <div class="form-control-feedback">{{ $errors->first('captcha_site_key') }}</div>
                          @endif
                        </div>
                        <div class="form-group{{ $errors->has('captcha_secret_key') ? ' has-danger' : '' }}">
                          <label for="captcha_secret_key">reCAPTCHA Secret Key</label>
                          <span class="ml-2" data-placement="right" data-toggle="tooltip" data-html="true" data-original-title="<strong>reCAPTCHA Secret Key?</strong><p>By entering the reCAPTCHA keys, these keys will be used for all reCAPTCHA in your store. If these keys are not specified, the ShopBox reCAPTCHA keys will be used instead.</p>">
                            <i class="fa fa-question-circle"></i>
                          </span>
                          <input name="captcha_secret_key" id="captcha_secret_key" class="form-control{{ $errors->has('captcha_secret_key') ? ' form-control-danger' : '' }}" value="{{ old('captcha_secret_key', $store->setting->captcha_secret_key) }}">
                          @if($errors->has('captcha_secret_key'))
                            <div class="form-control-feedback">{{ $errors->first('captcha_secret_key') }}</div>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>    
            </div>
        </div>
      </div>
    </div>
    <!-- end Google recaptcha -->

    <!-- start password protection -->
    <div id="passwordProtected" class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Password protection
          </h6>
          <div class="p-r-30">
            <p>
             Enable the password to restrict access to your online store. Only users with the password can access it.
            </p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div class="card card-default">
                        <div class="card-block">
                          <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label for="password">Password</label>
                            <input type="text" id="password" name="password" class="form-control{{ $errors->has('password') ? ' form-control-danger' : '' }}" value="{{ $store->setting->password }}">
                            @if($errors->has('password'))
                              <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                            @endif
                          </div>
                          <div class="form-group{{ $errors->has('message') ? ' has-danger' : '' }}">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" class="form-control{{ $errors->has('message') ? ' form-control-danger' : '' }}" cols="30" rows="10">{{ $store->setting->message }}</textarea>
                            @if($errors->has('password'))
                              <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                            @endif
                          </div>
                          <div class="form-group{{ $errors->has('enable_password') ? ' has-danger' : '' }}">
                            <div class="checkbox check-info checkbox-circle">
                              <input type="checkbox" value="1" id="enable_password" name="enable_password" @if($store->setting->enable_password) checked @endif>
                              <label for="enable_password">Enable password</label>
                            </div>
                            @if($errors->has('enable_password'))
                              <div class="form-control-feedback">{{ $errors->first('enable_password') }}</div>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>    
              </div>
          </div>
        </div>
    </div>
    <!-- end password protection -->

  </div>
  @if(auth()->user()->can('edit general settings'))
    @include ('zpanel.partials._form_actions', ['path' => route('store.preferences.index')])
  @endif
</form>
@endsection
@section('scripts') 


@endsection

@section('page_scripts')
<script>


</script>
@endsection
