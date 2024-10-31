<template>
	
<ul>
	<li v-if="favorite">
    	<a href="#0" class="tooltip-1" data-toggle="tooltip" data-placement="left" title="Listed" data-original-title="Listed" @click.prevent="remove">
    		<i v-if="processing" class="ti-reload ti-spin">Loading</i>
    		<i v-else class="ti-heart"></i><span>Listed</span>
    	</a>
    </li>
    <li v-else>
    	<a href="#0" class="tooltip-1" data-toggle="tooltip" data-placement="left" title="Add to favorites" data-original-title="Add to favorites" @click.prevent="wishlist">
    		<i v-if="processing" class="ti-reload ti-spin">Loading</i>
    		<i v-else class="ti-heart"></i><span>Add to favorites</span>
    	</a>
    </li>
	<li v-if="(product.in_stock || product.preorder || product.backorder) && product.type != 'variant'">
		<a href="#0" @click.prevent="addToCart" class="tooltip-1 cart-item" :class="action" data-toggle="tooltip" data-placement="left" title="Add To Cart" data-original-title="Add to cart">
			<i class="ti-shopping-cart"></i><span>Add to cart</span>
			<i class="ti-reload ti-spin"></i><span>Adding</span>
			<i class="ti-check"></i><span>Added</span>
		</a>
	</li>
	<li v-else-if="product.type == 'variant'">
		<a :href="product.url" class="tooltip-1" data-toggle="tooltip" data-placement="left" title="View options" data-original-title="View options">
			<i class="ti-eye"><span>View options</span></i>
		</a>
	</li>
</ul>

</template>

<script>
	
import bus from '../../assets/js/bus'
import settings from '../../assets/js/settings'

export default {
	props: {
		product: {
			type: Object,
			required: true
		},
		authenticated: {
	        type: Boolean,
	        default: false
      	},
      	wishlists: {
	        type: Array,
	        default() {
	            return []
	        }
      	},
	},
	data () {
		return {
			processing: false,
			action: {
				loading: false,
				'add-item': false,
			},
			list: []
		}
	},
	mixins: [settings],
	computed: {
		favorite () {

			if(this.list && this.list.length) {
				var exists = this.list.find((p) => {
		            return p === this.product.id
		        })

		        if(exists) {
		        	return true;
		        }
			}

			return false;
			

		}
	},
	methods: {
		wishlist () {

			if(!this.authenticated) {
				window.location.pathname = 'login';
				return;
			}

			this.processing = true;

			axios.post('/wishlist', {
				product_id: this.product.id
			}).then((response) => { 
				this.list.push(this.product.id)
	           	this.processing = false;
	        }).catch((error) => {
	            console.log(error);
	            this.processing = false;
	        })
		},
		remove () {

			if(!this.authenticated) {
				window.location.pathname = 'login';
				return;
			}

			this.processing = true;

			axios.post('/wishlist/item/remove', {
				product_id: this.product.id
			}).then((response) => { 
	           this.list.splice(this.list.indexOf(this.product.id), 1);   
	           this.processing = false;
	        }).catch((error) => {
	            console.log(error);
	            this.processing = false;
	        })
		},
		addToCart () {

			this.action.loading = true;
			this.action['add-item'] = true;

			axios.post('/cart/add', {
                product_id: this.product.id,
                qty: 1
            }).then((response) => { 

               	this.getCart();
               	this.action.loading = false;
		  		this.action['add-item'] = true;

               	setTimeout(() => { 
	               	this.action.loading = false;
					this.action['add-item'] = false;
               	}, 1000);

            }).catch((error) => {

            	this.action.loading = false;
				this.action['add-item'] = false;

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
		this.list = this.wishlists;
	}
}

</script>