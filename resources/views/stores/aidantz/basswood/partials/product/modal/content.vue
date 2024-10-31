<template>
	
<div class="col-md-7">
    <div v-if="product" class="single-product-content">
        <h1 class="single-product-name">{{ product.name }}</h1>
        <div class="single-product-price">
            <div class="product-discount">             
                <span v-if="product.special_price > 0" class="regular-price">{{ price }}</span>
                <span v-if="product.special_price > 0" class="price">{{ discountPrice }}</span>
                <span v-else class="price">{{ price }}</span>
                <span v-if="discount" class="discount-sticker">-{{ discount }}%</span>
            </div>
        </div>
        <div class="product-info" v-if="product.short_description" v-html="product.short_description"></div>
        <div class="single-product-action">
            <product-variation v-if="product.type === 'variant'" :option-selectors="product.options" :variants="product.variants" :preorder="product.preorder" :backorder="product.backorder"></product-variation>
            <add-to-cart :product="product"></add-to-cart>
        </div>
    </div>
    <div v-else class="single-product-content">
        <h1 class="single-product-name">Product Name Here</h1>
        <div class="single-product-price">
            <div class="product-discount">
                <span class="price">LKR 99.00</span>
            </div>
        </div>
        <div class="product-info">
            <p> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
    </div>
</div>

</template>

<script>
	
import settings from '../../../assets/js/settings'
import bus from '../../../assets/js/bus'
import ProductVariation from '../variation'
import AddToCart from '../add-to-cart'

export default {
    mixins: [settings],
    components: {
        ProductVariation,
        AddToCart
    },
	data () {
        return {
            product: '',
            regular_price: 0,
            discount_price: 0
        }
    },
    computed: {
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
    mounted () {

        var vm = this;

        bus.$on('currency.switched', () => {
            this.price = this.formatMoney(this.product.price_min);
        });

        bus.$on('quickview', (product) => {

            if(product) {
                this.product = product;
                this.price = product.price_min;
                this.discountPrice = product.special_price;
            }

        });

        bus.$on('variation.refresh', (variant) => {

            this.price = variant.price;
            this.discountPrice = variant.special_price;

        });

        bus.$on('selection.cleared', () => {
   
            this.price = this.product.price_min;
            this.discountPrice = this.product.special_price;

        });

        $('#modal-productDetail').on('hidden.bs.modal', function (e) {
            vm.product = '';
        })

    }
}

</script>