@extends('layouts.zpanel')

@section('styles')

<link href="{{ asset('assets/plugins/jquery-datatable/media/css/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/plugins/datatables-responsive/css/datatables.responsive.css') }}" rel="stylesheet" type="text/css" media="screen" />

@endsection

@section('content')
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('settings.index') }}">setting</a></li>
  <li class="breadcrumb-item">billing</li>
  <li class="pull-right"><a href="javascript:void(0);" class="btn guide-me"><i class="aapl-lifebuoy"></i> Guide me</a></li>
</ol>
<!-- END BREADCRUMB -->

  <div id="app" class="card card-transparent">
    <div class="card-header">
      <h1 class="section-title">Billing</h1>
    </div>
    <!-- start account overview -->
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Account overview
          </h6>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div class="card card-default">
                        <div class="card-header">
                          <div class="row mb-2">
                            <div class="col-md-9">
                              <p class="card-title mb-2">Merchant since:</p>
                              <span class="card-title ml-2 mb-2">{{ $store->created_at->toFormattedDateString() }}</span>
                            </div>
                            <div class="col-md-3">
                              @if($store->plan->slug === 'trial')
                              <a href="{{ route('plan.change.index') }}" class="btn btn-xs btn-danger btn-block">Choose plan</a>
                              @else
                              <a href="{{ route('plan.change.index') }}" class="btn btn-xs btn-danger btn-block">Change plan</a>
                              @endif
                            </div>
                          </div>
                        </div>
                        <div class="card-block">
                          <div class="row">
                              <div class="col-md-4 mb-2">
                                <p class="font-weight-bold">Current plan</p>
                                <span>{{ $store->plan->name }}</span>
                              </div>
                              <div class="col-md-4 mb-2">
                                <p class="font-weight-bold">Subscription ends on</p>
                                <span>{{ $store->expiry_date->format("l". ", " . "d M Y" ) }}</span>
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
    <!--- end account overview -->
    <!-- start account services -->
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Services
          </h6>
          <div class="p-r-30">
            <p>
             Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores, fugit?
            </p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                       <service-card :data="{{ json_encode($services) }}"></service-card> 
                    </div>
                  </div>    
              </div>
          </div>
        </div>
    </div>
    <!--- end account services -->
    <!-- start invoices -->
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Bills
          </h6>
          <div class="p-r-30">
            <p>
             Your paid and pending bills will be shown here.
            </p>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div class="card card-default">
                        <div class="card-header">
                          <div class="card-title">bills</div>
                        </div>
                        <div class="card-block">
                          <invoice-table></invoice-table>    
                        </div>
                      </div>
                    </div>
                  </div>    
              </div>
          </div>
        </div>
    </div>
    <!--- end invoices -->
  </div>
@endsection
@section('scripts') 


@endsection

@section('page_scripts')
<script>
 
  
</script>
@endsection
