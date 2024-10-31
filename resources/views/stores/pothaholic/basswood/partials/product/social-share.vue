<template>
	
<div class="single-product-share mt-20">
    <ul>
       <li class="categories-title">Share :</li>
        <li>
            <a target="_blank" :href="`//www.facebook.com/sharer.php?u=${product.url}`" class="fa fa-facebook"></a>
        </li>
        <li>
            <a target="_blank" :href="`//twitter.com/share?text=${product.name}&amp;url=${product.url}`" class="fa fa-twitter"></a>
        </li>
        <li>
            <a v-if="product.cover_image" target="_blank" :href="`//pinterest.com/pin/create/button/?url=${product.url}&amp;media=${product.cover_image.medium}&amp;description=${product.name}`" class="fa fa-pinterest"></a>
            <a v-else target="_blank" :href="`//pinterest.com/pin/create/button/?url=${product.url}&amp;media=${image}&amp;description=${product.name}`"><i class="fa fa-pinterest"></i></a>
        </li>
    </ul>
</div>

</template>


<script>

import bus from '../../assets/js/bus'
import settings from '../../assets/js/settings'
	
export default {
    props: {
        product: {
            type: Object,
            required: true
        }
    },
    mixins: [settings],
    data () {
        return {
            image: ''
        }
    },
    created () {
        this.image = this.shopbox.store.imagePath + this.defaultImg
    }
}

</script>