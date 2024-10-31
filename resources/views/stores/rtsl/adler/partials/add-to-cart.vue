<template>

	<div class="b-product_single_action clearfix">
	  <div class="b-quantity pull-left">
	    <input type="button" value="-" class="b-minus" @click="decrement">
	    <input type="text" step="1" min="1" title="Qty" class="input-text qty text" size="4" pattern="[0-9]*" inputmode="numeric" v-model.number="qty" v-on:keypress="isNumber($event)">
	    <input type="button" value="+" class="b-plus" @click="increment">
	  </div>
	  <button class="text-uppercase pull-left btn ml-4" v-if="product.type === 'variant'" :disabled="disabled"
	  @click.prevent="addToCart">{{ label }}</button>
	  <button class="text-uppercase pull-left btn ml-4" v-else :disabled="disabled" @click.prevent="addToCart">{{ label }}</button>
	</div>

</template>

<script>
	import bus from '../assets/bus'
	import settings from '../assets/settings'

	export default {
		props: ['product'],
		mixins: [settings],
		data () {
			return {
				disabled: true,
		        label: 'add to cart',
		        product_id: '',
		        attribute_id: '',
		        qty: 1,
		        stock: 0
			}
		},
		watch: {
	      qty: function (val) {

	      	if(!this.product.preorder && !this.product.backorder) {
	      		if(val > this.stock) {
		          this.qty = this.stock;
		        } 
	      	}

	        if(val === '' || val === 0) {
	          this.disabled = true;
	        } else {
	          this.disabled = false;
	        }
	      }
	    },
		methods: {
			increment() {

		        this.qty++;
		        
		    },
		    decrement() {
		        if(this.qty === 1 || this.qty === '' || this.qty <= 0) {
		          this.qty = 1;
		        } else {
		          this.qty--;
		        }
		        
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
		    addToCart () {
		        this.disabled = true;
		        axios.post('/cart/add', {
		            product_id: this.product_id,
		            attribute_id: this.attribute_id,
		            qty: this.qty,
		        }).then((response) => { 
		           this.getCart();
		           bus.$emit('added-to-cart');
		           this.disabled = false;
		        }).catch((error) => {
		        	this.disabled = false;
		        	if(error.response.data.product_id.length) {
		        		this.$snotify.warning(error.response.data.product_id[0]);
		        	} else if(error.response.data.attribute_id.length) {
		        		this.$snotify.warning(error.response.data.attribute_id[0]);
		        	} else if(error.response.data.qty.length) {
		        		this.$snotify.warning(error.response.data.qty[0]);
		        	}
		        })

		    }
		},
		mounted () {

			if(this.product.type !== 'variant') {

				if(!this.product.preorder && !this.product.backorder) {
					this.disabled = !this.product.in_stock;
				} else {
					this.disabled = false;
				}
				
			}
			
	        this.stock = this.product.stock;
	        this.product_id = this.product.id;
	        
	       	if(this.product.preorder) {
	        	this.label = 'pre-order';
	        }

		    bus.$on('variation.refresh', (variation) => {
				
				if(!this.product.preorder && !this.product.backorder) {
					this.disabled = !Boolean(variation.stock);
				} else {
					this.disabled = false;
				}
		         
		        this.stock = variation.stock;
		        this.attribute_id = variation.id;
		        this.qty = 1;

	      	});

		    bus.$on('selection.cleared', () => {
		        this.disabled = true;
		        this.label = 'add to cart';
		    });
		  
		}
	}

</script>