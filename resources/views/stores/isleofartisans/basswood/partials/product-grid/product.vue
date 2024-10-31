<template>
	
<div class="basswood-single-product">
    <div class="product-img">
        <a :href="product.url">
            <img class="first-img" :src="cover" :alt="product.name">
            <img class="hover-img" :src="hoverImage" :alt="product.name">
        </a>
        <span v-if="product.preorder" class="na-sticker">pre order</span>
        <span v-else-if="!product.in_stock && !product.backorder" class="na-sticker">{{ product.outofstock_label }}</span>
        <span v-else-if="discount" class="discount-sticker">-{{ discount }}%</span>
        <product-action :product="product" :authenticated="authenticated" :wishlists="wishlists"></product-action>
    </div>
    <div class="product-content">
        <h4><a :href="product.url">{{ product.name }}</a></h4>
        <div class="product-price">
            <span v-if="product.special_price > 0" class="price">{{ discountPrice }}</span>
            <span v-else class="price">{{ price }}</span>
            <span v-if="product.special_price > 0" class="regular-price">{{ price }}</span>
        </div>
    </div>
</div>

</template>

<script>

import settings from '../../assets/js/settings.js'
import bus from '../../assets/js/bus.js'
import ProductAction from './product-action'
	
export default {
	props: {
		product: {
			type: Object,
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
	mixins: [settings],
	components: {
		ProductAction
	},
	data () {
		return {
			regular_price: 0,
			discount_price: 0,
			placeholder: 'https://via.placeholder.com/350x441/f2f2f2/3d3d3d?text=350x441'
		}
	},
	computed: {
		cover () {
	        if(this.product.cover_image) {
	          return this.product.cover_image.standard;
	        }

	        return this.placeholder;
	    },
	    hoverImage () {
	    	if(this.product.images.length) {
	    		return this.product.images[0].standard; 
	    	}

	    	if(this.product.cover_image) {
	    		return this.product.cover_image.standard;
	    	}

	    	return this.placeholder;
	    },
	    discount () {
			if(this.product.special_price > 0) {
				return Math.floor((this.product.price_min - this.product.special_price) / this.product.price_min * 100);
			}

			return;
		},
		price: {
	        set: function(amount) {
	          this.regular_price = this.formatMoney(amount);
	        },
	        get: function() {
	          return this.regular_price;
	        }
	    },
	    discountPrice: {
	        set: function(amount) {
	        	if(amount > 0) {
	        		this.discount_price = this.formatMoney(amount);
	        	}
	        },
	        get: function() {
	          return this.discount_price;
	        }
	    }
	},
	updated () {
      this.price = this.product.price_min;
      this.discountPrice = this.product.special_price;
    },
    mounted () {

      this.price = this.product.price_min;
      this.discountPrice = this.product.special_price;

      bus.$on('currency.switched', () => {
        this.price = this.formatMoney(this.product.price_min);
      });

    }
}

</script>