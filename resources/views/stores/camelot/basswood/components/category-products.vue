<template>

<div class="shop-area pt-60">
    <div class="col-12 text-center">
        <div class="section-title">
            <h2>{{ category.name }}</h2>
        </div>
    </div>
    
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="category-cover img-full">
                     <img :src="getCategoryImage" :alt="category.name">
                </div>
                <div v-if="category.description" class="category-description" v-html="category.description"></div>    
            </div>    
        </div>
        <div class="row">
             <div class="col-lg-12 col-md-12 col-sm-12">
                <div v-if="!loading" class="shop-layout">
                    <div v-if="products.length" class="shop-topbar-wrapper d-md-flex flex-row-reverse justify-content-md-between align-items-center">
                        <sorter></sorter>
                    </div>
                     
                     
                    <div v-if="products.length" class="row">
                        <div v-for="product in products" :key="product.id" class="col-lg-3 col-md-3 col-sm-3">
                        	<product :product="product" :authenticated="authenticated" :wishlists="wishlists"></product>
                        </div>   
                    </div>
                     
                    <pagination v-if="products.length && meta.pagination" :pagination="meta.pagination" for="products"></pagination>

                </div>
                <div v-else class="pt-50 pb-50 vld-parent">
					<loading :active.sync="loading" 
				    :is-full-page="false"
				    loader="dots"
				    ></loading>
				</div>
                <p v-if="!loading && products.length === 0" class="text-center my-5">No product found under this category.</p>
            </div>     
        </div>
     </div>
</div>

</template>

<script>
	import bus from '../assets/js/bus.js'
	import Loading from 'vue-loading-overlay';
	import Pagination from '../partials/pagination/pagination'
	import sorter from '../partials/product/sorter'
	import Product from '../partials/product-grid/product'

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
		},
		components: {
			Loading,
			Pagination,
			sorter,
			Product
		},
		data () {
			return {
				category: '',
		        products: [],
		        meta: null,
		        sortBy: 'newest',
		        loading: true
			}
		},
		watch: {
			'$route.query' (query) {
				this.getProducts(query.page);
			}
		},
		computed: {
			getCategoryImage() {
				if(this.category.image.cover) {
					return this.category.image.cover;
				}

				return 'https://via.placeholder.com/870x272/f2f2f2/dcdfde?text=870x272';
			}
		},
		methods: {
			getProducts (page) {
				axios.get(`/api/categories/${this.category.handle}/products`, {
					params: {
						page,
						sort: this.sortBy
					}					
				}).then((response) => { 
                    this.products = response.data.data;
                    this.meta = response.data.meta;
                    this.loading = false;
                })
			},
			switchPage(page = this.route.query.page) {
				this.$router.replace({
					query: {
						page,
						sort: this.sortBy
					}
				})
			}
		},
		created () {
	      this.category = JSON.parse(document.getElementById('categoryJson').innerHTML).data;
	    },
		mounted() {
			
			bus.$on('products.switched-page', this.switchPage);
			bus.$on('list-sorted', (sort) => {
				this.sortBy = sort;
				this.getProducts(1);
			});
			
			this.getProducts(1);
		}
	}
   
</script>