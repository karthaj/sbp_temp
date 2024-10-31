<template>

	<div v-if="!loading && brands.length">
		<div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="b-decent-title-wrap my-5">
              <div class="b-decent-title">
                <span>brands</span>
              </div>
            </div>
          </div>
        </div>
		<div class="b-products b-product_grid b-product_grid_four mb-4">
              <div class="container">
                  <div class="row clearfix">
                      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12" v-for="brand in brands" :key="brand.id">
                      <div class="b-product_grid_single">
                         <div class="b-product_grid_header">
                             <a :href="brand.url">
                               <img :src="getBrandImage(brand.image.medium)" class="img-fluid img-switch d-block" :alt="brand.name">
                             </a>
                         </div>
                         <div class="b-product_grid_info">
                              <h3 class="product-title">
                                  <a :href="brand.url">{{ brand.name }}</a>
                              </h3>
                         </div>
                      </div>
                    </div>
                  </div>
              </div>
              <pagination v-if="meta.pagination" :pagination="meta.pagination" for="brands"></pagination>
          </div>
	</div>
	
	<p v-else-if="!loading && brands.length === 0" class="text-center my-5">No brands found.</p>
	
	<div v-else-if="loading" class="pt-50 pb-50 text-center vld-parent">
		<loading :active.sync="loading" 
	    :is-full-page="false"
	    loader="dots"
	    ></loading>
	</div>

</template>

<script>
	import bus from '../assets/bus.js'
	import pagination from '../partials/pagination.vue'
	import Loading from 'vue-loading-overlay';

	export default {
		props: ['config', 'sectionIndex', 'category'],
		components: {
			pagination,
			Loading
		},
		data () {
			return {
		        section: '',
		        placeholder: 'https://via.placeholder.com/263x336',
		        brands: [],
		        meta: null,
		        loading: true
			}
		},
		watch: {
			'$route.query' (query) {
				this.brands(query.page);
			}
		},
		methods: {
			getBrands (page = this.route.query.page) {
				axios.get('/api/brands', {
					params: {
						page
					}		
				}).then((response) => { 
                    this.brands = response.data.data;
                    this.meta = response.data.meta;
                    this.loading = false;
                })
			},
			switchPage(page) {
				this.$router.replace({
					name: 'brands.index',
					query: {
						page,
						sort: this.sortBy
					}
				})
			},
			getBrandImage (image) {
			  	if(!image) {
			  		return this.placeholder
			  	}

			  	return image;
			}
		},
		mounted() {

			this.getBrands(1);

			bus.$on('brands.switched-page', this.switchPage);
			
		}
	}
   
</script>