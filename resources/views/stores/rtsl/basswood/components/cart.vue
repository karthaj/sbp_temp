<template>
	
<div class="Shopping-cart-area pt-80 pb-40">
    <div v-if="!loading" class="container vld-parent">
        <div class="row">
            <div class="col-12">
                <div v-if="cart.items && cart.items.length" class="table-content table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="product-remove">remove</th>
                                <th class="product-thumbnail">images</th>
                                <th class="cart-product-name">Product</th>
                                <th class="product-price">Unit Price</th>
                                <th class="product-quantity">Quantity</th>
                                <th class="product-subtotal">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<product v-for="item in cart.items" :key="item.id" :product="item" :stockReserved="cart.stock_reserved"></product>
                        </tbody>
                    </table>
                </div>
                <p v-else class="text-center">Your cart is currently empty.</p>
                    
                <div v-if="cart.total_price" class="row">
                    <div class="col-md-5 ml-auto">
                        <div class="cart-page-total">
                            <h2>Cart totals</h2>
                            <ul>
                                <li>Subtotal <span>{{ total }}</span></li>
                            </ul>
                            <a :href="`cart/${this.cart.id}/reserve`" :class="{'disabled-control': !cart.inventory_check}">Proceed to checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <loading v-else :active.sync="loading" :is-full-page="false"></loading>
</div>

</template>

<script>
    import Loading from 'vue-loading-overlay';
	import bus from '../assets/js/bus'
	import settings from '../assets/js/settings.js'
	import product from '../partials/cart/product'

	export default {
		props: ['endpoint'],
		mixins: [settings],
		components: {
			product,
            Loading
		},
        data () {
            return {
                grand_total: 0
            }
        },
        computed: {
            total: {
                set: function(amount) {
                  this.grand_total = this.formatMoney(amount);
                },
                get: function() {
                  return this.grand_total;
                }
            },
        },
        updated () {

            this.total = this.cart.total_price;
            
        },
        mounted () {

            bus.$on('currency.switched', () => {
                this.total = this.cart.total_price;
            });

        }
	}
   
</script>