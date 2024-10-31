<template>

	<div :class="{'adler-loading': loading}">
        <div v-if="category.image.cover" class="container">
        	<div class="row">
        		<div class="col-sm-12">
        			<div class="promo-banner text-center vertical-alignment-middle increased-padding cursor-pointer" onclick="window.location.href=`/categories/${category.handle}`">
        				<div class="main-wrapp-img">
		                  	<div class="banner-image">
		                  		<img width="auto" height="400" :src="category.image.cover" :alt="category.name"></div>
		               	</div>
						<div v-if="settings.cat_overlay_text" class="wrapper-content-baner">
		                  	<div class="banner-inner">
		                        <h3><strong>{{ category.name }}</strong></h3>
		                    </div>
		               	</div>
        			</div>
        		</div>
        	</div>
        </div>
        <div v-else class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="b-decent-title-wrap my-5 mb-0">
              <div class="b-decent-title">
                <span>{{ category.name }}</span>
              </div>
            </div>
          </div>
        </div>
        <div v-if="category.description" class="row my-4 mx-2">
        	<div v-html="description" class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
        	</div>
        </div>
		
		<sorter></sorter>

		<div v-if="!loading && products.length" class="b-products b-product_grid b-product_grid_four mb-4">
	      	<div class="container">
	          	<div class="row clearfix justify-content-center">
	              	<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12" v-for="product in products"
	              :key="product.id">
	                   	<div class="b-product_grid_single">
	                     	<div class="b-product_grid_header">
		                         <a :href="product.url">
		                           <img :data-src="images(product.images)" :src="cover(product.cover_image)" class="img-fluid img-switch d-block" :alt="product.name" style="">
		                         </a><div class="b-hover_img"><a :href="product.url"><img :src="hoverImage(product.images, product.cover_image)" class="img-fluid img-switch d-block" :alt="product.name"></a></div> 
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
	
		<p v-if="!loading && products.length === 0 && !category.description" class="text-center my-5">No product found under this category.</p>

		<div v-if="loading" class="adler-products-loader"></div>

	</div>

</template>

<script>
	import settings from '../assets/settings.js'
	import bus from '../assets/bus.js'
	import pagination from '../partials/pagination.vue'
	import sorter from '../partials/sorter.vue'
	const Entities = require('html-entities').AllHtmlEntities;

	export default {
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
		        loading: true,
		        category: ''
			}
		},
		watch: {
			'$route.query' (query) {
				this.loading = true;
				this.getProducts(query.page);
			}
		},
		computed: {
			categoryImage: function () {
			var image = ''
		      if(this.category.cover_image) {
		      	image =  this.shopbox.store.categoryPath + this.category.cover_image
		      }
		      
		      return {
		      	backgroundImage: 'url(' + image + ')',
		      	backgroundSize: 'cover',
		      	backgroundRepeat: 'no-repeat'
		      }
		    },
		    description: function () {
		    	const entities = new Entities();
		    	return entities.decode(this.category.description);
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
				axios.get(`/api/categories/${this.category.handle}/products`, {
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
		created () {
	      this.category = JSON.parse(document.getElementById('categoryJson').innerHTML).data;
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