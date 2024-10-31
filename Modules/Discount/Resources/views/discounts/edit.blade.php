@extends('layouts.zpanel')

@section('styles')
<link href="{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
@endsection

@section('content')
<div id="app"></div>
<!-- BEGIN PlACE PAGE CONTENT HERE -->
<!-- START BREADCRUMB -->
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ route('discounts.index') }}">discounts</a></li>
  <li class="breadcrumb-item">edit</li>
</ol>
<!-- END BREADCRUMB --> 
<form action="{{ route('discounts.update', $discount) }}" method="post" autocomplete="off" class="sodirty">
{{ csrf_field() }}
{{ method_field('PATCH') }}
  <div  class="card card-transparent">
    <div class="card-header ">
        <div>
            <h1 class="section-title">{{ $discount->name }}</h1>
        </div>
    </div>

    <div class="m-0 row card-block">
        <div class="col-lg-4 no-padding">
          <div class="p-r-30">
            <h6 class="ui-subheader">
             Discount Details
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
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group{{ $errors->has('discount_code') ? ' has-danger' : '' }}">
                                  <label for="discount_code">Discount Code <small>Alphanumeric only</small></label>
                                  <input type="text" id="discount_code" class="form-control{{ $errors->has('discount_code') ? ' form-control-danger' : '' }}" name="discount_code" value="{{ old('discount_code', $discount->code) }}" placeholder="e.g. SPRINGSALE" required>
                                  
                                  @if($errors->has('discount_code'))
                                  <div class="form-control-feedback">{{ $errors->first('discount_code') }}</div>
                                  @endif
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group{{ $errors->has('discount_name') ? ' has-danger' : '' }}">
                                  <label for="discount_name">Discount Name</label>
                                  <input type="text" id="discount_name" class="form-control{{ $errors->has('discount_name') ? ' form-control-danger' : '' }}" name="discount_name" value="{{ old('discount_name', $discount->name) }}" required>
                    
                                  @if($errors->has('discount_name'))
                                  <div class="form-control-feedback">{{ $errors->first('discount_name') }}</div>
                                  @endif
                              </div>
                            </div>
                          </div>
                            
                            
                          <div class="row">
                            <div class="form-group col-md-6{{ $errors->has('discount_type') ? ' has-danger' : '' }}">
                              <label for="discount_type">Discount Type</label>
                              <select name="discount_type" id="discount_type" class="form-control{{ $errors->has('discount_type') ? ' form-control-danger' : '' }}" data-init-plugin="select2" data-currency="{{ session('store')->setting->currency->iso_code }}">
                                <option value="percentage" {{ $discount->reduction_percent > 0 ? 'selected' : '' }}>Percentage discount</option>
                                <option value="fixed" {{ $discount->reduction_amount > 0 ? 'selected' : '' }}>Fixed amount</option>
                              </select>
                              @if($errors->has('discount_type'))
                              <div class="form-control-feedback">{{ $errors->first('discount_type') }}</div>
                              @endif
                            </div>
                            <div id="discountBlock" class="form-group col-md-6{{ $errors->has('discount_value') ? ' has-danger' : '' }}">
                              <label for="discount">Discount Value</label>
                               <div id="" class="input-group transparent">
                                @if($discount->reduction_percent > 0)
                                  <span class="input-group-addon">
                                      <i class="fa fa-percent"></i>
                                  </span>
                                  <input type="text" name="discount_value" id="discount_value" class="form-control autonumeric{{ $errors->has('discount_value') ? ' form-control-danger' : '' }}" data-v-min="0" data-v-max="100" value="{{ $discount->reduction_percent }}">
                                @elseif($discount->reduction_amount > 0)
                                <span class="input-group-addon">
                                    Rs
                                </span>
                                <input type="text" name="discount_value" id="discount_value" class="form-control autonumeric{{ $errors->has('discount_value') ? ' form-control-danger' : '' }}" data-v-min="0" data-v-max="999999999" value="{{ $discount->reduction_amount }}">
                                @endif
                      
                               </div>

                              @if($errors->has('discount_value'))
                                <div class="form-control-feedback">{{ $errors->first('discount_value') }}</div>
                              @endif
                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group col-md-4{{ $errors->has('minimum_amount') ? ' has-danger' : '' }}">
                              <label for="minimum_amount">Minimum Purchase</label>
                              <div class="input-group transparent">
                                <span class="input-group-addon">
                                  {{ session('store')->setting->currency->iso_code }}
                                </span>
                                <input type="text" name="minimum_amount" id="minimum_amount" class="form-control autonumeric{{ $errors->has('minimum_amount') ? ' form-control-danger' : '' }}" value="{{ $discount->minimum_amount }}">
                              </div>
                              @if($errors->has('minimum_amount'))
                              <div class="form-control-feedback">{{ $errors->first('minimum_amount') }}</div>
                              @endif
                            </div>
                          </div>
                            
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group{{ $errors->has('quantity') ? ' has-danger' : '' }}">
                                <label for="quantity">Limit total number of uses</label>
                                <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="Use '0' for unlimited usage">
                                    <i class="fa fa-question-circle"></i>
                                  </span>
                                <input type="number" name="quantity" id="quantity" class="form-control {{ $errors->has('quantity') ? ' form-control-danger' : '' }}" required min="0" value="{{ $discount->quantity }}">
                                @if($errors->has('quantity'))
                                <div class="form-control-feedback">{{ $errors->first('quantity') }}</div>
                                @endif
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group{{ $errors->has('quantity_per_customer') ? ' has-danger' : '' }}">
                                <label for="quantity_per_customer">Limit number of uses per customer</label>
                                <input type="number" name="quantity_per_customer" id="quantity_per_customer" class="form-control{{ $errors->has('quantity_per_customer') ? ' form-control-danger' : '' }}" min="0" value="{{ $discount->quantity_per_user }}">
                                @if($errors->has('quantity_per_customer'))
                                <div class="form-control-feedback">{{ $errors->first('quantity_per_customer') }}</div>
                                @endif
                              </div>
                            </div>
                          </div>
                            
                            
                          <div class="form-group{{ $errors->has('expiry_date') ? ' has-danger' : '' }}">
                              <label for="expiry_date">Expiry Date</label>
                              <div id="expiry_date" class="input-group date">
                                  <input type="text" class="form-control{{ $errors->has('expiry_date') ? ' form-control-danger' : '' }}" placeholder="Expiry Date" name="expiry_date" value="{{ $discount->expires_at ? $discount->expires_at->toDateString() : '' }}">
                                  <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                  </span>
                              </div>
                              @if($errors->has('expiry_date'))
                              <div class="form-control-feedback">{{ $errors->first('expiry_date') }}</div>
                              @endif
                          </div>

                          <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                            <div class="checkbox check-info">
                              <input type="checkbox" {{ $discount->active ? 'checked' : '' }} value="1" id="status" name="status">
                              <label for="status" class="form-check-label">Active</label>
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

    <div class="m-0 row card-block">
        <div class="col-lg-4 no-padding">
          <div class="p-r-30">
            <h6 class="ui-subheader">
             Discount Conditions
            </h6>
          </div>
        </div>
        <div class="col-lg-8 sm-no-padding">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div id="card-advance" class="card card-default">
                        <div class="card-header ">
                          <div class="card-title">
                            Applies to
                          </div>
                        </div>
                        <div class="card-block">
                          <div class="radio radio-info">
                            <input type="radio" value="entire_order" name="discount_condition" id="entire_order" {{ $discount->entire_order ? 'checked' : '' }} class="discount-condition">
                            <label for="entire_order">Entire order</label>
                          </div>
                          <div class="radio radio-info">
                            <input type="radio" value="specific_category" name="discount_condition" id="specific_category" {{ $discount->category_id ? 'checked' : '' }} class="discount-condition">
                            <label for="specific_category">Specific category</label>
                          </div>
                          <div class="radio radio-info">
                            <input type="radio" value="specific_product" name="discount_condition" id="specific_product" class="discount-condition" {{ $discount->product_id ? 'checked' : '' }}>
                            <label for="specific_product">Specific product</label>
                          </div>
                          @if($errors->has('discount_condition'))
                            <div class="error">{{ $errors->first('discount_condition')}}</div>
                          @endif
                          
                          <div id="specificCategory" class="form-group{{ $discount->category_id ? '' : ' hide' }}{{ $errors->has('category') ? ' has-danger' : '' }}">
                            <select id="category" class="full-width form-control{{ $errors->has('category') ? ' form-control-danger' : '' }}" name="category"  data-init-plugin="select2" data-placeholder="Search category">
                            <option value=""></option>
                            @if($categories->count())
                              @foreach($categories as $category)
                              <option value="{{ $category->id }}" {{ $category->id == $discount->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                              @endforeach
                            @endif
                            </select>

                            @if($errors->has('category'))
                                <div class="form-control-feedback">{{ $errors->first('category') }}</div>
                            @endif
                          </div>

                          <div id="specificProduct" class="form-group{{ $discount->product_id ? '' : ' hide' }}{{ $errors->has('product') ? ' has-danger' : '' }}">
                            <select id="product" class="full-width form-control{{ $errors->has('category') ? ' form-control-danger' : '' }}" name="product"  data-init-plugin="select2" data-placeholder="Search product">
                            <option value=""></option>
                            @if($products->count())
                              @foreach($products as $product)
                              <option value="{{ $product->id }}" {{ $category->id == $discount->product_id ? 'selected' : '' }}>{{ $product->name }}</option>
                              @endforeach
                            @endif
                            </select>

                            @if($errors->has('product'))
                                <div class="form-control-feedback">{{ $errors->first('product') }}</div>
                            @endif
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>    
              </div>
          </div>

          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div id="card-advance" class="card card-default">
                        <div class="card-header ">
                          <div class="card-title">
                            Customer eligibility
                          </div>
                        </div>
                        <div class="card-block">
                          <div class="radio radio-info">
                            <input type="radio" value="everyone" name="customer_restriction" id="everyone"  class="customer-restriction" {{ $discount->group_id == null && $discount->customer_id == null ? 'checked' : '' }}>
                            <label for="everyone">Everyone</label>
                          </div>
                          <div class="radio radio-info">
                            <input type="radio" value="specific_group" name="customer_restriction" id="specific_group" class="customer-restriction" {{ $discount->group_id ? 'checked' : '' }}>
                            <label for="specific_group">Specific group of customers</label>
                          </div>
                          <div class="radio radio-info">
                            <input type="radio" value="specific_customer" name="customer_restriction" id="specific_customer" class="customer-restriction" {{ $discount->customer_id ? 'checked' : '' }}>
                            <label for="specific_customer">Specific customer</label>
                          </div>
                          @if($errors->has('customer_restriction'))
                            <div class="error">{{ $errors->first('customer_restriction')}}</div>
                          @endif
                          <div id="customerGroup" class="form-group{{ $discount->group_id  ? '' : ' hide' }}{{ $errors->has('customer') ? ' has-danger' : '' }}">
                            <select id="group" class="full-width form-control{{ $errors->has('group') ? ' form-control-danger' : '' }}" name="group" data-init-plugin="select2">
                            <option value="">Search group of customers</option>
                            @if($groups->count())
                              @foreach($groups as $group)
                              <option value="{{ $group->id }}" {{ $discount->group_id == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                              @endforeach
                            @endif
                            </select>

                            @if($errors->has('group'))
                                <div class="form-control-feedback">{{ $errors->first('group') }}</div>
                            @endif
                          </div>
                          <div id="customerWrapper" class="form-group{{ $discount->customer_id  ? '' : ' hide' }}{{ $errors->has('customer') ? ' has-danger' : '' }}">
                            <select id="customer" class="full-width form-control{{ $errors->has('customer') ? ' form-control-danger' : '' }}" name="customer" data-init-plugin="select2">
                            <option value="">Search customer</option>
                            @if($customers->count())
                              @foreach($customers as $customer)
                              <option value="{{ $customer->id }}" {{ $discount->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->firstname.' '.$customer->lastname.' '.$customer->email }}</option>
                              @endforeach
                            @endif
                            </select>

                            @if($errors->has('customer'))
                                <div class="form-control-feedback">{{ $errors->first('customer') }}</div>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>    
              </div>
          </div>
          
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div id="card-advance" class="card card-default">
                        <div class="card-header ">
                          <div class="card-title">
                            Location Restriction
                          </div>
                        </div>
                        <div class="card-block">
                          <div class="form-group{{ $errors->has('countries') ? ' has-danger' : '' }}">
                            <label for="countries">Limit by location</label>
                            <select id="countries" class="full-width form-control{{ $errors->has('countries') ? ' form-control-danger' : '' }}" name="countries[]" multiple data-init-plugin="select2" data-placeholder="Search country">

                            @if($countries->count())
                              @foreach($countries as $country)
                              <option value="{{ $country->id }}" {{ $discount->countries ? $discount->countries->contains($country->id) ? 'selected' : '' : '' }}>{{ $country->name }}</option>
                              @endforeach
                            @endif
                            </select>

                            @if($errors->has('countries'))
                                <div class="form-control-feedback">{{ $errors->first('countries') }}</div>
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
  </div>
@include ('zpanel.partials._form_actions', ['path' => route('discounts.index')])
</form>

@endsection

@section('scripts') 
<script type="text/javascript" src="{{ asset('assets/plugins/jquery-autonumeric/autoNumeric.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/discount.js') }}"></script>
@endsection

@section('page_scripts')
<script>
  $(document).ready(function() {
    
    discount.init();
    discount.toggleDiscount();
    discount.toggleCustomerRestriction();
    discount.toggleDiscountCondition();

  });
</script>
@endsection
