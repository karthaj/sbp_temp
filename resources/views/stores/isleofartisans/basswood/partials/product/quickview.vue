<template>
  
<div class="modal fade" id="modal-productDetail" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <image-slide></image-slide>
            <product-content></product-content>
        </div>
      </div>
      <div class="modal-footer">
        <social-share></social-share>
      </div>
    </div>
  </div>
</div>

</template>

<script>

import ImageSlide from './modal/image-slide'
import SocialShare from './modal/social-share'
import ProductContent from './modal/content'

export default {
  components: {
    ImageSlide,
    SocialShare,
    ProductContent
  }
}

</script>