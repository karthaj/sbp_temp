<template>
	<div class="container margin_30">
		<div v-if="!processing && products.length" class="row small-gutters">
			<div v-for="product in products" :key="product.id" class="col-6 col-md-4 col-xl-3">
		    	<product :product="product"></product>	   
		    </div>
		</div>
		<div v-else-if="!processing && !products.length" class="row small-gutters">
			<div class="col text-center">
		 		<p>No products found</p>
		 		<a class="btn_1" href="/" role="button">Go to homepage</a>
		 	</div>
		</div>
		<div v-if="meta && meta.pagination && meta.pagination.total_pages > 1" class="pagination__wrapper">
	        <pagination :pagination="meta.pagination" for="products"></pagination>
	    </div>
	</div>
</template>

<script>
	
	import bus from '../assets/js/bus.js'
	import product from '../partials/product/grid-item.vue'
	import pagination from '../partials/common/pagination.vue'

	export default {
		props: {
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
	        endpoint: {
	        	type: String,
	        	required: true
	        }
	    },
	    components: {
	    	product,
	    	pagination
	    },
	    data () {
			return {
		        products: [],
		        meta: null,
	         	sortBy: 'newest',
		        processing: true
			}
		},
		watch: {
			'$route.query' (query) {
				this.getProducts(query.page);
			}
		},
		methods: {
			getProducts (page) {
				axios.get(this.endpoint, {
					params: {
						page,
						sort: this.sortBy
					}					
				}).then((response) => { 
                    this.products = response.data.data;
                    this.meta = response.data.meta;
                    this.processing = false;
                })
			},
			switchPage(page = this.$route.query.page) {
				this.$router.replace({
					query: {
						page,
						sort: this.sortBy
					}
				})
			}
		},
	    mounted() {
			
			bus.$on('products.switched-page', this.switchPage);
			bus.$on('list.sort', (sort) => {
				this.sortBy = sort;
				this.getProducts(1);
			});
			
			this.getProducts(this.$route.query.page ? this.$route.query.page : 1);
		}
	}

</script>