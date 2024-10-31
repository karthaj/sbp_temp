<p class="text-uppercase"><strong>Product File</strong></p>
@if($product->file)
<div id="fileInfo" class="card card-transparent">
  <div class="card-block">
    <p><span class="font-weight-bold">File Name: </span>{{ $product->file->filename }}</p>
    <p><span class="font-weight-bold">File Size: </span>{{ formattedFileSize($product->file->size) }}</p>
    <p><span class="font-weight-bold">Maximum Downloads: </span>{{ $product->file->maximum_downloads ?: 'Unlimited' }}</p>
    <a href="{{ route('product.file.download', $product->file) }}" class="btn btn-action-add">Download File</a>
  </div>
</div>
@endif
<div class="row column-seperation">
  @if($product->file)
    <div class="col-sm-12 form-group">
      <label for="delete_product_file">Delete currently associated file</label><br>
      <a href="{{ route('product.file.delete', $product->file) }}" id="delete_product_file" class="btn btn-danger">Delete File</a>
    </div>
  @else
    <div class="col-sm-11 form-group{{ $errors->has('product_file') ? ' has-error' : '' }}">
        <label for="product_file">Upload a File</label>
        <input type="file" name="product_file" id="product_file" class="form-control">
        @if($errors->has('product_file'))
          <label id="product_file-error" class="error" for="product_file">{{ $errors->first('product_file') }}</label>
        @endif
    </div>
  @endif
</div>
<div class="row column-seperation">
  <div class="col-sm-4 form-group{{ $errors->has('max_downloads') ? ' has-error' : '' }}">
      <label for="max_downloads">Maximum Downloads</label>
      <span class="ml-2" data-placement="right" data-toggle="tooltip" data-original-title="Number of downloads allowed per customer. Set to 0 for unlimited downloads.">
            <i class="fa fa-question-circle"></i>
      </span>
      <input type="text" name="max_downloads" id="max_downloads" class="form-control" value="0">
      @if($errors->has('max_downloads'))
        <label id="max_downloads-error" class="error" for="max_downloads">{{ $errors->first('max_downloads') }}</label>
      @endif
  </div>
</div>