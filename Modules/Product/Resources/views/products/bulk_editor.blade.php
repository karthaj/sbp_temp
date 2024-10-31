@extends('layouts.zpanel')

@section('styles')

<link href="{{ asset('assets/plugins/dropzone/css/dropzone.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">product</li>
  <li class="breadcrumb-item"><a href="{{ route('product.bulk.editor') }}">import / export</a></li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent mb-0">
  <div class="card-header ">
      <div>
          <h1 class="section-title">Bulk Product Import/Export</h1>
      </div>
  </div>
  @if(auth()->user()->can('import products') || auth()->user()->can('export products'))
  <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
        <div class="p-r-30">
          <p>Upload your product details in .CSV according to the table format in the example. Any other formats and or deviations from the table format would result in failed uploads.</p>
        </div>
      </div>
      <div class="col-lg-8 sm-no-padding mb-0">
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent mb-0">
                  <div class="card-block no-padding">
                    <div class="card card-default mb-0">
                      <div class="card-block">
                        @if(auth()->user()->can('import products'))
                          <a href="{{ route('product.import') }}" class="inline mr-4"><p><i class="aapl-hdd-up"></i> <span class="pl-2">Import</span></p>
                        @endif
                        @if(auth()->user()->can('export products'))
                          <a href="{{ route('product.export') }}" class="inline mr-4"><p><i class="aapl-hdd-down"></i> Export</p></a>
                        @endif       
                      </div>
                    </div>
                  </div>
                </div>    
            </div>
        </div>
      </div>
  </div>
  @endif
  <div class="m-0 row card-block pb-0">
	  <div class="col-lg-4 no-padding">
		  
	  	<div class="p-r-30">
          <p>Drag and drop or select your product images here to upload them. Several image files can be uploaded together.</p>
        </div>
	  </div>
	<div class="col-lg-8">
	   <div class="card card-transparent mb-0">
		  <div class="card-block no-padding">
			<div class="card card-default mb-0">
			  <div class="card-block"> 
				<div id="success"></div>
				<div id="error"></div>
				<form id="bulkEditorDropzone" action="{{ route('product.bulk.image.upload') }}" class="dropzone">
          {{ csrf_field() }}
				  <div class="fallback">
					<input name="file" type="file" multiple/>
				  </div>
				</form>
			  </div>
			</div>
		  </div>
		</div>    
	</div>
  </div>
</div>
<!-- end shipping -->


@endsection

@section('scripts')


<script type="text/javascript" src="{{ asset('assets/plugins/dropzone/dropzone.min.js') }}"></script>

@endsection
