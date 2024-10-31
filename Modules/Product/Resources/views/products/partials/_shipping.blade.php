<!-- start shipping -->
<p id="shipping-header" class="text-uppercase"><strong>shipping</strong></p>
<div id="productShipping" class="row column-seperation"> 
  <div class="col-sm-6">
    <div class="form-group align-items-center row{{ $errors->has('weight') ? ' has-error' : '' }}">
      <label for="weight" class="control-label col-sm-2 mb-0">Weight</label>
      <div class="input-group col-sm-6">
        <input type="text" class="form-control autonumeric" name="weight" id="weight" value="{{ old('weight', $product->weight) }}">
        <div class="input-group-addon">
          {{ $store->setting->weight->weight_code }}
        </div>
      </div>
      <span class="more-dimension">
      <a data-toggle="collapse" href="#addDimension" aria-expanded="true" aria-controls="addDimension" class="collapsed">
        Add dimension
      </a>
    </span>
      @if($errors->has('weight'))
        <label id="weight-error" class="error" for="weight">{{ $errors->first('weight') }}</label>
      @endif
    </div>
  </div>
</div>
<div id="addDimension" class="collapse" role="tabcard">
  <div class="row column-seperation"> 
    <div class="col-sm-4">
      <div class="form-group row align-items-center">
        <label for="width" class="control-label col-sm-2 mb-0">Width</label>
        <div class="input-group col-sm-6">
          <input type="text" class="form-control autonumeric" name="width" id="width" value="{{ old('width', $product->width) }}">
          <div class="input-group-addon">
            cm
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row column-seperation"> 
    <div class="col-sm-4">
      <div class="form-group align-items-center row{{ $errors->has('height') ? ' has-error' : '' }}">
        <label for="height" class="control-label col-sm-2 mb-0">Height</label>
        <div class="input-group col-sm-6">
          <input type="text" class="form-control autonumeric" name="height" id="height" value="{{ old('height', $product->height) }}">
          <div class="input-group-addon">
            cm
          </div>
        </div>
        @if($errors->has('height'))
          <label id="height-error" class="error" for="height">{{ $errors->first('height') }}</label>
        @endif
      </div>
    </div>
  </div>
  <div class="row column-seperation"> 
    <div class="col-sm-4">
      <div class="form-group align-items-center row{{ $errors->has('depth') ? ' has-error' : '' }}">
        <label for="depth" class="control-label col-sm-2 mb-0">Depth</label>
        <div class="input-group col-sm-6">
          <input type="text" class="form-control autonumeric" name="depth" id="depth" value="{{ old('depth', $product->depth) }}">
          <div class="input-group-addon">
            cm
          </div>
        </div>
        @if($errors->has('depth'))
          <label id="depth-error" class="error" for="depth">{{ $errors->first('depth') }}</label>
        @endif
      </div>
    </div>
  </div>
</div>
<div class="form-group align-items-center row mt-3{{ $errors->has('shipping_class') ? ' has-error' : '' }}">
  <label for="shipping_class" class="control-label col-sm-2">Shipping class</label>
  <div class="col-sm-4">
    <select name="shipping_class" id="shipping_class" class="form-control" data-init-plugin="select2">
      <option value="">No shipping class</option>
      @if($shipping_classes->count())
        @foreach($shipping_classes as $class)
        <option value="{{ $class->id }}" {{ old('shipping_class', $product->shipping_class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
        @endforeach
      @endif
    </select>
    @if($errors->has('shipping_class'))
      <label class="error">{{ $errors->first('shipping_class') }}</label>
    @endif
  </div>
  <a href="javascript:;" data-toggle="modal" data-target="#createShippingClass" class="col-sm-2">Create New</a>
</div>
<!-- end shipping -->
<hr id="shipping-divider">