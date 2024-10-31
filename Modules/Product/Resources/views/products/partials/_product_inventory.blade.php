<div class="row">
  <div class="col-lg-12">
      <div data-pages="card" class="card card-default card-custom">
        <div class="card-header">
          <div class="card-title"><p class="ui-subheader">Inventory</p>
          </div>
          <div class="card-controls">
            <ul>
              <li><a data-toggle="collapse" class="card-collapse" href="javascript:;"><i class="pg-arrow_maximize"></i></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-block">
            <div class="row{{ $product ? $product->type == 'variation' ? 'hide' : '' : old('product_type') == 'variation' ? ' hide' : '' }}">
              <div class="col-sm-6 form-group">
                  <label for="sku">SKU (Stock Keeping Unit)</label>
                  <input type="text" class="form-control" name="sku" id="sku" value="{{ $product ? $product->sku : old('sku') }}">
              </div>
              <div class="col-sm-6 form-group">
                  <label for="compare_price">Barcode</label>
                  <input type="text" class="form-control" name="barcode" id="barcode" value="{{ $product ? $product->barcode : old('barcode') }}">
              </div>
            </div>
            <div class="row{{ $product ? $product->type == 'variation' ? 'hide' : '' : old('product_type') == 'variation' ? ' hide' : '' }}">
              <div class="col-sm-6 form-group">
                  <label for="isbn">ISBN</label>
                  <input type="text" class="form-control" name="isbn" id="isbn" value="{{ $product ? $product->isbn : old('isbn') }}">
              </div>
              <div class="col-sm-6 form-group">
                  <label for="upc">UPC Barcode</label>
                  <input type="text" class="form-control" name="upc" id="upc" value="{{ $product ? $product->upc : old('upc') }}">
              </div>
            </div>
            <div class="row" id="standardQty">
              <div class="col-sm-6 form-group">
                <label for="qty">Minimum quantity for sale</label>
                <input type="text" id="min_qty" name="min_qty" value="{{ $product ? $product->minimal_quantity : 1 }}" class="form-control autonumeric" data-v-min="1" data-v-max="9999">
              </div>
              <div class="col-sm-4 form-group">
                  <label for="low_qty">Low stock level</label>
                  <input type="text" id="low_qty" name="low_qty" value="{{ $product ? $product->out_of_stock : 1 }}" class="form-control autonumeric" data-v-min="1" data-v-max="9999">  
              </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <div class="checkbox check-success  ">
                        <input type="checkbox" value="1" id="backorders" name="backorders">
                        <label for="backorders">Allow customers to purchase this product when it's out of stock</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label for="instock">Label when in stock</label>
                    <input type="text" id="instock" name="instock" value="{{ $product ? $product->available_now : 'In stock' }}" class="form-control">
                </div>
                <div class="col-sm-6 form-group">
                    <label for="outofstock">Label when out of stock</label>
                    <input type="text" id="outofstock" name="outofstock" value="{{ $product ? $product->available_later : 'Out of stock' }}" class="form-control">
                </div>
            </div>
        </div>
      </div>
  </div>
</div>