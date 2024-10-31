<div class="row">
  <div class="col-lg-4 col-md-4" style="padding-top: 35px;">
      <div class="store-information">
          <div class="store-title">
            @if($settings['contact_title'])
              <h4>{{ $settings['contact_title'] }}</h4>
            @endif
              <div class="communication-info">
                @if($settings['contact_address'])
                  <div class="single-communication">
                      <div class="communication-icon">
                          <i class="zmdi zmdi-pin"></i>
                      </div>
                      <div class="communication-text">{{ $settings['contact_address'] }}</div>
                  </div>
                @endif
                
                @if($settings['contact_phone'])
                  <div class="single-communication">
                      <div class="communication-icon">
                          <i class="zmdi zmdi-phone"></i>
                      </div>
                      <div class="communication-text">{{ $settings['contact_phone'] }}</div>
                  </div>
                @endif
                
                @if($settings['contact_email'])
                  <div class="single-communication">
                      <div class="communication-icon">
                          <i class="zmdi zmdi-email"></i>
                      </div>
                      <div class="communication-text">{{ $settings['contact_email'] }}</div>
                  </div>
                @endif
              </div>
          </div>
      </div>
  </div>
  @if($page->enable_form)
    <div class="col-lg-8 col-md-8">
      <div class="content-wrapper">
          <div class="page-content">
              <div class="contact-form">
                <div class="contact-form-title">
                    <h3>Contact us</h3>
                </div>
                @if(session()->has('success'))
                  <div class="alert alert-success">
                    <i class="fa fa-check-circle-o"></i> {{ session('success') }}
                  </div>
                @elseif(session()->has('error'))
                  <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                  </div>
                @endif
                <form action="{{ route('stores.contact', $page) }}" method="post" autocomplete="off">
                  {{ csrf_field() }}
                  <div class="row">
                      <div class="col-lg-6">
                          <div class="form-group contact-form-style mb-20">
                              <input type="text" id="name" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Name">

                              @if($errors->has('name'))
                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                              @endif
                          </div>
                      </div>
                      <div class="col-lg-6">
                          <div class="form-group contact-form-style mb-20">
                              <input type="email" id="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"placeholder="Email Address">

                              @if($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                              @endif
                          </div>
                      </div>
                      <div class="col-lg-12">
                          <div class="form-group contact-form-style mb-20">
                              <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject">
                          </div>
                      </div>
                      <div class="col-lg-12">
                          <div class="form-group contact-form-style">
                              <textarea name="content" id="content" name="content" class="form-control" placeholder="Message"></textarea>
                              <button class="default-btn" type="submit"><span>SEND MESSAGE</span></button>
                          </div>
                          <div id="g-recaptcha" data-sitekey="{{ $store->setting->captcha_site_key ?: config('services.recaptcha.site_key') }}" data-size="invisible" data-action="{{ $store->domain }}_contactpage" data-badge="inline"></div>
                      </div>
                  </div>
                </form>
              </div>
          </div>
      </div>
    </div>
  @endif
</div>