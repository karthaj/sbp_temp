<p class="text-uppercase"><strong>pricing</strong></p>
<div id="productPricing" class="row column-seperation align-items-end">
  <div class="col-sm-6">
    <div class="form-group row align-items-center{{ $errors->has('selling_price') ? ' has-error' : '' }}">
        <label for="selling_price" class="control-label col-sm-3 mb-0">Selling Price</label>
        <div class="col-sm-5 pr-0">
          <div class="input-group">
            <div class="input-group-addon">
              {{ $store->setting->currency->iso_code }}
            </div>
            <input type="text" class="form-control autonumeric" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price) }}">
          </div>
        </div>
        <span class="more-pricing">
          <a data-toggle="collapse" href="#morePricing" aria-expanded="true" aria-controls="morePricing" class="collapsed ml-3">
            More pricing 
          </a>
        </span>
        @if($errors->has('selling_price'))
          <label id="selling_price-error" class="error" for="selling_price">{{ $errors->first('selling_price') }}</label>
        @endif  
    </div>
  </div>    
</div>
<!-- start pricing -->
<div id="morePricing" class="collapse" role="tabcard">
  <div class="row column-seperation">
    <div class="col-sm-6">
      <div class="form-group row align-items-center{{ $errors->has('cost_price') ? ' has-error' : '' }}">
          <label for="cost_price" class="control-label col-sm-3 mb-0">Cost Price</label>
          <div class="col-sm-5">
            <div class="input-group">
              <div class="input-group-addon">
                {{ $store->setting->currency->iso_code }}
              </div>
              <input type="text" class="form-control autonumeric" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" id="cost_price">
            </div>
          </div>
          @if($errors->has('cost_price'))
            <label id="cost_price-error" class="error" for="cost_price">{{ $errors->first('cost_price') }}</label>
          @endif  
      </div>
    </div>
  </div> 
  <div class="row column-seperation">
    <div class="col-sm-6">
      <div class="form-group row align-items-center{{ $errors->has('special_price') ? ' has-error' : '' }}">
        <label for="special_price"  class="control-label col-sm-3 mb-0">Special Price</label>
        <div class="col-sm-5">
          <div class="input-group">
            <div class="input-group-addon">
              {{ $store->setting->currency->iso_code }}
            </div>
            <input type="text" class="form-control autonumeric" name="special_price" id="special_price" value="{{ old('special_price', $product->special_price) }}">
          </div>
        </div>
        @if($errors->has('special_price'))
            <label id="special_price-error" class="error" for="special_price">{{ $errors->first('special_price') }}</label>
        @endif  
      </div>
    </div>
  </div>
  
  <div class="row column-seperation ml-5">
    <div class="col-sm-6 form-group">
        <label for="special_active_date">Special Active On</label>
        <div class="row">
          <div class="col-sm-6">
              <div id="special_start_date" class="form-group date{{ $errors->has('special_start_date') ? ' has-error' : '' }}">
                <div class="input-group">
                  <input type="text" id="special_start_date" class="form-control" name="special_start_date" placeholder="YYYY-MM-DD" @if(request()->is('merchant/product/*/add')) value="{{ old('special_start_date') }}" @elseif($product->special_active_on) value="{{ $product->special_active_on->toDateString() }}" @endif>
              
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
                @if($errors->has('special_start_date'))
                    <label id="special_start_date-error" class="error" for="special_start_date">{{ $errors->first('special_start_date') }}</label>
                @endif 
              </div>
          </div>
          <div class="col-sm-6"> 
              <div class="form-group bootstrap-timepicker{{ $errors->has('special_start_time') ? ' has-error' : '' }}">
                <div class="input-group">
                  <input id="special_start_time" type="text" class="form-control" name="special_start_time" @if(request()->is('merchant/product/*/add')) value="{{ old('special_start_time') }}" @elseif($product->special_active_on) value="{{ $product->special_active_on->format('h:i A') }}" @endif>
                  <div class="input-group-addon"><i class="pg-clock"></i></div>
                </div>
                 
                @if($errors->has('special_start_time'))
                    <label id="special_start_time-error" class="error" for="special_start_time">{{ $errors->first('special_start_time') }}</label>
                @endif 
              </div>
          </div>
        </div>
    </div> 
  </div>
  <div class="row column-seperation ml-5">
    <div class="col-sm-6 form-group">
        <label for="special_end_date">Special End On</label>
        <div class="row">
          <div class="col-sm-6">
              <div id="special_end_date" class="form-group date{{ $errors->has('special_end_date') ? ' has-error' : '' }}">
                <div class="input-group">
                  <input type="text" id="special_end_date" class="form-control" name="special_end_date" placeholder="YYYY-MM-DD"  @if(request()->is('merchant/product/*/add')) value="{{ old('special_end_date') }}" @elseif($product->special_end_on)  value="{{ $product->special_end_on->toDateString() }}" @endif>
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
    
                @if($errors->has('special_end_date'))
                    <label id="special_end_date-error" class="error" for="special_end_date">{{ $errors->first('special_end_date') }}</label>
                @endif 
              </div>
          </div>
          <div class="col-sm-6"> 
              <div class="form-group bootstrap-timepicker{{ $errors->has('special_end_time') ? ' has-error' : '' }}">
                <div class="input-group">
                  <input id="special_end_time" type="text" class="form-control" name="special_end_time"  @if(request()->is('merchant/product/*/add')) value="{{ old('special_end_time') }}" @elseif($product->special_end_on) value="{{ $product->special_end_on->format('h:i A') }}" @endif>
                  <div class="input-group-addon"><i class="pg-clock"></i></div>
                </div>
                  
                @if($errors->has('special_end_time'))
                    <label id="special_end_time-error" class="error" for="special_end_time">{{ $errors->first('special_end_time') }}</label>
                @endif 
              </div>
          </div>
        </div>
    </div> 
  </div>
</div>
<!-- end pricing -->