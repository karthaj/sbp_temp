@extends('layouts.zpanel')

@section('content')

<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item active">settings</li>
</ol>
<!-- END BREADCRUMB --> 
    
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<div id="app" class="row">
  <div class="col-lg-12">
    <div class="card card-transparent">
      <div class="card-header row">
        <div class="col-lg-10 col-md-9 col-sm-8">
            <h1 class="section-title">Settings</h1>
        </div>
      </div>
      <div class="card-block">
        <div class="row">
          <div class="col-md-12">
            <div data-pages="card" class="card card-default">
              <div  class="card-block">
                <div class="row">
					<div class="col-md-4">
                    <a class="blacklink" href="{{ route('settings.edit') }}">
                      <div class="card card-outline">
                        <div class="media card-block">
                          <i class="d-flex mr-3 aapl-cog h2"></i>
                          <div class="media-body">
                            <h6 class="mt-0 semi-bold">General</h6>
                            Basic requirements and settings for your business.
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>	
				 <div class="col-md-4">
                    <a class="blacklink" href="{{ route('account.users') }}">
                      <div class="card card-outline">
                        <div class="media card-block">
                          <i class="d-flex mr-3 aapl-user h2"></i>
                          <div class="media-body">
                            <h6 class="mt-0 semi-bold">Account</h6>
                            Settings related to your store user accounts permissions.
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
				 <div class="col-md-4">
                    <a class="blacklink" href="{{ route('stores.index') }}">
                      <div class="card card-outline">
                        <div class="media card-block">
                          <i class="d-flex mr-3 aapl-map-marker h2"></i>
                          <div class="media-body">
                            <h6 class="mt-0 semi-bold">Stores</h6>
                            Create additional store locations for your business.
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
                <br>
                <div class="row">
				 <div class="col-md-4">
                    <a class="blacklink" href="{{ route('tax.zones.index') }}">
                      <div class="card card-outline">
                        <div class="media card-block">
                          <i class="d-flex mr-3 aapl-percent-circle h2"></i>
                          <div class="media-body">
                            <h6 class="mt-0 semi-bold">Tax</h6>
                            Enable taxes to be added to your taxable products.
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-md-4">
                    <a class="blacklink" href="{{ route('payments.index') }}">
                      <div class="card card-outline">
                        <div class="media card-block">
                          <i class="d-flex mr-3 aapl-credit-card h2"></i>
                          <div class="media-body">
                            <h6 class="mt-0 semi-bold">Payments</h6>
                            Configure various payment options for your customers.
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-md-4">
                    <a class="blacklink" href="{{ route('shipping.index') }}">
                      <div class="card card-outline">
                        <div class="media card-block">
                          <i class="d-flex mr-3 aapl-truck h2"></i>
                          <div class="media-body">
                            <h6 class="mt-0 semi-bold">Shipping</h6>
                            Set up how to deliver your products to your customers.
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                </div> 
				<br>
                <div class="row">
                  <div class="col-md-4">
                    <a class="blacklink" class="black" href="{{ route('settings.billing.index') }}">
                      <div class="card card-outline">
                        <div class="media card-block">
                          <i class="d-flex mr-3 aapl-receipt h2"></i>
                          <div class="media-body">
                            <h6 class="mt-0 semi-bold">Billing</h6>
                            Subscription and billing details.
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                  
                  
                </div> <!--row-->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END PLACE PAGE CONTENT HERE -->

@endsection

@section('scripts') 
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>

@endsection



