<template>
	
<div v-if="products.length" class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="related-products-area pb-20">
		        <div class="container">
		            <div class="row">
		                <div class="col-12">
		                    <div class="section-title mb-50 text-center">
		                        <h2>related products</h2>
		                    </div>
		                </div>
		                <div class="col-12">
		                    <div class="product-slider-wrap row">
		                        <div class="product-slider owl-carousel">
		                            <div v-for="product in products"  class="col-12">
		                                <product :key="product.id" :product="product" :authenticated="authenticated" :wishlists="wishlists"></product>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
    		</div>
        </div>
    </div>
</div>

</template>

<script>
	
import Product from '../product-grid/product'

export default {
	props: {
		products: {
			type: Array,
			required: true
		},
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
		Product
	}
}

</script>