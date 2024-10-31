@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{ asset('assets/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="{{ route('settings.index') }}">setting</a></li>
 	<li class="breadcrumb-item">general</li>
 	<li class="pull-right"><a href="javascript:void(0);" class="btn guide-me"><i class="aapl-lifebuoy"></i> Guide me</a></li>
</ol>
<!-- END BREADCRUMB -->

<form action="{{ $action }}" method="post" enctype="multipart/form-data" autocomplete="off">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
  <div  class="card card-transparent">
    <div class="card-header">
      <h1 class="section-title">General Settings</h1>
    </div>
    @if(session()->has('info'))
    <div class="row card-block">
      <div class="col-lg-12">
        <div class="alert alert-info bordered" role="alert">
          <p class="pull-left">{{ session('info') }}</p>
          <button class="close" data-dismiss="alert"></button>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    @endif
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Store Details
          </h6>
          <div class="p-r-30">
            <p>
             Shopbox and your customers will use this information to contact you.
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
                          <div id="storeName" class="col-sm-12 form-group{{ $errors->has('store_name') ? ' has-danger' : '' }}">
                            <label for="store_name">Store name</label>
                            <input type="text" class="form-control{{ $errors->has('store_name') ? ' form-control-danger' : '' }}" id="store_name" name="store_name" value="{{ old('store_name', $store->store_name) }}" required>
                            @if($errors->has('store_name'))
                              <div class="form-control-feedback">{{ $errors->first('store_name') }}</div>
                            @endif
                          </div>
                          <div class="row">
                            <div id="accountEmail" class="col-sm-6 form-group{{ $errors->has('account_email') ? ' has-danger' : '' }}">
                              <label for="account_email">Account email</label>
                              <input type="email" id="account_email" name="account_email" class="form-control{{ $errors->has('account_email') ? ' form-control-danger' : '' }}" aria-describedby="accountEmailHelp" value="{{ old('account_email', $store->store_email) }}">
                              <small id="accountEmailHelp" class="form-text text-muted">We'll use this address if we need to contact you about your account.</small>
                              @if($errors->has('account_email'))
                                <div class="form-control-feedback">{{ $errors->first('account_email') }}</div>
                              @endif
                            </div>
                            <div id="customerEmail" class="col-sm-6 form-group{{ $errors->has('customer_email') ? ' has-danger' : '' }}">
                              <label for="customer_email">Customer email</label>
                              <input type="email" id="customer_email"  name="customer_email" class="form-control{{ $errors->has('customer_email') ? ' form-control-danger' : '' }}" aria-describedby="customerEmailHelp" value="{{ old('customer_email', $store->trans_email) }}">
                              <small id="customerEmailHelp" class="form-text text-muted">Your customers will see this address if you email them.</small>
                              @if($errors->has('customer_email'))
                                <div class="form-control-feedback">{{ $errors->first('customer_email') }}</div>
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
    </div>

    <!-- start store logo favicon -->
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Logo / Favicon
          </h6>
          <div class="p-r-30">
            <p>
             Upload images to use as your logo and favicon.
            </p>
            <p>
               This logo will appear in your emails and invoices. To add a logo to your storefront, go to your themes and customize your active theme.
            </p>
            <p>A favicon is the small icon which appears on browser tabs.</p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div id="uploadLogo" class="card card-transparent">
                    <div class="card-block no-padding">
                      <div class="card card-default">
                        <div class="card-block">
                          <div class="row justify-content-between">
                            <div class="col-md-6">
                              <label for="logo">Logo</label><br>
                              <img id="logoPreview" src="{{ $store->setting->logo ? asset('stores/'.$store->domain.'/img/'.$store->setting->logo) : 'https://via.placeholder.com/200x200' }}" alt="{{ $store->store_name }}" class="img-fluid" width="200">
                              <div class="form-group mt-3{{ $errors->has('logo') ? ' has-danger' : '' }}">
                                <div class="styled-file-input">
                                  <div class="btn button">
                                    <input type="file" accept="image/*" id="logo" class="form-control{{ $errors->has('logo') ? ' form-control-danger' : '' }}" name="logo" onchange="Shopbox.logoPreview(this)" style="overflow: hidden;">
                                    <label class="mb-0">Upload image</label>
                                  </div>
                                </div>
                                @if($errors->has('logo'))
                                  <div class="form-control-feedback">{{ $errors->first('logo') }}</div>
                                @endif
                              </div>
                            </div>
                            <div class="col-md-6">
                              <label for="favicon">Favicon</label><br>
                              <img id="faviconPreview" src="{{ $store->setting->favicon ? asset('stores').'/'.session('store')->domain.'/img/'.$store->setting->favicon : 'https://via.placeholder.com/48' }}" alt="{{ $store->store_name }}" class="img-fluid" width="48">
                              <p class="mt-3">
                                Supported file types: JPG, GIF, PNG
                                Recommended sizes: 16 x 16px, 32 x 32px, 48 x 48px
                              </p>
                              <div class="form-group mt-3{{ $errors->has('favicon') ? ' has-danger' : '' }}">
                                <div class="styled-file-input">
                                  <div class="btn button">
                                    <input type="file" accept="image/*" id="favicon" class="form-control{{ $errors->has('favicon') ? ' form-control-danger' : '' }}" name="favicon" onchange="Shopbox.faviconPreview(this)" style="overflow: hidden;">
                                    <label class="mb-0">Upload image</label>
                                  </div>
                                </div>
                                @if($errors->has('favicon'))
                                  <div class="form-control-feedback">{{ $errors->first('favicon') }}</div>
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
        </div>
    </div>
    <!-- end store logo / favicon -->
    
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Store billing address
          </h6>
          <div class="p-r-30">
            <p>
             This address will appear on your invoices.
            </p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div id="storeAddress" class="card-block no-padding">
                      <div class="card card-default">
                        <div class="card-block">
                          <div class="form-group{{ $errors->has('company') ? ' has-danger' : '' }}">
                            <label for="company">Legal Business Name</label>
                            <input type="text" class="form-control{{ $errors->has('company') ? ' form-control-danger' : '' }}" id="company" name="company" value="{{ old('company', $store->company) }}">
                            @if($errors->has('company'))
                                <div class="form-control-feedback">{{ $errors->first('company') }}</div>
                            @endif
                          </div>
                          <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control{{ $errors->has('phone') ? ' form-control-danger' : '' }}" id="phone" name="phone" value="{{ old('phone', $store->phone) }}">
                            @if($errors->has('phone'))
                                <div class="form-control-feedback">{{ $errors->first('phone') }}</div>
                            @endif
                          </div>
                          <div class="form-group{{ $errors->has('address1') ? ' has-danger' : '' }}">
                            <label for="address1">Legal Address 1</label>
                            <input type="text" class="form-control{{ $errors->has('address1') ? ' form-control-danger' : '' }}" id="address1" name="address1" value="{{ old('address1', $store->address1) }}">
                            @if($errors->has('phone'))
                                <div class="form-control-feedback">{{ $errors->first('address1') }}</div>
                            @endif
                          </div>
                          <div class="form-group{{ $errors->has('address2') ? ' has-danger' : '' }}">
                            <label for="address2">Legal Address 2</label>
                            <input type="text" class="form-control{{ $errors->has('address2') ? ' form-control-danger' : '' }}" id="address2" name="address2" value="{{ old('address2', $store->address2) }}">
                            @if($errors->has('phone'))
                                <div class="form-control-feedback">{{ $errors->first('address2') }}</div>
                            @endif
                          </div>
                          <div class="row">
                            <div class="col-sm-6 form-group{{ $errors->has('city') ? ' has-danger' : '' }}">
                              <label for="city">City</label>
                              <input type="text" id="city" name="city" class="form-control{{ $errors->has('city') ? ' form-control-danger' : '' }}" value="{{ old('city', $store->city) }}">
                              @if($errors->has('phone'))
                                <div class="form-control-feedback">{{ $errors->first('city') }}</div>
                              @endif
                            </div>
                            <div class="col-sm-6 form-group{{ $errors->has('zip_code') ? ' has-danger' : '' }}">
                              <label for="zip_code">Postal / ZIP code</label>
                              <input type="text" id="zip_code"  name="zip_code" class="form-control{{ $errors->has('zip_code') ? ' form-control-danger' : '' }}" value="{{ old('zip_code', $store->postcode) }}">
                              @if($errors->has('phone'))
                                <div class="form-control-feedback">{{ $errors->first('zip_code') }}</div>
                              @endif
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-6 form-group{{ $errors->has('country') ? ' has-danger' : '' }}">
                              <label for="country">Country</label>
                              <select id="country" name="country" class="full-width form-control{{ $errors->has('country') ? ' form-control-danger' : '' }}" data-init-plugin="select2">
                                @if($countries->count())
                                  @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @if($store->country_id == $country->id) selected @endif>{{ $country->name }}</option>
                                  @endforeach
                                @endif
                              </select>
                              @if($errors->has('phone'))
                                <div class="form-control-feedback">{{ $errors->first('country') }}</div>
                              @endif
                            </div> 
                            <div class="col-sm-6 form-group{{ $errors->has('state') ? ' has-danger' : '' }}">
                              <label for="state">State</label>
                              <select id="state" name="state" class="full-width form-control{{ $errors->has('state') ? ' form-control-danger' : '' }}" data-init-plugin="select2">
                                @if($store->country->states->count())
                                  @foreach($store->country->states as $state)
                                    <option value="{{ $state->id }}" @if($store->state_id == $state->id) selected @endif>{{ $state->name }}</option>
                                  @endforeach
                                @else
                                  <option value="">None</option>
                                @endif
                              </select>
                              @if($errors->has('phone'))
                                <div class="form-control-feedback">{{ $errors->first('state') }}</div>
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
    </div>
    <!-- end store address -->
    
    @if(auth()->user()->can('custom domain'))
     <!-- start return settings -->
    <div id="domain_connect" class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Domain
          </h6>
          <div class="p-r-30">
            <p>Connect existing domain</p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div class="card card-warning">
                        <div class="card-block">
                          <div class="d-flex justify-content-between align-items-center">
                            <h6 class="font-weight-bold">Primary Domain</h6>
                            <a href="{{ route('settings.domain.index') }}">Change domain</a>
                          </div>
                          <table class="table table-sm">
                            <thead>
                              <tr>
                                <th scope="col">Domain Name</th>
                                <th scope="col">Status</th>
                              </tr>
                            </thead>
                            <tbody>
                              @if($store->store_url)
                                <tr>
                                  <td>{{ $store->store_url }}</td>
                                  <td>
                                    @if($store->main)
                                      <span class="badge badge-pill badge-success">Connected</span>
                                    @else
                                      <span class="badge badge-pill badge-danger">Not connected</span>
                                    @endif
                                  </td>
                                </tr>
                              @else
                                <tr>
                                  <td>{{ $store->domain.'.'.config('domain.app_domain') }}</td>
                                  <td><span class="badge badge-pill badge-success">Connected</span></td>
                                </tr>
                              @endif
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>    
              </div>
          </div>
        </div>
    </div>
    <!-- end return settings -->
    @endif

    <!-- start standards and formats -->
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Standards and formats
          </h6>
          <div class="p-r-30">
            <p>
             Standards and formats are used to calculate product prices, shipping weights, and order times.
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
                          <div class="row">
                            <div id="timeZone" class="col-sm-6 form-group{{ $errors->has('timezone') ? ' has-danger' : '' }}">
                              <label for="timezone">Timezone</label>
                              <select id="timezone" name="timezone" class="full-width form-control{{ $errors->has('timezone') ? ' form-control-danger' : '' }}" data-init-plugin="select2">
                                @if($timezones->count())
                                  @foreach($timezones as $timezone)
                                    <option value="{{ $timezone->id }}" @if($store->setting->timezone_id == $timezone->id) selected @endif>{{ $timezone->timezone }}</option>
                                  @endforeach
                                @endif
                              </select>
                              @if($errors->has('timezone'))
                                <div class="form-control-feedback">{{ $errors->first('timezone') }}</div>
                              @endif
                            </div>
                            <div id="storeCurrency" class="col-sm-6 form-group{{ $errors->has('store_currency') ? ' has-danger' : '' }}">
                              <label for="store_currency">Store currency</label>
                              <select name="store_currency" id="store_currency" class="form-control full-width{{ $errors->has('store_currency') ? ' form-control-danger' : '' }}" data-init-plugin="select2">
                                @if($currencies->count())
                                  @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" @if($store->setting->store_currency == $currency->id) selected @endif>{{ $currency->name }}</option>
                                  @endforeach
                                @endif
                              </select>
                              @if($errors->has('store_currency'))
                                <div class="form-control-feedback">{{ $errors->first('store_currency') }}</div>
                              @endif
                            </div>
                          </div>
                          <div class="row">
                            <div id="orderPrefix" class="col-sm-6 form-group{{ $errors->has('order_prefix') ? ' has-danger' : '' }}">
                              <label for="order_prefix">Order prefix</label>
                              <input type="text" class="form-control{{ $errors->has('order_prefix') ? ' form-control-danger' : '' }}" id="order_prefix" name="order_prefix" value="{{ $store->setting->order_id_prefix }}" maxlength="255">
                              @if($errors->has('order_prefix'))
                                <div class="form-control-feedback">{{ $errors->first('order_prefix') }}</div>
                              @endif
                            </div>
                            <div class="col-sm-6 form-group{{ $errors->has('order_suffix') ? ' has-danger' : '' }}">
                              <label for="order_suffix">Order suffix</label>
                              <input type="text" class="form-control{{ $errors->has('order_prefix') ? ' form-control-danger' : '' }}" id="order_suffix" name="order_suffix" value="{{ $store->setting->order_id_suffix }}" maxlength="255">
                              @if($errors->has('order_suffix'))
                                <div class="form-control-feedback">{{ $errors->first('order_suffix') }}</div>
                              @endif
                            </div>
                          </div>
                          <div class="row">
                            <div id="weightMesurement" class="col-sm-6 form-group{{ $errors->has('weight_unit') ? ' has-danger' : '' }}">
                              <label for="weight_unit">Weight Measurement</label>
                              <select name="weight_unit" id="weight_unit" class="form-control{{ $errors->has('weight_unit') ? ' form-control-danger' : '' }}">
                                @if($units->count())
                                  @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" @if($store->setting->weight_unit_id == $unit->id) selected @endif>{{ $unit->weight }}</option>
                                  @endforeach
                                @endif
                              </select>
                              @if($errors->has('weight_unit'))
                                <div class="form-control-feedback">{{ $errors->first('weight_unit') }}</div>
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
    </div>
    <!-- end standards and formats -->

     <!-- start return settings -->
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Returns
          </h6>
          <div class="p-r-30">
            <p>
             The returns system will allow your customers to request a return on items they've purchased.
            </p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div id="enableReturns" class="card-block no-padding">
                      <div class="card card-default">
                        <div class="card-block">
                          <div class="form-group{{ $errors->has('enable_return') ? ' has-danger' : '' }}">
                            <div class="checkbox check-info">
                              <input type="checkbox" value="1" id="enable_returns" name="enable_returns" @if($store->setting->enable_returns) checked @endif @if(!auth()->user()->can('accept returns')) disabled @endif>
                              <label for="enable_returns">Enable returns</label>
                            </div>
                            @if($errors->has('enable_return'))
                              <div class="form-control-feedback">{{ $errors->first('enable_return') }}</div>
                            @endif
                          </div>
                          <div class="form-group ml-4{{ $errors->has('partial_returns') ? ' has-danger' : '' }}">
                            <div class="checkbox check-info">
                              <input type="checkbox" value="1" id="partial_returns" name="partial_returns"
                              @if(!auth()->user()->can('accept partial returns')) disabled @endif>
                              <label for="partial_returns">accept partial returns</label>
                            </div>
                            @if($errors->has('partial_returns'))
                              <div class="form-control-feedback">{{ $errors->first('partial_returns') }}</div>
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
    <!-- end return settings -->

  </div>
  @if(auth()->user()->can('edit general settings'))
    @include ('zpanel.partials._form_actions', ['path' => route('settings.edit')])
  @endif
</form>
@endsection
@section('scripts') 

<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-autonumeric/autoNumeric.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/switchery/js/switchery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js') }}"></script>

@endsection

@section('page_scripts')
<script>
  $(document).ready(function() {
      Shopbox.init();
      
  });

  function startTour()
  {
    var intro = introJs();
    intro.setOptions({
      steps: [
        {
          element: document.querySelector('#storeName'),
          intro: "<h4>Store Name</h4><p>Amend your store name if required.</p>"
        },
		{
          element: document.querySelector('#accountEmail'),
          intro: "<h4>Account Email </h4><p>Will be used by ShopBox to send you notifications regarding your account such as subscription invoices, expiration notices and critical system notifications/updates etc. This will not be visible to public.</p>"
        },
		{
          element: document.querySelector('#customerEmail'),
          intro: "<h4>Customer Email </h4><p>Is your public email which will appear on customers’ order confirmation emails, store contact information etc.</p>"
        },
		{
          element: document.querySelector('#uploadLogo'),
          intro: "<p>Add your Logo and Favicon.</p>"
        },
		{
          element: document.querySelector('#storeAddress'),
          intro: "<p>Verify/enter your store address details.</p>"
        },
		{
          element: document.querySelector('#timeZone'),
          intro: "<p>Order times will be recorded according to the time zone you set.</p>"
        },
		{
          element: document.querySelector('#storeCurrency'),
          intro: "<p>Your store currency will be the default currency of your online store.</p>"
        },
		{
          element: document.querySelector('#orderPrefix'),
          intro: "<p>Your order number will be a sequential integer starting from 1. The start point cannot be altered, however prefix and/or suffix can be added before and after this sequence respectively.</p>"
        },
		{
          element: document.querySelector('#weightMesurement'),
          intro: "<p>Weight is necessary for shipping rate calculations. Depending on your shipper requirements, you can set the weight unit accordingly.</p>"
        },
		{
          element: document.querySelector('#enableReturns'),
          intro: "<p>Choose to activate returns for your store. Partial returns can be activated on orders where only certain products are to be returned and not the entire order.</p>"
        }
      ],
      showStepNumbers: false,
      exitOnEsc: false
    });
    intro.start();
  }

  $(document).on('click', '.guide-me', function(e){  
       startTour();
  });
  
</script>
@endsection
