@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">store</li>
  <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">payments</a></li>
  <li class="breadcrumb-item active">shopbox pay</li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header">
    <h1 class="section-title">Payment methods</h1>
  </div>
  <form action="{{ route('shopboxpay.update') }}" method="post">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Shopbox Pay
          </h6>
          <div class="p-r-30">
            <p>
              Enable Shopbox Pay into your store to accept payments for orders.
            </p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div id="card-advance" class="card card-default">
                        <div class="card-header">
                          <div class="card-title">
                            <img src="{{ asset('assets/img/ShopBoxPay_Logo.svg') }}" alt="shopbox pay" class="img-fluid">
                          </div>
                        </div>
                        <div class="card-block">
                          
                            @if($shopboxpay && $shopboxpay->payments->count())
                              @foreach($shopboxpay->payments as $payment)
                                <input type="hidden" name="data[{{ $loop->index }}][payment]" value="{{ $payment->id }}">
                                <div class="row align-items-center justify-content-between">
                                  <div class="col-sm-6">
                                    <legend>{{ $payment->display_name }}</legend>
                                    <p>{{ $payment->plugin->description }}</p>
                                    <small>(TDR Rate: <b>{{ $payment->tdr_rate }}</b>%)</small>
                                    @if(!$payment->live)
                                      <div class="alert alert-warning mt-4">
                                        <i class="aapl-warning"></i> IPG service not active.
                                      </div>
                                    @endif
                                  </div>
                                  <input type="hidden" name="data[{{ $loop->index }}][status]" value="0">
                                  <div class="col-sm-1">
                                    <div class="form-group{{ $errors->has('live') ? ' has-error' : '' }}">
                                      <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="info" id="{{ str_slug($payment->plugin_name, '-') }}" name="data[{{ $loop->index }}][status]" value="1" @if(!$payment->live) disabled @endif @if($payment->active) checked @endif>
                                    </div>
                                  </div>
                                </div>
                                <hr>
                              @endforeach
                            @else
                              <p>No payment options found.</p>
                            @endif
                         
                        </div>
                      </div>
                    </div>
                  </div>
              </div>    
          </div>
        </div>
    </div>
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Bank Details
          </h6>
          <div class="p-r-30">
            <p>
              Fill in your bank details to get paid from Shopbox Pay
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
                          <div class="form-group">
                            <label for="account_name">account name</label>
                            <input type="text" id="account_name" name="account_name" class="form-control" value="{{ array_has($configs, 'SHOPBOXPAY_ACCOUNT_NAME') ?  $configs['SHOPBOXPAY_ACCOUNT_NAME'] : old('account_name') }}">
                          </div>

                          <div class="form-group">
                            <label for="account_number" >account number</label>
                            <input type="text" id="account_number" name="account_number" class="form-control" value="{{ array_has($configs, 'SHOPBOXPAY_ACCOUNT_NUMBER') ?  $configs['SHOPBOXPAY_ACCOUNT_NUMBER'] : old('account_number') }}">
                          </div>

                          <div class="form-group">
                            <label for="bank_name" >bank name</label>
                            <input type="text" id="bank_name" name="bank_name" class="form-control" value="{{ array_has($configs, 'SHOPBOXPAY_BANK_NAME') ?  $configs['SHOPBOXPAY_BANK_NAME'] : old('bank_name') }}">
                          </div>
                          
                          <div class="form-group">
                            <label for="bank_branch" >bank branch</label>
                            <input type="text" id="bank_branch" name="bank_branch" class="form-control" value="{{ array_has($configs, 'SHOPBOXPAY_BANK_BRANCH') ?  $configs['SHOPBOXPAY_BANK_BRANCH'] : old('bank_branch') }}">
                          </div>

                          <div class="form-group">
                            <label for="swift_code" >swift code</label>
                            <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="A SWIFT code is an international bank code that identifies particular banks worldwide. It’s also known as a Bank Identifier Code (BIC). A SWIFT code consists of 8 or 11 characters.">
                              <i class="fa fa-question-circle"></i>
                            </span>
                            <input type="text" id="swift_code" name="swift_code" class="form-control" value="{{ array_has($configs, 'SHOPBOXPAY_SWIFT_CODE') ?  $configs['SHOPBOXPAY_SWIFT_CODE'] : old('swift_code') }}">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>    
          </div>
        </div>
    </div>

    @if(starts_with(session('store')->id, 2))
    <!-- start api credentials -->
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Shopbox Pay Integration
          </h6>
          <div class="p-r-30">
            <p>
             Shopbox Pay uses these credentials to autheticate your account.
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
                            <div class="col-md-4">
                              <p>Client ID</p>
                            </div>
                            <div class="col-md-8">
                              <p>{{ session('store')->client->id }}</p>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">
                              <p>Secret</p>
                            </div>
                            <div class="col-md-8">
                              <p>
                                <code id="secret" class="mb-2">{{ session('store')->client->secret }}</code>
                                @if(auth()->user()->can('edit api settings'))
                                  <button type="button" id="btnRefresh" class="btn btn-info btn-xs ml-2"><i class="fa fa-refresh"></i> Refresh</button>
                                @endif
                              </p>
                            </div>
                          </div>
                          <div class="form-group{{ $errors->has('redirect_url') ? ' has-danger' : '' }}">
                            <label for="redirect_url">Redirect Url</label>
                            <input type="url" class="form-control{{ $errors->has('redirect_url') ? ' form-control-danger' : '' }}" id="redirect_url" name="redirect_url" value="{{ old('redirect_url', session('store')->client->redirect) }}">
                            @if($errors->has('redirect_url'))
                                <div class="form-control-feedback">{{ $errors->first('redirect_url') }}</div>
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
    <!-- end api credentials -->
    @endif
    @include ('zpanel.partials._form_actions', ['path' => route('payments.index')])
  </form>
</div>

@endsection

@section('scripts') 
<script src="{{ asset('assets/plugins/switchery/js/switchery.min.js') }}" type="text/javascript"></script>
@endsection

@section('page_scripts')
<script>
  
  $(document).on('click', '#btnRefresh', function(e) {

    axios.get("{{ url('/auth/shopbox/refresh') }}").then((response) => {
      $("#secret").html(response.data.secret);
      $('.page-content-wrapper').pgNotification({
          style: 'simple',
          message: response.data.message,
          position: 'top-right',
          timeout: 5000,
          type: "success"
      }).show();
    }).catch((error) => {
      $('.page-content-wrapper').pgNotification({
          style: 'simple',
          message: 'Something went wrong!',
          position: 'top-right',
          timeout: 5000,
          type: "danger"
      }).show();
    })

  })

</script>
@endsection
