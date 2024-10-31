<div class="row column-seperation">
  <div class="col-sm-6">
    <div class="form-group">
      <label for="tags">Tags <small>(Hit enter after each tag)</small></label>
      <input class="tagsinput form-control" data-role="tagsinput" type="text" name="tags" value="{{ old('tags', $product->tags) }}">
    </div>
  </div>
</div>
<hr>
<p class="text-uppercase"><strong>Search Engine Optimization</strong></p> 
<div class="row column-seperation">
  <div class="col-sm-11 form-group{{ $errors->has('meta_title') ? ' error' : '' }}">
      <label for="meta_title">Page title</label>
      <span class="text-muted pull-right">Max 70 characters</span>
      <input type="text" class="form-control" id="meta_title" name="meta_title" maxlength="70" value="{{ old('meta_title', $product->meta_title) }}"> 
      @if($errors->has('meta_title'))
        <label id="meta_title-error" class="error" for="meta_title">{{ $errors->first('meta_title') }}</label>
      @endif   
  </div>
</div>
<div class="row column-seperation">
    <div class="col-sm-11 form-group{{ $errors->has('meta_description') ? ' error' : '' }}">
        <label for="page_title">Meta description</label>
        <span class="text-muted pull-right">Max 160 characters</span>
        <textarea name="meta_description" id="meta_description" cols="30" rows="5" class="form-control" maxlength="160">{{ old('meta_description', $product->meta_description) }}</textarea>
        @if($errors->has('meta_description'))
          <label id="meta_description-error" class="error" for="meta_description">{{ $errors->first('meta_description') }}</label>
        @endif   
    </div>
</div>
<div class="row column-seperation">
    <div class="col-sm-11 form-group{{ $errors->has('meta_keywords') ? ' error' : '' }}">
        <label for="meta_keywords">Meta Keywords <small>(Hit enter after each keyword)</small></label>
        <input class="tagsinput form-control}" data-role="tagsinput" type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}">
        @if($errors->has('meta_keywords'))
          <label id="meta_keywords-error" class="error" for="name">{{ $errors->first('meta_keywords') }}</label>
        @endif
    </div>
</div>
<div class="row column-seperation">
    <div class="col-sm-11 form-group{{ $errors->has('url_handle') ? ' has-error' : '' }}"> 
        <label for="url_handle">URL and handle</label>
        <input type="text" id="url_handle" name="url_handle" class="form-control" value="{{ old('url_handle', $product->slug) }}">
        @if($errors->has('url_handle'))
          <label id="url_handle-error" class="error" for="url_handle">{{ $errors->first('url_handle') }}</label>
        @endif
    </div>
</div>