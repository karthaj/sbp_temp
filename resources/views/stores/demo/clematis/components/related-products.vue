<template>
	
<div v-if="product.related_products.length" class="container margin_60_35">
	<div class="main_title">
        <h2>Related Products</h2>
    </div>
    <div id="ProductSlider-related" data-section-id="related" data-section-type="related_products" class="d-flex justify-content-center owl-carousel owl-theme products_carousel">
		<div v-for="product in product.related_products" :key="product.id" class="item">
			<product :authenticated="authenticated" :wishlists="wishlists" :product="product"></product>
		</div>
    </div>
</div>

</template>

<script>

	import product from '../partials/product/grid-item.vue'

	export default {
		components: {
			product
		},
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
	        }
	    },
	    data () {
	    	return {
	    		product: {}
	    	}
	    },
	    created () {
	      this.product = JSON.parse(document.getElementById('productJson').innerHTML).data;
	    }
	}

</script>