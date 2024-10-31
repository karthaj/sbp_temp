<template>
	
<div class="header-cart">
  <div v-if="cart.item_count" class="checkout">
    <a class="cart-btn" href="/cart">cart</a>
    <a class="cart-btn" href="/checkout">checkout</a>
  </div>
  <li v-if="cart.item_count" class="cart-dropdown mini-cart">
    <div class="cart-total">
      <h5>Subtotal <span class="float-right">{{ total }}</span></h5>
    </div>
    <ul class="cart-items">
      <li v-for="product in cart.items" class="single-cart-item">
          <div class="cart-img">
              <a href="shopping-cart.html"><img :src="product.image" class="img-fluid" :alt="product.name"></a>
              <span class="cart-sticker">{{ product.quantity }}x</span>
          </div>
          <div class="cart-content">
              <h5 class="product-name">{{ product.name }}</h5>
              <span class="product-price">{{ formatMoney(product.selling_price) }}</span>
          </div>
          <div class="cart-item-remove">
              <a title="Remove" href="#"  @click.prevent="removeItemFromCart(product.id)"><i class="fa fa-times"></i></a>
          </div>
      </li>
    </ul>
  </li>
  <li v-else class="cart-empty-title">
    <h2>Your cart is currently empty.</h2>
  </li>
</div>
</template>

<script>
	
import settings from '../../assets/js/settings'
import bus from '../../assets/js/bus'

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