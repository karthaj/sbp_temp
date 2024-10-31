<template>
	
<div class="product-action">
    <ul>
        <li v-if="(product.in_stock || product.preorder || product.backorder) && product.type != 'variant'">
        	<a href="#" @click.prevent="addToCart" class="action-btn cart cart-item" :class="action" title="Add To Cart">
        		<i class="fa fa-circle-o-notch fa-spin"></i>
        		<i class="zmdi zmdi-shopping-cart-plus"></i>
        		<i class="fa fa-times"></i>
        		<i class="fa fa-check"></i>
        	</a>
        </li>
        <li><a href="#modal-productDetail" data-toggle="modal" title="Quick view" @click.prevent="quickview"><i class="zmdi zmdi-search"></i></a></li>
        <li v-if="favorite">
        	<a href="#" title="Wishlist" @click.prevent="remove">
        		<i v-if="processing" class="fa fa-circle-o-notch fa-spin"></i>
        		<i v-else class="zmdi zmdi-favorite"></i>
        	</a>
        </li>
        <li v-else>
        	<a href="#" title="Wishlist" @click.prevent="wishlist">
        		<i v-if="processing" class="fa fa-circle-o-notch fa-spin"></i>
        		<i v-else class="zmdi zmdi-favorite-outline"></i>
        	</a>
        </li>
    </ul>
</div>

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
		quickview() {

			if(!this.product.id) {
				bus.$emit('quickview', this.product);
				return;
			}

			axios.get(`/api/products/${this.product.handle}`).then((response) => { 
	           bus.$emit('quickview', response.data.data);
	        }).catch((error) => {
	            console.log(error)
	        })
		},
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