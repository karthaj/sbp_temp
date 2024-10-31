<p class="text-uppercase"><strong>Availability</strong></p>
@if(request()->is('merchant/product/'.$product->slug.'/add'))
  <div class="row column-seperation">
    <div id="productPublish" class="col-sm-6 form-group">
      <label for="compare_price">Publish product on</label>
      <div class="row">
        <div class="col-sm-6">
          <div id="datepicker-component" class="form-group date{{ $errors->has('publish_date') ? ' has-error' : '' }}">
            <div class="input-group">
              <input type="text" class="form-control" id="publish_date" name="publish_date" @if(request()->is('merchant/product/*/add')) value="{{ $product->freshTimestamp()->timezone(request()->tenant()->setting->timezone->timezone)->toDateString() }}" @else value="{{ $publish_date }}" @endif>
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
            </div>
            @if($errors->has('publish_date'))
              <label id="publish_date-error" class="error" for="publish_date">{{ $errors->first('publish_date') }}</label>
            @endif
          </div>
        </div>
        <div class="col-sm-6"> 
          <div class="form-group input-group bootstrap-timepicker">
            <input id="timepicker" type="text" class="form-control" name="publish_time" @if(request()->is('merchant/product/*/add')) value="{{ old('publish_time') }}" @else value="{{ $publish_time }}" @endif>
            <div class="input-group-addon"><i class="pg-clock"></i></div>
          </div>
      </div>
    </div>
    </div>
  </div>
@endif
@if(request()->is('merchant/product/'.$product->slug.'/edit'))
  <div id="productLive" class="row column-seperation">
    <div class="col-sm-4">
      <div class="form-group{{ $errors->has('live') ? ' has-error' : '' }}">
        <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="info" @if($product->active == 1) checked @endif id="live" name="live" value="1">
        <label for="live">Live</label>
      </div>
      @if($errors->has('live'))
        <label id="live-error" class="error" for="live">{{ $errors->first('live') }}</label>
      @endif
    </div>
  </div>
@endif