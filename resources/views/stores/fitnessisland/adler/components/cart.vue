<template>
	
	<section id="b-cart_default">
	    <div class="b-cart b-cart_default pb-5">
	      <div v-if="!cart_loading" class="container vld-parent">
	         <table v-if="cart.items.length" id="cart" class="table table-hover table-condensed" >
	            <thead>
	              <tr>
	                <th style="width:48%">Product</th>
	                <th style="width:10%">Price</th>
	                <th style="width:10%">Quantity</th>
	                <th style="width:22%" class="text-center">Subtotal</th>
	                <th style="width:10%"></th>
	              </tr>
	            </thead>
	            <tbody>
	            	<product v-for="product in cart.items" :key="product.id" :product="product" :stockReserved="cart.stock_reserved"></product>
	            </tbody>
	            <tfoot> 
	              <tr>
	                <td><a :href="shopbox.store.url" class="btn btn-warning"><i class="aapl-chevron-left"></i> Continue Shopping</a></td>
	                <td colspan="2" class="hidden-xs"></td>
	                <td class="hidden-xs text-center"><strong>Total {{ total }}</strong></td>
	                <td><a :href="`cart/${this.cart.id}/reserve`" class="btn btn-success btn-block" :class="{'disabled': !cart.inventory_check}">Checkout <i class="aapl-chevron-right"></i></a></td>
	              </tr>
	            </tfoot>
	          </table>
	          	<div v-else class="text-center b-cart_empty" v-if="!cart.item_count">
			        <i class="aapl-cart-empty"></i>
			        <h2>your cart is empty</h2>
			        <a href="/categories" class="btn">Continue shopping</a>
		       </div>
	      </div>
	      <div v-else class="vld-parent text-center">
          	<loading :active.sync="cart_loading" 
		    :is-full-page="false"
		    ></loading>
          </div>
	    </div>
	  </section>

</template>

<script>
	import Loading from 'vue-loading-overlay';
	import settings from '../assets/settings.js'
	import product from '../partials/cart/product'
	import bus from '../assets/bus.js'

	export default {
		components: {
			product,
			Loading
		},
		data () {
			return {
				grand_total: 0
			}
		},
		mixins: [settings],
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