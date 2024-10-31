@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">store</li>
  <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">payments</a></li>
  <li class="breadcrumb-item active">hnb</li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header">
    <h1 class="section-title">Payment methods</h1>
  </div>
  <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
        <h6 class="ui-subheader">
         HNB Payment
        </h6>
        <div class="p-r-30">
          <p>
            Enable HNB Payment into your store to accept credit cards.
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
                          <div class="media">
                            @if(config('hnb.cover'))
                              <img class="d-flex mr-3 img-fluid" src="{{ asset('modules/hnb/'.config('hnb.cover')) }}" height="96" width="96" alt="{{ config('hnb.name') }}">
                            @else
                              <img class="d-flex mr-3 img-fluid" src="https://via.placeholder.com/96?text={{ config('hnb.name') }}" height="96" width="96" alt="{{ $plugin['name'] }}">
                            @endif
                            <div class="media-body">
                              <h4>{{ config('hnb.name') }}</h4>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card-block">
                        <form action="{{ route('hnb.store') }}" method="post" autocomplete="off">
                          {{ csrf_field() }}
                          <div class="form-group{{ $errors->has('display_name') ? ' has-danger' : '' }}">
                            <label for="display_name">Display Name</label>
                            <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="If you enable more than one payment options then your customers will need to choose which payment method the want to use. The text in this box will be used to describe this payment method on your site.">
                              <i class="fa fa-question-circle"></i>
                            </span>
                            <input type="text" id="display_name" name="display_name" class="form-control{{ $errors->has('display_name') ? ' form-control-danger' : '' }}" value="{{ $display_name ? $display_name : old('display_name') }}">
                            @if($errors->has('display_name'))
                              <div class="form-control-feedback">{{ $errors->first('display_name') }}</div>
                            @endif
                          </div>

                          <div class="form-group{{ $errors->has('merchant_id') ? ' has-danger' : '' }}">
                            <label for="merchant_id">Merchant ID</label>
                            <input type="text" id="merchant_id" name="merchant_id" class="form-control{{ $errors->has('merchant_id') ? ' form-control-danger' : '' }}" value="{{ array_has($configs, 'HNB_MERCHANT_ID') ?  $configs['HNB_MERCHANT_ID'] : old('merchant_id') }}">
                            @if($errors->has('merchant_id'))
                              <div class="form-control-feedback">{{ $errors->first('merchant_id') }}</div>
                            @endif
                          </div>

                          <div class="form-group{{ $errors->has('acquirer_id') ? ' has-danger' : '' }}">
                            <label for="acquirer_id" >Acquirer ID</label>
                            <input type="text" id="acquirer_id" name="acquirer_id" class="form-control{{ $errors->has('acquirer_id') ? ' form-control-danger' : '' }}" value="{{ array_has($configs, 'HNB_ACQUIRER_ID') ?  $configs['HNB_ACQUIRER_ID'] : old('merchant_id') }}">
                            @if($errors->has('acquirer_id'))
                              <div class="form-control-feedback">{{ $errors->first('acquirer_id') }}</div>
                            @endif
                          </div>

                          <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control{{ $errors->has('password') ? ' form-control-danger' : '' }}" value="{{ array_has($configs, 'HNB_MERCHANT_PASSWORD') ?  $configs['HNB_MERCHANT_PASSWORD'] : old('merchant_id') }}">
                            @if($errors->has('password'))
                              <div class="form-control-feedback">{{ $errors->first('password') }}</div>
                            @endif
                          </div>

                          <div class="form-group{{ $errors->has('currency') ? ' has-danger' : '' }}">
                            <label for="currency" >Currency</label>
                            <select id="currency" class="full-width" data-init-plugin="select2" name="currency">
                              <option value="">Select currency</option>
                              @if($currencies->count())
                                @foreach($currencies as $currency)
                                  <option value="{{ $currency->code }}"
                                    @if(array_has($configs, 'HNB_CURRENCY')) @if($configs['HNB_CURRENCY'] == $currency->code) selected @endif @endif>
                                    {{ $currency->name }}
                                  </option>
                                @endforeach
                              @endif
                            </select>
                            @if($errors->has('currency'))
                              <div class="form-control-feedback">{{ $errors->first('currency') }}</div>
                            @endif
                          </div>

                          <div class="form-group{{ $errors->has('test_mode') ? ' has-danger' : '' }}">
                            <label for="test_mode" >Test Mode</label>
                            <div class="radio radio-info">
                              <input type="radio" value="1" name="test_mode" id="test_mode_yes" @if(array_has($configs, 'HNB_TEST_MODE')) @if($configs['HNB_TEST_MODE'] == 1) checked @endif @endif @if(!array_has($configs, 'HNB_TEST_MODE')) checked @endif>
                              <label for="test_mode_yes">Yes</label>
                              <input type="radio" value="0" name="test_mode" id="test_mode_no" @if(array_has($configs, 'HNB_TEST_MODE')) @if($configs['HNB_TEST_MODE'] == 0) checked @endif @endif>
                              <label for="test_mode_no">No</label>
                            </div>
                            @if($errors->has('test_mode'))
                              <div class="form-control-feedback">{{ $errors->first('test_mode') }}</div>
                            @endif
                          </div>

                          @include ('zpanel.partials._form_actions', ['path' => route('payments.index')])
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
            </div>    
        </div>
      </div>
  </div>
</div>

@endsection

@section('scripts') 
<script src="{{ asset('assets/plugins/switchery/js/switchery.min.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
@endsection

@section('page_scripts')

@endsection
