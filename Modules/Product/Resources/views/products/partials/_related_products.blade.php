<p class="text-uppercase"><strong>Related Products</strong></p>
<div class="row column-seperation">
  <div class="col-sm-11">
    <div class="form-group">
      <label for="condition">Products</label>
      <select name="related_products[]" id="related_products" class="full-width" multiple>
        @if($products->count())
          @foreach($products as $related_product)
            <option value="{{ $related_product->id }}" @if($product->relatedProducts->count()) @if($product->relatedProducts->contains($related_product->id)) selected @endif @endif>{{ $related_product->name }}</option>
          @endforeach
        @else
          <option value="">No products found.</option>
        @endif
      </select>
    </div>
  </div>
</div>