<template>
    
<div class="single-product-area pt-90">
    <div class="container">
        <div class="row">
            <image-slide :featured-image="product.data.cover_image" :images="product.data.images"></image-slide>
            <product-content :product="product.data"></product-content>
        </div>
    </div>
    
    <description v-if="product.data.description" :description="product.data.description"></description>
    
    <related-product :products="product.data.related_products" :authenticated="authenticated" :wishlists="wishlists"></related-product>
</div>

</template>

<script>

import ImageSlide from './image-slide'
import ProductContent from './content'
import Description from './description'
import RelatedProduct from './related-product'

export default {
    props: {
        authenticated: {
            type: Boolean,
            default: false
        },
        wishlists: {
            type: Array,
            default() {
                return []
            }
        },
    },
    components: {
        ImageSlide,
        ProductContent,
        Description,
        RelatedProduct
    },
    date () {
        return {
             product: {}
        }
    },
    created () {
      this.product = JSON.parse(document.getElementById('productJson').innerHTML);
    }
}

</script>