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
  <li class="breadcrumb-item active">bank transfer</li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header">
    <h1 class="section-title">Payment methods</h1>
  </div>
  <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
        <h6 class="ui-subheader">
         Bank Transfer
        </h6>
        <div class="p-r-30">
          <p>
            Accept payments by bank transfer.
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
                          <h4>Bank Transfer</h4>
                        </div>
                      </div>
                      <div class="card-block">
                        <form action="{{ route('bank.transfer.store') }}" method="post" autocomplete="off">
                          {{ csrf_field() }}
                          <div class="form-group{{ $errors->has('display_name') ? ' has-danger' : '' }}">
                            <label for="display_name">Display Name</label>
                            <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="The contents of this box is what the customer will see as a payment method during checkout. Use the default text or enter your own to best describe this payment method to the customer. If there are multiple payment methods, the customer will select their preferred option.">
                              <i class="fa fa-question-circle"></i>
                            </span>
                            <input type="text" id="display_name" name="display_name" class="form-control{{ $errors->has('display_name') ? ' form-control-danger' : '' }}" value="{{ $display_name ? $display_name : old('display_name') }}" required>
                            @if($errors->has('display_name'))
                              <div class="form-control-feedback">{{ $errors->first('display_name') }}</div>
                            @endif
                          </div>

                          <div class="form-group{{ $errors->has('account_name') ? ' has-danger' : '' }}">
                            <label for="account_name">account name</label>
                            <input type="text" id="account_name" name="account_name" class="form-control{{ $errors->has('account_name') ? ' form-control-danger' : '' }}" value="{{ array_has($configs, 'BANK_TRANSFER_ACCOUNT_NAME') ?  $configs['BANK_TRANSFER_ACCOUNT_NAME'] : old('account_name') }}" required>
                            @if($errors->has('account_name'))
                              <div class="form-control-feedback">{{ $errors->first('account_name') }}</div>
                            @endif
                          </div>

                          <div class="form-group{{ $errors->has('account_number') ? ' has-danger' : '' }}">
                            <label for="account_number" >account number</label>
                            <input type="text" id="account_number" name="account_number" class="form-control{{ $errors->has('account_number') ? ' form-control-danger' : '' }}" value="{{ array_has($configs, 'BANK_TRANSFER_ACCOUNT_NUMBER') ?  $configs['BANK_TRANSFER_ACCOUNT_NUMBER'] : old('account_number') }}" required>
                            @if($errors->has('account_number'))
                              <div class="form-control-feedback">{{ $errors->first('account_number') }}</div>
                            @endif
                          </div>

                          <div class="form-group{{ $errors->has('bank_name') ? ' has-danger' : '' }}">
                            <label for="bank_name" >bank name</label>
                            <input type="text" id="bank_name" name="bank_name" class="form-control{{ $errors->has('bank_name') ? ' form-control-danger' : '' }}" value="{{ array_has($configs, 'BANK_TRANSFER_BANK_NAME') ?  $configs['BANK_TRANSFER_BANK_NAME'] : old('bank_name') }}" required>
                            @if($errors->has('bank_name'))
                              <div class="form-control-feedback">{{ $errors->first('bank_name') }}</div>
                            @endif
                          </div>
                          
                          <div class="form-group{{ $errors->has('bank_branch') ? ' has-danger' : '' }}">
                            <label for="bank_branch" >bank branch</label>
                            <input type="text" id="bank_branch" name="bank_branch" class="form-control{{ $errors->has('bank_branch') ? ' form-control-danger' : '' }}" value="{{ array_has($configs, 'BANK_TRANSFER_BANK_BRANCH') ?  $configs['BANK_TRANSFER_BANK_BRANCH'] : old('bank_branch') }}" required>
                            @if($errors->has('bank_branch'))
                              <div class="form-control-feedback">{{ $errors->first('bank_branch') }}</div>
                            @endif
                          </div>

                          <div class="form-group{{ $errors->has('swift_code') ? ' has-danger' : '' }}">
                            <label for="swift_code" >swift code</label>
                            <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="A SWIFT code is an international bank code that identifies particular banks worldwide. It’s also known as a Bank Identifier Code (BIC). A SWIFT code consists of 8 or 11 characters.">
                              <i class="fa fa-question-circle"></i>
                            </span>
                            <input type="text" id="swift_code" name="swift_code" class="form-control{{ $errors->has('swift_code') ? ' form-control-danger' : '' }}" value="{{ array_has($configs, 'BANK_TRANSFER_SWIFT_CODE') ?  $configs['BANK_TRANSFER_SWIFT_CODE'] : old('swift_code') }}" required>
                            @if($errors->has('swift_code'))
                              <div class="form-control-feedback">{{ $errors->first('swift_code') }}</div>
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
