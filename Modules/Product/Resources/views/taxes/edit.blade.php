@extends('layouts.zpanel')

@section('content')

<!-- START BREADCRUMB -->
 <ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">{{ config('app.name') }}</a></li>
  <li class="breadcrumb-item">Store</li>
  <li class="breadcrumb-item">Tax</li>
  <li class="breadcrumb-item">General</li>
</ol>
<div id="app"></div>
<!-- END BREADCRUMB --> 
<form id="taxOptionForm" action="{{ route('tax.update',$tax_option) }}" autocomplete="off" method="post">
{{ csrf_field() }}
{{ method_field('PATCH') }}
<div class="card card-transparent mb-0">
  <div class="card-header  ">
    <div class="card-title">
        <h2 class="section-title">Tax Options</h2>
    </div>
  </div>
  <div class="m-0 row card-block pb-0">
    <div class="col-lg-4 no-padding">
      <div class="p-r-30">
        <h6 class="ui-subheader">Configure Tax Options</h6>    
      </div>
    </div>
    <div class="col-lg-8 sm-no-padding">
      <div class="card card-transparent mb-0">
        <div class="card-block no-padding">
          <div id="card-advance" class="card card-default mb-0">
            <div class="card-block">
              <div class="row">
                <div class="col-sm-12 form-group">
                    <label for="tax_label">Tax Label</label>
                    <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="Enter a general name that describes the type of tax applied to orders on your store. This will be shown to your customers when tax is displayed in your store during the checkout process or on manual orders when taxes are shown as one summarized line item. Some suggested values include: Tax, Sales Tax, or GST.">
                      <i class="fa fa-question-circle"></i>
                    </span>
                    <input type="text" id="tax_label" class="form-control{{ $errors->has('tax_label') ? ' error' : '' }}" required name="tax_label" value="{{ $tax_option->tax_label ? $tax_option->tax_label : old('tax_label') }}">
                    @if($errors->has('tax_label'))
                    <label id="taxlabel-error" class="error" for="tax_label">{{ $errors->first('tax_label') }}</label>
                    @endif
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 form-group">
                  <label>Price entered with tax</label>
                  <span class="ml-2" data-placement="right" data-toggle="tooltip" data-html="true" data-original-title="This option is important as it will affect how you input prices. Changing it will not update existing products.">
                      <i class="fa fa-question-circle"></i>
                  </span>
                  <div class="radio radio-success">
                    <input type="radio" value="1" name="price_tax" id="yes" {{ $tax_option->price_includes_tax ? 'checked' : '' }}
                    class="{{ $errors->has('price_tax') ? 'error' : '' }}">
                    <label for="yes" class="text-normal">Yes, I will enter prices inclusive of tax</label>
                    <input type="radio" value="0" name="price_tax" id="no" {{ $tax_option->price_includes_tax == 0 ? 'checked' : '' }}
                    class="{{ $errors->has('price_tax') ? 'error' : '' }}">
                    <label for="no" class="text-normal">No, I will enter prices exclusive of tax</label>
                  </div>
                  @if($errors->has('price_tax'))
                    <label id="price_tax-error" class="error" for="price_tax">{{ $errors->first('price_tax') }}</label>
                  @endif
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4 form-group">
                  <label for="calculate_tax">Calculate tax based on</label>
                  <span class="ml-2" data-placement="right" data-toggle="tooltip" data-html="true" data-original-title="<strong>Calculate Tax Based On</strong> <p>This setting controls which address should be used to determine the tax zone that a customer falls under.<br /><br />Choose <strong>Billing Address</strong> to determine a customer's tax zone based on their order's billing address, or <strong>Shipping Address</strong> to base the applicable tax zone on a customer's shipping address.<br /><br />If tax zones should be determined by your store's address, choose <strong>Store Address</strong>.</p>">
                      <i class="fa fa-question-circle"></i>
                  </span>
                  <select name="calculate_tax" id="calculate_tax" class="form-control{{ $errors->has('calculate_tax') ? ' error' : '' }}">
                    <option value="shipping" {{ $tax_option->tax_based_on == 'shipping' ? 'selected' : '' }}>Shipping Address</option>
                    <option value="billing" {{ $tax_option->tax_based_on == 'billing' ? 'selected' : '' }}>Billing Address</option>
                    <option value="store" {{ $tax_option->tax_based_on == 'store' ? 'selected' : '' }}>Store Address</option>
                  </select>
                  @if($errors->has('calculate_tax'))
                    <label id="calculate_tax-error" class="error" for="calculate_tax">{{ $errors->first('calculate_tax') }}</label>
                  @endif
                </div>
                <div class="col-sm-4 form-group">
                  <label for="shipping_tax">Shipping tax class</label>
                  <span class="ml-2" data-placement="right" data-toggle="tooltip" data-html="true" data-original-title="<strong>Shipping Tax Class</strong> <p>Choose the tax class (configured using the <em>Tax Classes</em> tab above) that taxes for shipping costs should be calculated using.<br /><br />Charging shipping costs using a different tax class will allow you to tax shipping at a higher rate, or not at all.</p>">
                      <i class="fa fa-question-circle"></i>
                  </span>
                  <select name="shipping_tax" id="shipping_tax" class="form-control{{ $errors->has('shipping_tax') ? ' error' : '' }}">
                  @if($tax_classes->count())
                    @foreach($tax_classes as $class)
                    <option value="{{ $class->id }}" {{ $tax_option->shipping->id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                  @endif
                  </select>
                  @if($errors->has('shipping_tax'))
                    <label id="shipping_tax-error" class="error" for="shipping_tax">{{ $errors->first('shipping_tax') }}</label>
                  @endif
                </div>
                <div class="col-sm-4 form-group">
                  <label for="charge_tax">Charge tax</label>
                  <span class="ml-2" data-placement="top" data-toggle="tooltip" data-html="true" data-original-title="<strong>Charge Tax </strong> <p>Check this box if you want to charge tax on discounted price, or leave it unchecked to charge tax on undiscounted price.</p>">
                      <i class="fa fa-question-circle"></i>
                  </span>
                  <div class="checkbox check-success checkbox-circle">
                    <input type="checkbox" value="1" id="charge_tax" name="charge_tax" {{ $tax_option ? 'checked' : '' }}
                    class="{{ $errors->has('charge_tax') ? 'error' : '' }}">
                    <label for="charge_tax" class="text-normal">Appy after discount</label>
                  </div>
                  @if($errors->has('charge_tax'))
                    <label id="charge_tax-error" class="error" for="charge_tax">{{ $errors->first('charge_tax') }}</label>
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
<hr>
<div class="card card-transparent">
  <div class="row card-block pt-0">
    <div class="col-lg-4 no-padding">
      <div class="p-r-30">
        <h6 class="ui-subheader">Configure Tax Display Settings</h6>    
      </div>
    </div>
    <div class="col-lg-8 sm-no-padding">
      <div class="card card-transparent mb-0">
        <div class="card-block no-padding">
          <div id="card-advance" class="card card-default mb-0">
            <div class="card-block">
              <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="product_listing_tax">Display prices on product listings</label><br>
                      <select name="product_listing_tax" id="product_listing_tax" class="form-control{{ $errors->has('product_listing_tax') ? ' error' : '' }}">
                        <option value="1" {{ $tax_option->tax_display_product_listing ? 'selected' : '' }}>Including Tax</option>
                        <option value="0" {{ $tax_option->tax_display_product_listing == 0 ? 'selected' : '' }}>Excluding Tax</option>
                      </select>
                      @if($errors->has('product_listing_tax'))
                        <label id="product_listing_tax-error" class="error" for="product_listing_tax">{{ $errors->first('product_listing_tax') }}</label>
                      @endif
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="product_page_tax">Display prices on product pages</label><br>
                      <select name="product_page_tax" id="product_page_tax" class="form-control{{ $errors->has('product_page_tax') ? ' error' : '' }}">
                        <option value="1" {{ $tax_option->tax_display_product_page ? 'selected' : '' }}>Including Tax</option>
                        <option value="0" {{ $tax_option->tax_display_product_page == 0 ? 'selected' : '' }}>Excluding Tax</option>
                      </select>
                      @if($errors->has('product_page_tax'))
                        <label id="product_page_tax-error" class="error" for="product_page_tax">{{ $errors->first('product_page_tax') }}</label>
                      @endif
                    </div>
                  </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                      <label for="cart_tax">Display prices in shopping carts</label><br>
                      <select name="cart_tax" id="cart_tax" class="form-control{{ $errors->has('cart_tax') ? ' error' : '' }}">
                        <option value="1" {{ $tax_option->tax_display_cart ? 'selected' : '' }}>Including Tax</option>
                        <option value="0" {{ $tax_option->tax_display_cart == 0 ? 'selected' : '' }}>Excluding Tax</option>
                      </select>
                      @if($errors->has('cart_tax'))
                        <label id="cart_tax-error" class="error" for="cart_tax">{{ $errors->first('cart_tax') }}</label>
                      @endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                      <label for="cart_charge">Display tax charges in cart</label><br>
                      <select name="cart_charge" id="cart_charge" class="form-control{{ $errors->has('cart_charge') ? ' error' : '' }}">
                        <option value="1" {{ $tax_option->display_tax_charge_cart ? 'selected' : '' }}>As a single total</option>
                        <option value="2" {{ $tax_option->display_tax_charge_cart == 2 ? 'selected' : '' }}>Broken down by tax rate</option>
                      </select>
                      @if($errors->has('cart_charge'))
                        <label id="cart_charge-error" class="error" for="cart_charge">{{ $errors->first('cart_charge') }}</label>
                      @endif
                    </div> 
                </div>
              </div>
              <div class="row">
                  <div class="col-sm-6">
                      <div class="form-group">
                        <label for="order_invoice_tax">Display prices on orders & invoices</label><br>
                        <select name="order_invoice_tax" id="order_invoice_tax" class="form-control{{ $errors->has('order_invoice_tax') ? ' error' : '' }}">
                          <option value="1" {{ $tax_option->tax_display_order_invoice ? 'selected' : '' }}>Including Tax</option>
                          <option value="0" {{ $tax_option->tax_display_order_invoice == 0 ? 'selected' : '' }}>Excluding Tax</option>
                        </select>
                        @if($errors->has('order_invoice_tax'))
                          <label id="order_invoice_tax-error" class="error" for="order_invoice_tax">{{ $errors->first('order_invoice_tax') }}</label>
                        @endif
                      </div>
                  </div>
                  <div class="col-sm-6">
                      <div class="form-group">
                        <label for="order_tax">Display tax charges on orders</label><br>
                        <select name="order_tax" id="order_tax" class="form-control{{ $errors->has('order_tax') ? ' error' : '' }}">
                          <option value="1" {{ $tax_option->display_tax_charge_order ? 'selected' : '' }}>As a single total</option>
                          <option value="2" {{ $tax_option->display_tax_charge_order == 2 ? 'selected' : '' }}>Broken down by tax rate</option>
                        </select>
                        @if($errors->has('order_tax'))
                          <label id="order_tax-error" class="error" for="order_tax">{{ $errors->first('order_tax') }}</label>
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
@include ('zpanel.partials._form_actions', ['path' => route('tax.edit')])
</form>


@endsection

@section('scripts') 
<script type="text/javascript" src="{{ asset('assets/plugins/classie/classie.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/tax_form.js') }}"></script>
@endsection

@section('page_scripts')

@endsection