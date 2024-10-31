@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
 	<li class="breadcrumb-item"><a href="{{ route('settings.index') }}">setting</a></li>
 	<li class="breadcrumb-item">payments</li>
	<li class="pull-right"><a href="javascript:void(0);" class="btn guide-me"><i class="aapl-lifebuoy"></i> Guide me</a></li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent">
  <div class="card-header">
    <h1 class="section-title">Payment methods</h1>
  </div>
  <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
        <h6 class="ui-subheader">
         Accept payments
        </h6>
        <div class="p-r-30">
          <p>
           Enable payment providers to accept credit cards, PayPal, and other payment methods during checkout.
          </p>
          <p>
            Choose a payment provider to accept payments for orders.
          </p>
        </div>
      </div>
      <div class="col-lg-8 sm-no-padding">
        <div class="row">
            <div class="col-lg-12">
               <div id="manualPayments" class="card card-transparent">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default">
                      <div class="card-header">
                        <div class="card-title"><span class="font-weight-bold">manual payments</span>
                        </div>
                      </div>
                      <div class="card-block">
                        @if($offline_payments->count())
                          @foreach($offline_payments as $payment)
                             <div class="row justify-content-between mb-4">
                                <div class="col-6">
                                  <span class="mr-3">{{ $payment->plugin_name }}</span>
                                  @if($payment->logo)
                                  <img src="{{ asset('modules/'.$payment->alias.'/'.$payment->logo) }}" alt="{{ $payment->plugin_name }}" height="23">
                                  @endif
                                </div>
                                @if(auth()->user()->can('active disable payments'))
                                <div class="col-2">
                                    <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="info" id="{{ $payment->alias }}" class="payment_status"
                                    @if(session('store')->payments()->where('plugin_id', $payment->id)->first() && session('store')->payments()->where('plugin_id', $payment->id)->first()->active)
                                      checked 
                                    @endif
                                    data-id="{{ $payment->alias }}">
                                </div>
                                @endif
                                @if(auth()->user()->can('setup payments'))
                                <div class="col-4">
                                  <a href="{{ url('merchant/store/payments').'/'.$payment->alias }}" class="btn btn-xs btn-action-add pull-right sm-pull-reset"><i class="aapl-wrench"> setup</i></a>
                                </div>
                                @endif
                            </div>
                          @endforeach
                        @endif  
                      </div>
                    </div>
                  </div>
                </div>    
            </div>
        </div>
        
      @if($online_payments->count())
        <div class="row">
            <div class="col-lg-12">
               <div id="onlinePayments" class="card card-transparent">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default">
                      <div class="card-header">
                        <div class="card-title"><span class="font-weight-bold">online payment methods</span>
                        </div>
                      </div>
                      <div class="card-block">
                        @foreach($online_payments as $payment)
                           <div class="row justify-content-between align-items-center mb-4">
                              <div class="col-6">
                                <span class="mr-3">{{ $payment->plugin_name }}</span>
                                @if($payment->alias === 'shopboxpay')
                                <em><a href="{{ route('payouts.index') }}">Payouts</a></em>
                                @endif
                                @if($payment->logo)
                                <img src="{{ asset('modules/'.$payment->alias.'/'.$payment->logo) }}" alt="{{ $payment->plugin_name }}" height="23">
                                @endif
                              </div>
                              @if(auth()->user()->can('active disable payments') && session('store')->payments()->where('plugin_id', $payment->id)->first()) 
                              <div class="col-2">
                                  <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="info" id="{{ $payment->alias }}" class="payment_status"
                                  @if(session('store')->payments()->where('plugin_id', $payment->id)->first() && session('store')->payments()->where('plugin_id', $payment->id)->first()->active)
                                    checked 
                                  @endif
                                   data-id="{{ $payment->alias }}">
                              </div>
                              @endif
                              @if(auth()->user()->can('setup payments'))
                              <div class="col-4">
                                <a href="{{ url('merchant/store/payments').'/'.$payment->slug }}" class="btn btn-xs btn-action-add pull-right sm-pull-reset"><i class="fa fa-wrench"> setup</i></a>
                              </div>
                              @endif
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>    
            </div>
        </div>
      @endif
      </div>
  </div>
</div>

@endsection

@section('scripts') 
<script src="{{ asset('assets/plugins/switchery/js/switchery.min.js') }}" type="text/javascript"></script>
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
          element: document.querySelector('#manualPayments'),
          intro: "<h4>Manual Payments</h4><p>Select the payment options for your customers to pay for their products.<br><br> To activate a manual payment method of your choosing, be sure to set the toggle to active. <br><br>Further configurations such as adding bank details for the bank transfer method can be done by clicking on the respective setup button.<br><br> Note: Please check with your delivery partner to determine whether COD facility is available.</p>"
        },
		{
          element: document.querySelector('#onlinePayments'),
          intro: "<h4>Online Payments</h4><p>Online payment methods would require a more detailed setup compared to the manual payments above. These would enable your customers to make a payment via e-payment methods such as credit card.<br><br>Registration and/or approval from the e-payment provider (including ShopBoxPay) may be required prior to integrating with your store.<br><br>Note: Integrating external e-payment providers such as PayPal is also possible; visit the marketplace to view available providers.</p>"
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
