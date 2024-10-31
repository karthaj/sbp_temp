<template>
	
<div v-if="!loading" class="category-collections pt-50">
    <div class="col-12 text-center">
        <div class="section-title text-center">
            <h2>BRANDS</h2>
        </div>
    </div>
    <div v-if="!loading && brands.length" class="d-flex row justify-content-center">
        <div v-for="brand in brands" :key="brand.id" class="col-md-4 text-center pb-3">
            <a :href="brand.url">
                <img class="img-fluid" :src="getBrandImage(brand.image.medium)" :alt="brand.name">
                <h6 class="py-2">{{ brand.name }}</h6>
            </a>
        </div>            
    </div>
	
    <p v-else-if="!loading && brands.length === 0" class="text-center my-5">No brands found.</p>

	<div v-if="brands.length" class="col-12">
		<pagination v-if="meta && meta.pagination" :pagination="meta.pagination" for="brands"></pagination>
	</div>
</div>
<div v-else class="pt-50 pb-50 vld-parent">
	<loading :active.sync="loading" 
    :is-full-page="false"
    loader="dots"
    ></loading>
</div>

</template>


<script>
	import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
	import bus from '../assets/js/bus.js'
	import pagination from '../partials/pagination/pagination.vue'

	export default {
		components: {
			pagination,
			Loading
		},
		data () {
			return {
		        section: '',
		        placeholder: 'https://via.placeholder.com/350x250/f2f2f2/3d3d3d?text=350x250',
		        brands: [],
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
			switchPage(page = this.route.query.page) {
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
			},
			viewCategory (category) {
				bus.$emit('category-view', category);
			}
		},
		mounted() {

			this.getBrands(1);

			bus.$on('brands.switched-page', this.switchPage);
			
		}
	}
   
</script>