<p class="text-uppercase"><strong>Product Identifiers</strong></p>
<!-- start ta> -->
<div class="row column-seperation">
  <div class="col-sm-4">
    <div class="form-group{{ $errors->has('tax_class') ? ' has-error' : '' }}">
      <label for="tax_class">Tax class</label>
      @if(auth()->user()->can('add tax classes'))
      <a href="javascript:;" data-toggle="modal" data-target="#createNewTaxClass">Create New</a>
      @endif
      <select name="tax_class" id="tax_class" class="full-width" data-init-plugin="select2">
        @if($taxes->count())
          @foreach($taxes as $tax)
          <option value="{{ $tax->id }}" {{ old('tax_class', $product->tax_class_id) == $tax->id ? 'selected' : '' }}>{{ $tax->name }}</option>
          @endforeach
        @endif
      </select>
      @if($errors->has('tax_class'))
        <label id="tax_class-error" class="error" for="tax_class">{{ $errors->first('tax_class') }}</label>
      @endif
    </div>
  </div>
</div>
<!-- end ta> -->
<!-- start brand -->
<div class="row column-seperation">
  <div class="col-sm-4">
    <div class="form-group">
      <label for="brand">Brand</label>
      @if(auth()->user()->can('add brands'))
      <a href="javascript:;" data-toggle="modal" data-target="#createNewBrand">Create New</a>
      @endif
      <select class="full-width" id="brand" name="brand">
        <option value="">Choose brand</option>
        @if($brands->count())
          @foreach($brands as $brand)
          <option value="{{ $brand->id }}" @if(old('brand', $product->brand_id) == $brand->id) selected @endif>{{ title_case($brand->name) }}</option>
          @endforeach
        @endif
      </select>
    </div>
  </div>
</div>
<!-- end brand -->
<div class="row column-seperation">
  <div class="col-sm-4">
    <div class="form-group{{ $errors->has('barcode') ? ' has-error' : '' }}">
      <label for="compare_price">Barcode</label>
      <input type="text" class="form-control" name="barcode" id="barcode" value="{{ old('barcode', $product->barcode) }}">
      @if($errors->has('barcode'))
        <label id="barcode-error" class="error" for="barcode">{{ $errors->first('barcode') }}</label>
      @endif
    </div>
  </div>
</div>
<div class="row column-seperation">
  <div class="col-sm-4">
    <div class="form-group{{ $errors->has('isbn') ? ' has-error' : '' }}">
      <label for="isbn">ISBN</label>
      <input type="text" class="form-control" name="isbn" id="isbn" value="{{ old('isbn', $product->isbn) }}">
      @if($errors->has('isbn'))
        <label id="isbn-error" class="error" for="isbn">{{ $errors->first('isbn') }}</label>
      @endif
    </div>
  </div>
</div>
<div class="row column-seperation">
  <div class="col-sm-4">
    <div class="form-group{{ $errors->has('upc') ? ' has-error' : '' }}">
      <label for="upc">UPC Barcode</label>
      <input type="text" class="form-control" name="upc" id="upc" value="{{ old('upc', $product->upc) }}">
      @if($errors->has('upc'))
        <label id="upc-error" class="error" for="upc">{{ $errors->first('upc') }}</label>
      @endif
    </div>
  </div>
</div>