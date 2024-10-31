<template>	
	<a v-if="favorite" href="#" @click.prevent="remove"><i class="ti-heart"></i><span>Listed</span></a>
	<a v-else href="#" @click.prevent="wishlist"><i class="ti-heart"></i><span>Add to Wishlist</span></a>
</template>

<script>
	export default {
		props: {
			product_id: {
				type: Number,
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
				list: []
			}
		},
		computed: {
			favorite () {
		        if(this.list.length) {
		          var exists = this.list.find((p) => {
		                  return p === this.product_id
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

		      axios.post('/wishlist', {
		        product_id: this.product.data.id
		      }).then((response) => { 
		             this.list.push(this.product.data.id)
		             bus.$emit('wishlist.added', this.list.length)
		          }).catch((error) => {
		              console.log(error);
		          })
	      	},
	      	remove () {

		        if(!this.authenticated) {
		          window.location.pathname = 'login';
		          return;
		        }

		        axios.post('/wishlist/item/remove', {
		          product_id: this.product.data.id
		        }).then((response) => { 
		               this.list.splice(this.list.indexOf(this.product.data.id), 1);   
		               bus.$emit('wishlist.removed', this.list.length)
		            }).catch((error) => {
		                console.log(error);
		            })
	      	}
		},
		mounted () {
			this.list = this.wishlists;
		}
	}
</script>