@if(session()->has('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@elseif(session()->has('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<form action="{{ route('stores.contact', $page) }}" method="post" autocomplete="off">
    {{ csrf_field() }}
    <div class="form-group">
        <input id="name" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" placeholder="Name *">
        @if($errors->has('name'))
            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
        @endif
    </div>
    <div class="form-group">
        <input id="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" placeholder="Email *">
        @if($errors->has('email'))
            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
        @endif
    </div>
    <div class="form-group">
      <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject">
    </div>
    <div class="form-group">
        <textarea id="content" name="content" class="form-control" style="height: 150px;" placeholder="Message *"></textarea>
    </div>
    <div class="form-group">
        <input class="btn_1 full-width" type="submit" value="Submit">
    </div>
    <div id="g-recaptcha" data-sitekey="{{ $store->setting->captcha_site_key ?: config('services.recaptcha.site_key') }}" data-size="invisible" data-action="{{ $store->domain }}_contactpage" data-badge="inline"></div>
</form>