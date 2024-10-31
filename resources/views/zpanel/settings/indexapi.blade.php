@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">settings</li>
  <li class="breadcrumb-item active">api settings</li>
</ol>
<!-- END BREADCRUMB --> 
  <div  class="card card-transparent">
    <div class="card-header">
      <h1 class="section-title">API Account</h1>
    </div>
    <div class="m-0 row card-block pb-0">
        <div class="col-lg-4 no-padding">
          <h6 class="ui-subheader">
           Shopbox Pay Credentials
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
                        <div class="card-header">
                          <div class="card-title">
                            Shopbox API Credentials
                          </div>
                        </div>
                        <div class="card-block">
                          <div class="row">
                            <div class="col-md-4">
                              <p>Merchant ID</p>
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
                                  <button id="btnRefresh" class="btn btn-info btn-xs ml-2"><i class="fa fa-refresh"></i> Refresh</button>
                                @endif
                              </p>
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
</form>
@endsection
@section('scripts') 


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
