<template>
	<tr>
		<td>
			<div class="thumb_cart">
				<img :src="product.image" :data-src="product.image" class="lazy" :alt="product.name">
			</div>
			<span class="item_cart text-capitalize">{{ product.name }} 
				<span v-if="!product.preorder && !product.backorder && !stockReserved && !product.stock_count && product.quantity > product.stock_count" class="ml-2 out-of-stock">Out of stock</span>
			</span>
		</td>
		<td>
			<strong>{{ price }}</strong>
		</td>
		<td>
			<div class="numbers-row">
				<input type="text" value="1" id="quantity_2" class="qty2" v-model.number="product.quantity" v-on:keypress="isNumber($event)">
				<div class="inc button_inc" @click="increment">+</div>
				<div class="dec button_inc" @click="decrement">-</div>
			</div>
		</td>
		<td>
			<strong>{{ subtotal }}</strong>
		</td>
		<td class="options">
			<a href="#" @click.prevent="removeItemFromCart(product.id)"><i class="ti-trash"></i></a>
		</td>
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
		      line_price: 0,
		      regular_price: 0
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
	  	computed : {
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