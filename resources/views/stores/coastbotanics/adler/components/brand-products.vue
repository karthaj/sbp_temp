<template>

	<div :class="{'adler-loading': loading}">
		<div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="b-decent-title-wrap mt-5 mb-0">
              <div class="b-decent-title">
                <span>{{ brand.name }}</span>
              </div>
            </div>
          </div>
        </div>
		
		<sorter v-if="!loading && products.length"></sorter>

		<div v-if="!loading && products.length" class="b-products b-product_grid b-product_grid_four mb-4">
	      	<div class="container">
	          	<div class="row clearfix">
	              	<div  class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12" v-for="product in products"
	              :key="product.id">
	                   	<div class="b-product_grid_single">
	                     	<div class="b-product_grid_header">
		                         <a :href="product.url">
		                           <img :data-src="images(product.images)" :src="cover(product.cover_image)" class="img-fluid img-switch d-block" alt="" style="">
		                         </a><div class="b-hover_img"><a :href="product.url"><img :src="hoverImage(product.images, product.cover_image)" class="img-fluid img-switch d-block" alt=""></a></div> 
		                        <quickview-button :endpoint="`/api/products/${product.handle}`" :pid="product.id"></quickview-button>
		                        <div v-if="!product.backorder && !product.preorder && !product.in_stock" class="b-product_labels b-labels_rounded b-black">
						            <span class="b-product_label">sold out</span>
					            </div>
	                     	</div>
		                    <div class="b-product_grid_info">
		                        <h3 class="product-title">
		                            <a :href="product.url">{{ product.name }}</a>
		                        </h3>
		                        <div class="clearfix">
		                          <div class="b-product_grid_toggle float-left">
		                          	  <span v-if="product.original_sale_price > 0" class="b-price">
		                              	<del><span>{{ product.price }}</span></del> 
		                              	<span class="b-accent_color">{{ product.special_price }}</span>
		                              </span>
		                              <span v-else class="b-price">{{ product.price }}</span>
		                              <quick-action :endpoint="`/api/products/${product.handle}`" :product="product"></quick-action>
		                          </div>
		                        </div>
		                    </div>
	                   	</div>
	            	</div> 
	         	</div>
	      	</div>
	        <pagination v-if="meta.pagination" :pagination="meta.pagination" for="products"></pagination>
	    </div>	

	    <p v-if="!loading && products.length === 0" class="text-center my-5">No products found under this brand.</p>

		<div v-if="loading" class="adler-products-loader"></div>
	</div>
	
	

</template>

<script>
	import settings from '../assets/settings.js'
	import bus from '../assets/bus.js'
	import pagination from '../partials/pagination.vue'
	import sorter from '../partials/sorter.vue'

	export default {
		props: ['config', 'sectionIndex', 'brand'],
		mixins: [settings],
		components: {
			pagination,
			sorter,
		},
		data () {
			return {
		        placeholder: 'https://via.placeholder.com/263x336',
		        products: [],
		        meta: null,
		        sortBy: 'newest',
		        loading: true
			}
		},
		watch: {
			'$route.query' (query) {
				this.loading = true;
				this.getProducts(query.page);
			}
		},
		methods: {
			cover (image) {

		        if(image) {
		          return image.medium;
		        }
		        
		        return this.placeholder;
		    },
	      	images (images) {

		        
		        if(images[0] && images[1]) {

		          return images[0].standard + ', ' + images[1].standard;

		        } else if(images[0]) {

		        	return images[0].standard + ', ' + images[0].standard;

		        }
		        
		        return this.placeholder + ', ' + this.placeholder;
	         
	      	},
	      	hoverImage (images, cover_image) {
	      		
	      		if(images[0]) {

	      			return images[0].standard;

	      		} else if(cover_image) {

	      			return cover_image.standard;

	      		}

		        return this.placeholder;
	      	},
			getProducts (page) {
				axios.get('/api/brands/' + this.brand.slug + '/products', {
					params: {
						page,
						sort: this.sortBy
					}
				}).then((response) => { 
                    this.products = response.data.data;
                    this.meta = response.data.meta;
                    this.loading = false;
                    this.currencyCovert();
                })
			},
			switchPage(page) {
				this.$router.replace({
					query: {
						page,
						sort: this.sortBy
					}
				})
			},
			currencyCovert() {
				this.products.forEach((product) => {
					
					if(!product['original_price']) {
						product['original_price'] = product.price;
					}

					if(!product['original_sale_price']) {
						product['original_sale_price'] = product.special_price;
					}
					
					product.price = this.formatMoney(product['original_price']);
					product.special_price = this.formatMoney(product['original_sale_price']);
				});
			}
		},
		mounted() {
		
			bus.$on('products.switched-page', this.switchPage);
			bus.$on('list-sorted', (sort) => {
				this.sortBy = sort;
				this.loading = true;
				this.getProducts(1);
			});
			
			this.getProducts(1);

			bus.$on('currency.switched', () => {
		        this.getProducts(1);
	      	});

	      	bus.$on('currency.switched', () => {
		        this.currencyCovert();
	      	});
			
		}
	}
   
</script>