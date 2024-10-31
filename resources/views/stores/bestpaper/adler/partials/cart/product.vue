<template>
	
<tr>
	<td data-th="Product">
	  <div class="row align-items-center">
	    <div class="col-sm-2 hidden-xs"><img :src="product.image" :alt="product.name" class="img-fluid"></div>
	    <div class="col-sm-10">
	      <p class="m-0">{{ product.name }}</p>
	      <p v-if="product.preorder && product.available_on">{{ product.available_on }}</p>
	      <template v-if="!product.preorder && !product.backorder">
		      <p v-if="!stockReserved && !product.stock_count && product.quantity > product.stock_count" class="out-of-stock">Out of stock</p>
		      <p v-else-if="!stockReserved && product.stock_count && product.quantity > product.stock_count" class="out-of-stock">low stock</p>
	      </template>
	    </div>
	  </div>
	</td>
	<td data-th="Price">{{ price }}</td>
	<td data-th="Quantity">
	  <span>
	  	<input type="number" min="0" class="form-control text-center" v-model.number="product.quantity" @change="update">
	  <small v-if="!product.preorder && !product.backorder && !stockReserved" class="text-muted">({{ product.stock_count }} in stock)</small>
	  </span>
	</td>
	<td class="text-center" data-th="Subtotal">{{ subtotal }}</td>
	<td class="actions align-middle" data-th="">
	  <button class="btn btn-danger btn-sm" @click.prevent="removeItemFromCart(product.id)"><i class="aapl-cart-remove"></i></button>                
	</td>
</tr>

</template>

<script>

import settings from '../../assets/settings.js'
import bus from '../../assets/bus.js'
	
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
		update () {

			if(!this.product.preorder && !this.product.backorder) {
				if(this.product.quantity > this.product.stock_count) {
					this.product.quantity = this.product.stock_count;
				}
			} 

	        if(this.product.quantity === '' || this.product.quantity < 0) {
	          this.product.quantity = 0;
	        } 

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