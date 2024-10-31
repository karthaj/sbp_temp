<template>
	<div class="b-mini_cart">
      <div class="b-mini_cart_header">
        SHOPPING CART
        <span class="b-close_search" id="b-close_cart"></span>
      </div>
      <ul class="b-mini_cart_items mb-0 list-unstyled" v-if="cart.item_count">
        <li class="clearfix" v-for="product in cart.items" :key="product.id">
          <img :src="product.image" width="50" :alt="product.name">
          <span class="item-name">{{ product.name }}</span>
          <span class="item-price">{{ product.quantity }}&nbsp;x&nbsp;<span>{{ formatMoney(product.selling_price) }}</span></span> 
        </li>
      </ul>
      <div class="shopping-cart-total clearfix pl-3 pr-3 mb-4" v-if="cart.item_count">
        <span class="lighter-text float-left">Total:</span>
        <span class="main-color-text float-right">{{ total }}</span>
      </div>
      <div class="pl-3 pr-3" v-if="cart.item_count">
        <a href="/cart" class="btn d-block mb-2">Cart</a>
      </div>
      <div class="px-3 text-uppercase text-center" v-if="!cart.item_count">
        your cart is empty
      </div>
  </div>
</template>

<script>
	import bus from '../assets/bus'
  import settings from '../assets/settings.js' 

	export default {
    mixins: [settings],
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
    methods: {
      strLimit (value) {
        return value.substr(0, 20) + '...';
      }
    },
    updated () {
      
      this.total = this.cart.total_price;

      if(!this.cart_loading) {
        $("#b-mini_cart .b-cart_totals .b-cart_number").text(this.cart.item_count);
      }

    },
		mounted () {

			bus.$on('added-to-cart', () => {
        $("body").addClass("b-mini_cart_toggle");
      });

      this.getCart();

      bus.$on('currency.switched', () => {
        this.total = this.cart.total_price;
      });

		}
	}

</script>