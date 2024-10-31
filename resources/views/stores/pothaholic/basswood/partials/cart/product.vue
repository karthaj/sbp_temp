<template>
	
<tr>
    <td class="product-remove"><a href="#" @click.prevent="removeItemFromCart(product.id)"><i class="zmdi zmdi-close"></i></a></td>
    <td class="product-thumbnail">
      <a :href="product.url"><img :src="product.image" :alt="product.name"></a>
    </td>
    <td class="product-name">
      <p><a :href="product.url">{{ product.name }}</a></p>
      <p v-if="product.preorder && product.available_on">{{ product.available_on }}</p>
      <template v-if="!product.preorder && !product.backorder">
        <p v-if="!stockReserved && !product.stock_count && product.quantity > product.stock_count" class="out-of-stock">Out of stock</p>
        <p v-else-if="!stockReserved && product.stock_count && product.quantity > product.stock_count" class="out-of-stock">low stock</p>
      </template>
    </td>
    <td class="product-price"><span class="amount">{{ price }}</span></td>
    <td class="product-quantity">
        <div class="cart-plus-minus">
            <input class="cart-plus-minus-box" type="text" name="qtybutton" v-model.number="product.quantity" v-on:keypress="isNumber($event)">
            <div class="dec qtybutton"  @click="decrement"><i class="zmdi zmdi-chevron-down"></i></div>
            <div class="inc qtybutton"  @click="increment"><i class="zmdi zmdi-chevron-up"></i></div>
        </div>
        <small v-if="!product.preorder && !product.backorder && !stockReserved" class="text-muted">({{ product.stock_count }} in stock)</small>
    </td>
    <td class="product-subtotal"><span class="amount">{{ subtotal }}</span></td>
</tr>

</template>

<script>
	
import settings from '../../assets/js/settings.js'
import bus from '../../assets/js/bus.js'

export default {
	props: {
		product: {
			type: Object,
			required: true
		},
    stockReserved: {
      type: Boolean,
      required: true
    }
	},
	mixins: [settings],
  data () {
    return {
      regular_price: 0,
      line_price: 0
    }
  },
	watch: {
      'product.quantity': function (val) {

        if(!this.product.preorder && !this.product.backorder) {
            if(val > this.product.stock_count) {
              this.product.quantity = this.product.stock_count;
            } 
        }

        if(val === '' || val === 0 || val < 0) {
          this.product.quantity = 0;
        } 

        this.update();

      }
  },
  computed: {
    price: {
        set: function(amount) {
          this.regular_price = this.formatMoney(amount);
        },
        get: function() {
          return this.regular_price;
        }
    },
    subtotal: {
        set: function(amount) {
          this.line_price = this.formatMoney(amount);
        },
        get: function() {
          return this.line_price;
        }
    }
  },
	methods: {
		increment() {
        if(this.product.quantity < this.product.stock_count) {
          this.product.quantity++;
        }
        
    },
    decrement() {
        
        this.product.quantity--;
        
    },
    isNumber: function(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
          evt.preventDefault();;
        } else {
          return true;
            }
    },
    update () {
      this.setLoading(true);
      axios.post('/cart/update', {
        id: this.product.id,
        qty: this.product.quantity
      }).then((response) => { 
        this.getCart();
      }).catch((error) => {
          console.log(error)
      })
    }
	},
  updated () {
    this.price = this.product.selling_price;
    this.subtotal = this.product.line_price;
  },
  mounted () {

    this.price = this.product.selling_price;
    this.subtotal = this.product.line_price;

    bus.$on('currency.switched', () => {
      this.price = this.product.selling_price;
      this.subtotal = this.product.line_price;
    });

  }
}

</script>