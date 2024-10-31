<template>
<div class="dropdown dropdown-cart">
    <a href="cart.html" class="cart_bt"><strong class="cart-item-count">0</strong></a>
    <div v-if="cart.item_count" class="dropdown-menu">
        <ul>
            <li v-for="product in cart.items" :key="product.id">
                <a :href="product.url">
                    <figure><img :src="product.image" :data-src="product.image" :alt="product.name" width="50" height="50" class="lazy"></figure>
                    <strong><span>{{ product.quantity }}x {{ product.name }}</span>{{ formatMoney(product.selling_price) }}</strong>
                </a>
                <a href="#0" class="action" @click.prevent="removeItemFromCart(product.id)"><i class="ti-trash"></i></a>
            </li>
        </ul>
        <div class="total_drop">
            <div class="clearfix"><strong>Total</strong><span>{{ total }}</span></div>
            <a href="/cart" class="btn_1 outline">View Cart</a>
            <a href="/checkout" class="btn_1">Checkout</a>
        </div>
    </div>
    <div v-else class="dropdown-menu">
        <h6 class="text-uppercase text-center"> your cart is empty</h6>
    </div>
</div>
</template>

<script>
    import bus from '../assets/js/bus'
    import settings from '../assets/js/settings.js'

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
            $(".cart-item-count").text(this.cart.item_count);
          }

        },
        mounted () {

          this.getCart();

          bus.$on('currency.switched', () => {
            this.total = this.cart.total_price;
          });

        }
    }

</script>