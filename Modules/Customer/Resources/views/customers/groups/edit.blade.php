@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/jquery-dynatree/skin/ui.dynatree.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">customers</a></li>
  <li class="breadcrumb-item"><a href="{{ route('customers.groups.index') }}">groups</a></li>
</ol>
<!-- END BREADCRUMB --> 
<form id="groupForm" action="{{ route('customers.groups.update', $group->id) }}" method="post" class="sodirty">
{{ csrf_field() }}
{{ method_field('PATCH') }}
  <div  class="card card-transparent">
    <div class="card-header ">
        <div>
            <h1 class="section-title">Edit {{ $group->name }}</h1>
        </div>
    </div>

    <div class="m-0 row card-block">
        <div class="col-lg-4 no-padding">
          <div class="p-r-30">
            <h6 class="ui-subheader">
             Group Details
            </h6>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div id="card-advance" class="card card-default">
                        <div class="card-block">
                          
                            <div class="form-group{{ $errors->has('group_name') ? ' has-danger' : '' }}">
                                <label for="group_name">Group Name</label>
                                <input type="text" id="group_name" class="form-control{{ $errors->has('group_name') ? ' form-control-danger' : '' }}" name="group_name" value="{{ old('group_name', $group->name) }}" required>

                                @if($errors->has('group_name'))
                                <div class="form-control-feedback">{{ $errors->first('group_name') }}</div>
                                @endif
                            </div>
                            
                            <div class="form-group{{ $errors->has('group_discount') ? ' has-danger' : '' }}">
                                <label for="group_discount">Storewide Discount</label>
                                <span class="ml-2" data-placement="right" data-toggle="tooltip" data-html=
                                  "true" data-original-title="<span>If a product doesn't have any category level discount setup then this rate will be used as the discount rate instead.</span>">
                                    <i class="fa fa-question-circle"></i>
                                  </span>
                                <div class="input-group transparent">
                                  <span class="input-group-addon">
                                    <i class="fa fa-percent"></i>
                                  </span>
                                  <input type="text" id="group_discount" class="form-control{{ $errors->has('group_discount') ? ' form-control-danger' : '' }}" name="group_discount" value="{{ old('group_discount', round($group->discount,2)) }}">
                                </div>

                                @if($errors->has('discount'))
                                <div class="form-control-feedback">{{ $errors->first('discount') }}</div>
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

    <div class="m-0 row card-block">
        <div class="col-lg-4 no-padding">
          <div class="p-r-30">
            <h6 class="ui-subheader">
             Category Discount
            </h6>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div id="card-advance" class="card card-default">
                        <div id="categoryLevelDiscount" class="card-block">
                          <div class="form-group">
                             <button type="button" class="btn btn-action-add" data-toggle="modal" data-target="#categoryDiscount">Add category</button>
                          </div>
                          @if($group->discounts->count())
                            @foreach($group->discounts as $discount)
                              <div class="row item align-items-center mb-2">
                                  <div class="col"> 
                                    {{ $discount->category->name }}
                                    <input type="hidden" name="category_discount[{{ $discount->category->id }}][category]" value="{{ $discount->category->id }}">
                                  </div>
                                  <div class="col">Discount: {{ round($discount->discount,2) }} %
                                    <input type="hidden" name="category_discount[{{ $discount->category->id }}][reduction]" value="{{ $discount->discount }}">
                                  </div>
                                  <div class="col">
                                    <button class="btn btn-default btn-default-custom btn-remove"><i class="pg-trash"></i></button>
                                  </div>
                              </div>
                            @endforeach
                          @else
                            <p class="notification">No category level discounts have been created for this group.</p>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>    
              </div>
          </div>
        </div>
    </div>
    <div class="m-0 row card-block">
        <div class="col-lg-4 no-padding">
          <div class="p-r-30">
            <h6 class="ui-subheader">
             Customers in Group
            </h6>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div id="card-advance" class="card card-default">
                        <div class="card-block">
                          @if(session('store')->customers->count())
                            <div class="form-group">
                              <label for="customers">select customer</label>
                              <select id="customers" class="form-control" name="customers[]" multiple data-init-plugin="select2">
                                @foreach(session('store')->customers as $customer)
                                  <option value="{{ $customer->id }}" 
                                  @if($group->customers->contains('id', $customer->id)) selected @endif>{{ $customer->firstname }} {{ $customer->lastname }} ({{ $customer->email }})</option>
                                @endforeach
                              </select>
                            </div>
                          @else
                          <p>No customers have registered or been added yet. Once a customer account has been created, they will be viewable here for selection.</p>
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
@include ('zpanel.partials._form_actions', ['path' => route('customers.index')])
</form>

<div class="modal fade slide-up" id="categoryDiscount" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog ">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5>Select <span class="semi-bold">Category</span></h5>
        </div>
        <form id="categoryDiscountForm" action="javascript:;" method="post">
          <div class="modal-body">
                <div class="form-group">
                    <div id="categoryTree">
                    @if($categories->count())
                       
                        @include ('customer::partials._categories', ['categories' => $categories->where('parent_id', null)])
                        
                    @endif
                    </div>
                    <input type="hidden" id="category" name="category">
                    <input type="hidden" id="title" name="title">
                </div>
                <div class="form-group">
                  <label for="discount">Discount</label>
                  <div class="input-group transparent">
                      <span class="input-group-addon">
                        <i class="fa fa-percent"></i>
                      </span>
                      <input type="text" class="autonumeric form-control" name="discount" id="discount" data-a-dec="." data-a-sep="," value="0">
                  </div>  
                </div>
          </div>
          <div class="modal-footer">
            <div class="form-group">
                <input type="submit" value="Save" class="btn btn-action-save pull-right">
              </div>
          </div>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>

@endsection

@section('scripts') 
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-dynatree/jquery.dynatree.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-autonumeric/autoNumeric.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/customer.js') }}"></script>
@endsection

@section('page_scripts')
<script>
  $(document).ready(function() {
    
    customer.initCatergoryTree();
    customer.initAutonumeric();
    customer.validateCategoryDiscountForm();
    customer.removeCategoryDiscount();

  });
</script>
@endsection
