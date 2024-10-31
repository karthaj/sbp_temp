<p class="text-uppercase"><strong>Other Details</strong></p>
<div class="row column-seperation">
  <div class="col-md-6 form-group{{ $errors->has('online') ? ' has-error' : '' }}">
    <div class="checkbox check-info">
        <input type="checkbox" value="1" id="online" name="online" @if(old('online', $product->online)) checked @endif>
        <label for="online">This product can be purchased from online store</label>
    </div>
    @if($errors->has('online'))
      <label id="online-error" class="error" for="online">{{ $errors->first('online') }}</label>
    @endif
  </div>
</div>
<div class="row column-seperation align-items-end">
  <div class="col-sm-4">
    <div class="form-group{{ $errors->has('condition') ? ' has-error' : '' }}">
      <label for="condition">Product Condition</label>
      <select name="condition" id="condition" class="full-width" data-init-plugin="select2">
        <option value="new" @if(old('condition', $product->condition) == 'new') selected @endif>New</option>
        <option value="used"  @if(old('condition', $product->condition) == 'used') selected @endif>Used</option>
        <option value="refurbished"  @if(old('condition', $product->condition) == 'refurbished') selected @endif>Refurbished</option>
      </select>
      @if($errors->has('condition'))
        <label id="condition-error" class="error" for="condition">{{ $errors->first('condition') }}</label>
      @endif
    </div>
  </div>
  <div class="col-sm-6">
    <div class="form-group{{ $errors->has('show_condition') ? ' has-error' : '' }}">
      <div class="checkbox check-info">
          <input type="checkbox" value="1" id="show_condition" name="show_condition" @if(old('show_condition', $product->show_condition)) checked @endif>
          <label for="show_condition" class="text-lowercase">Show the condition in the product page</label>
      </div>
      @if($errors->has('show_condition'))
        <label id="show_condition-error" class="error" for="show_condition">{{ $errors->first('show_condition') }}</label>
      @endif
    </div>
  </div>
</div>
@if(0)
<div class="row column-seperation">
  <div class="col-sm-4">
    <div class="form-group{{ $errors->has('min_qty') ? ' has-error' : '' }}">
      <label for="min_qty">Minimum quantity for sale</label>
      <input type="text" id="min_qty" name="min_qty" class="form-control autonumeric" data-v-min="1" data-v-max="9999" value="{{ old('min_qty', $product->minimal_quantity) }}">
      @if($errors->has('min_qty'))
        <label id="min_qty-error" class="error" for="min_qty">{{ $errors->first('min_qty') }}</label>
      @endif
    </div>
  </div>
</div>
@endif
<div class="row column-seperation">
  <div class="col-sm-4 form-group{{ $errors->has('low_qty') ? ' has-error' : '' }}">
    <label for="low_qty">Low stock level</label>
    <input type="text" id="low_qty" name="low_qty" class="form-control autonumeric" data-v-min="0" data-v-max="9999" value="{{ old('low_qty', $product->stock ? $product->stock->out_of_stock : 0) }}"> 
    @if($errors->has('low_qty'))
      <label id="low_qty-error" class="error" for="low_qty">{{ $errors->first('low_qty') }}</label>
    @endif 
  </div>
</div>
<div class="row column-seperation">
    <div class="col-sm-4 form-group{{ $errors->has('instock') ? ' has-error' : '' }}">
        <label for="instock">Label when in stock</label>
        <input type="text" id="instock" name="instock" value="{{ $product->available_now }}" class="form-control">
        @if($errors->has('instock'))
          <label id="instock-error" class="error" for="instock">{{ $errors->first('instock') }}</label>
        @endif 
    </div>
    <div class="col-sm-4 form-group{{ $errors->has('outofstock') ? ' has-error' : '' }}">
        <label for="outofstock">Label when out of stock</label>
        <input type="text" id="outofstock" name="outofstock" value="{{ $product->available_later }}" class="form-control">
        @if($errors->has('outofstock'))
          <label id="outofstock-error" class="error" for="outofstock">{{ $errors->first('outofstock') }}</label>
        @endif
    </div>
</div>
@if($store->plan->slug !== 'member' && $store->plan->slug !== 'trial')
<p class="text-uppercase"><strong>Selling condition</strong></p>  
  <div id="productAvailability" class="row column-seperation">
    <div class="col-md-12 form-group {{ $errors->has('product_availability') ? ' has-error' : '' }}">
      <div class="radio radio-info">
        <input type="radio" @if(!$product->pre_order && !$product->available_for_order) checked @endif value="none" name="product_availability" id="none">
        <label for="none">None</label>
        <input type="radio"  @if($product->pre_order) checked @endif value="preorder" name="product_availability" id="preorder">
        <label for="preorder">I want to take pre-orders</label>
        <input type="radio" @if(old('product_availability') === 'backorder' || $product->available_for_order) checked @endif value="backorder" name="product_availability" id="backorder">
        <label for="backorder">Allow customers to purchase this product when it's out of stock</label>
      </div>
    </div>
    @if($errors->has('product_availability'))
      <label id="product_availability-error" class="error" for="product_availability">{{ $errors->first('product_availability') }}</label>
    @endif
  </div>
  @endif
  <div class="row column-seperation{{ !$product->pre_order ? ' hide' : '' }}">
    <div class="col-sm-4">
      <div class="form-group mb-0">
        <label for="available_date">available date</label>
      </div>
      <div id="available_date" class="form-group date{{ $errors->has('available_date') ? ' has-error' : '' }}">
        <div class="input-group">
          <input type="text" class="form-control" id="available_date" name="available_date" value="{{ old('available_date', $product->available_date ? $product->available_date->toDateString() : '') }}">
          <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
          </div>
        </div>
        @if($errors->has('available_date'))
          <label id="available_date-error" class="error" for="available_date">{{ $errors->first('available_date') }}</label>
        @endif
      </div>
    </div>
  </div>
