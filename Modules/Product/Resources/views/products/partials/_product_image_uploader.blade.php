<div class="row">
  <div class="col-sm-11">
    <div id="product-images-dropzone-error" class="text-danger"></div>
    <br>
    <div id="product-images-container" class="m-b-2">
      <div id="product-images-dropzone" class="dropzone ui-sortable" url-upload="{{ route('upload.store', $product->slug) }}" url-sort="{{ route('upload.sort') }}" data-max-size="2" style="height: 340px;">

        <div class="dz-default dz-message openfilemanager dz-clickable">
            <i class="fa fa-camera fa-4x"></i><br>
            Drop images here<br>
            <a>or select files</a><br>
            <small>
                JPG, GIF or PNG format.
            </small>
        </div>

        @if($product->images->count())
          <div class="dz-preview disabled openfilemanager">
              <div><span>+</span></div>
          </div>
        @foreach($product->images as $image)
          <div class="dz-preview dz-image-preview dz-complete ui-sortable-handle"
               data-id="{{ $image->id }}"
               url-delete="{{ url('merchant/upload/'.$product->slug.'/'.$image->id) }}"
               url-update="{{ url('merchant/upload/update/'.$product->slug.'/'.$image->id) }}">
            <div class="dz-image bg">
              <img data-dz-thumbnail alt="{{ $image->alt_text }}" src="{{ asset('stores').'/'.$store->domain.'/product/'.$image->home_default }}">
            </div>
            <div class="dz-details">
              <div class="dz-size"><span data-dz-size=""></span></div>
              <div class="dz-filename"><span data-dz-name=""></span></div>
            </div>
            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress="" style="width: 100%;"></span></div>
            <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
            <div class="dz-success-mark"></div>
            <div class="dz-error-mark"></div>
            @if($image->cover)
              <div class="iscover">Cover</div>
            @endif
          </div>
        @endforeach
      @else
      <div class="dz-preview disabled openfilemanager">
          <div><span>+</span></div>
      </div>
      @endif
      </div>
      <div class="dropzone-expander text-xs-center col-md-12">
        <span class="expand">View all images</span>
        <span class="compress">View less</span>
      </div>
    </div>
  </div>
</div>