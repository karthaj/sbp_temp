<template>
	
	<span class="b-add_cart">
      <template v-if="!product.backorder && !product.preorder && !product.in_stock">
      	<i class="aapl-eye"></i>
      	<a :href="product.url">View</a>
      </template>
      <template v-else>
      	<i class="aapl-cart"></i>
      	<a href="#" @click.prevent="addToCart" v-if="product.type === 'standard'">{{ label }}</a>
      	<a href="javascript:void(0);" v-else data-toggle="modal" data-target="#b-qucik_view" @click="quickview">Select Options</a>
      </template>
  	</span>

</template>

<script>
	import bus from '../assets/bus'
	import settings from '../assets/settings'

	export default {
		props: {
			endpoint: {
				type: String,
				required: true
			},
			product: {
				type: Object,
				required: true
			}
		},
		mixins: [settings],
		data() {
			return {
				label: 'Add to cart'
			}
		},
		methods: {
			quickview() {
				axios.get(this.endpoint).then((response) => { 
		           bus.$emit('quickview', response.data);
		        }).catch((error) => {
		            console.log(error)
		        })
			},
			addToCart () {
		        axios.post('/cart/add', {
		            product_id: this.product.id,
		            qty: 1,
		        }).then((response) => { 
		           this.getCart();
		           bus.$emit('added-to-cart');
		        }).catch((error) => {
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
		mounted() {
			
			if(this.product.preorder) {
				this.label = 'Pre-Order';
			}

		}
	}

</script>