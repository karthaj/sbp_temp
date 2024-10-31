<template>
	
<div v-if="!loading" class="category-collections pt-50">
    <div class="col-12 text-center">
        <div class="section-title text-center">
            <h2>CATEGORIES</h2>
        </div>
    </div>
    <div v-if="!loading && categories.length" class="d-flex row justify-content-center">
        <div v-for="category in categories" :key="category.id" class="col-md-4 text-center pb-3">
            <a :href="category.url">
                <img class="img-fluid" :src="getCategoryImage(category.image.cover)" :alt="category.name">
                <h6 class="py-2">{{ category.name }}</h6>
            </a>
        </div>            
    </div>
	
    <p v-else-if="!loading && categories.length === 0" class="text-center my-5">No categories found.</p>

	<div v-if="categories.length" class="col-12">
		<pagination v-if="meta && meta.pagination" :pagination="meta.pagination" for="categories"></pagination>
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
			switchPage(page = this.route.query.page) {
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