<template>
	
<div class="col-md-7">
    <div class="single-product-content">
        <h1 class="single-product-name">{{ product.name }}</h1>
        <div class="single-product-price">
            <div v-if="discountPrice" class="product-discount">             
                <span class="regular-price">{{ price }}</span>
                <span class="price">{{ discountPrice }}</span>
                <span v-if="discount" class="discount">-{{ discount }}%</span>
            </div>
            <div v-else class="product-discount"> 
                <span class="price">{{ price }}</span> 
            </div>
        </div>
        <div class="product-info" v-if="product.short_description" v-html="product.short_description"></div>
        <p v-if="product.preorder && product.available_on" class="mt-3 mb-0 font-weight-bold">{{ product.available_on }}</p>
        <div class="single-product-action">
            <product-variation v-if="product.type === 'variant'" :option-selectors="product.options" :variants="product.variants" :preorder="product.preorder" :backorder="product.backorder"></product-variation>
            <add-to-cart :product="product"></add-to-cart>
            <social-share :product="product"></social-share>
        </div>
    </div>
</div>

</template>

<script>
	
import settings from '../../assets/js/settings'
import bus from '../../assets/js/bus'
import ProductVariation from './variation'
import AddToCart from './add-to-cart'
import SocialShare from './social-share'

export default {
    props: {
        product: {
            type: Object,
            required: true
        }
    },
    mixins: [settings],
    components: {
        ProductVariation,
        AddToCart,
        SocialShare
    },
	data () {
        return {
            regular_price: 0,
            discount_price: 0,
            special_price: 0,
            original_price: 0
        }
    },
    computed: {
        discount () {
            if(this.special_price > 0) {
                return Math.floor((this.original_price - this.special_price) / this.original_price * 100);
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
                } else {
                    this.discount_price = 0;
                }
            },
            get: function() {
              return this.discount_price;
            }
        }
    },
    mounted () {

        bus.$on('currency.switched', () => {
            this.price = this.original_price;
            this.original_price = this.original_price;
            this.discountPrice = this.special_price;
            this.special_price = this.special_price;
        });

        this.price = this.product.price_min;
        this.original_price = this.product.price_min;
        this.discountPrice = this.product.special_price;
        this.special_price = this.product.special_price;

        bus.$on('variation.refresh', (variant) => {

            this.price = variant.price;
            this.original_price = variant.price;
            this.discountPrice = variant.special_price;
            this.special_price = variant.special_price;

        });

        bus.$on('selection.cleared', () => {
   
            this.price = this.product.price_min;
            this.discountPrice = this.product.special_price;
            this.special_price = 0;

        });

    }
}

</script>