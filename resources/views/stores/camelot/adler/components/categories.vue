<template>

	<div v-if="!loading && categories.length">
		<div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="b-decent-title-wrap my-5">
              <div class="b-decent-title">
                <span>categories</span>
              </div>
            </div>
          </div>
        </div>
        <div class="b-products b-product_grid b-product_grid_four mb-4">
          	<div class="container">
                  <div class="row clearfix">
                      <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12" v-for="category in categories" :key="category.id">
                      <div class="b-product_grid_single">
                         <div class="b-product_grid_header">
                             <a :href="category.url" @click="viewCategory(category.handle)">
                               <img :src="getCategoryImage(category.image.cover)" class="img-fluid img-switch d-block" :alt="category.name">
                             </a>
                         </div>
                         <div class="b-product_grid_info">
                              <h3 class="product-title">
                                  <a :href="category.url" @click="viewCategory(category.handle)">{{ category.name }}</a>
                              </h3>
                         </div>
                      </div>
                    </div>
                  </div>
          	</div>
          	<pagination v-if="meta.pagination" :pagination="meta.pagination" for="categories"></pagination>
      </div>	
	</div>
	
	<p v-else-if="!loading && categories.length === 0" class="text-center my-5">No categories found.</p>

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
		        placeholder: 'https://via.placeholder.com/263x336?text=Image',
		        categories: [],
		        meta: null,
		        loading: true
			}
		},
		watch: {
			'$route.query' (query) {
				this.getCategories(query.page);
			}
		},
		methods: {
			getCategories (page = this.route.query.page) {
				axios.get('/api/categories', {
					params: {
						page
					}
				}).then((response) => { 
                    this.categories = response.data.data;
                    this.meta = response.data.meta;
                    this.loading = false;
                })
			},
			switchPage(page) {
				this.$router.replace({
					name: 'categories.index',
					query: {
						page,
						sort: this.sortBy
					}
				})
			},
			getCategoryImage (image) {
			  	if(!image) {
			  		return this.placeholder
			  	}

			  	return image;
			},
			viewCategory (category) {
				bus.$emit('category-view', category);
			}
		},
		mounted() {

			this.getCategories(1);

			bus.$on('categories.switched-page', this.switchPage);
			
		}
	}
   
</script>