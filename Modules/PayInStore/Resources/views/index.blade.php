@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">store</li>
  <li class="breadcrumb-item"><a href="{{ route('payments.index') }}">payments</a></li>
  <li class="breadcrumb-item active">pay in store</li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header">
    <h1 class="section-title">Payment methods</h1>
  </div>
  <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
        <h6 class="ui-subheader">
         Pay In Store
        </h6>
        <div class="p-r-30">
          <p>
            Accept payments in store.
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
                          <h4>Pay In Store</h4>
                        </div>
                      </div>
                      <div class="card-block">
                        <form action="{{ route('payinstore.store') }}" method="post" autocomplete="off">
                          {{ csrf_field() }}
                          <div class="form-group{{ $errors->has('display_name') ? ' has-danger' : '' }}">
                            <label for="display_name">Display Name</label>
                            <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="If you enable more than one payment options then your customers will need to choose which payment method the want to use. The text in this box will be used to describe this payment method on your site.">
                              <i class="fa fa-question-circle"></i>
                            </span>
                            <input type="text" id="display_name" name="display_name" class="form-control{{ $errors->has('display_name') ? ' form-control-danger' : '' }}" value="{{ $display_name ? $display_name : old('display_name') }}" required>
                            @if($errors->has('display_name'))
                              <div class="form-control-feedback">{{ $errors->first('display_name') }}</div>
                            @endif
                          </div>

                          <div class="form-group{{ $errors->has('payment_instruction') ? ' has-danger' : '' }}">
                            <label for="payment_instruction">payment instruction</label>
                            <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="If a customer chooses to pay in-store then he will be shown the text you type into this box once he completes his order. You should include your store address so he can come to your store to pay, and also any information relating to the order including how you will contact the customer when his order is ready to pay for and pick up, etc.">
                              <i class="fa fa-question-circle"></i>
                            </span>
                            <textarea name="payment_instruction" id="payment_instruction" cols="30" rows="10" class="form-control{{ $errors->has('payment_instruction') ? ' form-control-danger' : '' }}" required>{{ array_has($configs, 'PAYINSTORE_PAYMENT_INSTRUCTION') ?  $configs['PAYINSTORE_PAYMENT_INSTRUCTION'] : old('payment_instruction') }}</textarea>
  
                            @if($errors->has('payment_instruction'))
                              <div class="form-control-feedback">{{ $errors->first('payment_instruction') }}</div>
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
