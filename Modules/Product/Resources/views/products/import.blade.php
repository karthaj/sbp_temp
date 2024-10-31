@extends('layouts.zpanel')

@section('styles')

@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item">product</li>
  <li class="breadcrumb-item"><a href="{{ route('product.import') }}">import</a></li>
</ol>
<!-- END BREADCRUMB --> 
<div  class="card card-transparent mb-0">
  <div class="card-header ">
      <div>
          <h1 class="section-title">Product Import</h1>
      </div>
  </div>
  <div class="m-0 row card-block pb-0">
      <div class="col-lg-4 no-padding">
        <div class="p-r-30">
          <p>
           description goes here.
          </p>
        </div>
      </div>
      <div class="col-lg-8 sm-no-padding mb-0">
        <div class="row">
            <div class="col-lg-12">
               <div class="card card-transparent mb-0">
                  <div class="card-block no-padding">
                    <div id="card-advance" class="card card-default mb-0">
                      <div class="card-block">
                        @if($errors->count())
                        <div class="alert bg-danger-lighter">
                          @foreach ($errors->all() as $error)
                            <p>{{ $error }}<p>
                          @endforeach
                        </div>
                        @endif
                        @if(session('messages')) 
                        <div class="alert bg-danger-lighter">
                          @foreach (session('messages') as $key => $product) 
                            <p>
                              <strong>{{ $key }}</strong>
                            </p>
                            @foreach($product as $message)
                            <p>{{ $message }}</p>
                            @endforeach
                          @endforeach
                        </div>
                        @endif 

                        @if(session('results')) 
                        <div class="alert alert-success">
                          @foreach (session('results') as $result) 
                            <p>{{ $result }}</p>
                          @endforeach
                        </div>
                        @endif 

                        <form id="productImportForm" action="{{ route('product.import.store') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                          <div class="form-group">
                              <label for="importfile">Select a CSV file to import</label>
                              <input type="file" id="importfile" class="form-control{{ $errors->has('importfile') ? ' error' : '' }}" name="importfile"  accept=".csv">
                              @if($errors->has('importfile'))
                              <label id="name-error" class="error" for="name">{{ $errors->first('importfile') }}</label>
                              @endif
                              <small><em>The maximum file size not be greater than 5000 kilobytes.</em></small>
                          </div>
                          <div class="form-group mt-4">
                            <div class="checkbox check-info">
                              <input type="checkbox" value="1" id="overwrite" name="overwrite">
                              <label for="overwrite">Overwrite existing products</label>
                              <span data-placement="right" data-toggle="tooltip" data-html="true" data-original-title="<p>If checked, any existing items with the same <strong>Product ID</strong> will be updated with the information from the CSV file.</p>">
                                <i class="fa fa-question-circle"></i>
                              </span>
                            </div>
                          </div>
                          <div class="form-group mt-4">
                            <div class="checkbox check-info">
                              <input type="checkbox" value="1" id="overwrite_image" name="overwrite_image">
                              <label for="overwrite_image">Overwrite existing product images</label>
                            </div>
                          </div>
                           @include ('zpanel.partials._form_actions', ['path' => route('shipping.index')])
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
<!-- end shipping -->


@endsection

@section('scripts') 

@endsection

@section('page_scripts')

@endsection
