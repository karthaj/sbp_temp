<template>
	<div class="container margin_30">
	 	<ul v-if="!processing && categories.length" id="banners_grid" class="clearfix">
	 		<transition-group name="fade">
		 		<li v-for="category in categories" :key="category.id">
		 			<a :href="category.url" class="img_container">
		 				<img v-if="category.image.cover" :src="category.image.cover" :data-src="category.image.cover" :alt="category.name" class="lazy"> 
		 				<img v-else :src="`${shopbox.store.assetsPath}banners_cat_placeholder.jpg`" :data-src="`${shopbox.store.assetsPath}banners_cat_placeholder.jpg`" :alt="category.name" class="lazy"> 
		 				<div v-if="settings.show_category_overlay" :style="`background-color: rgba(0, 0, 0, ${ settings.category_overlay_opacity / 100 })`" class="short_info opacity-mask">
		 					<h3 v-if="settings.show_category_title">{{ category.name }}</h3> 
		 				</div>
		 				<div v-else class="short_info">
		 					<h3 v-if="settings.show_category_title">{{ category.name }}</h3> 
		 				</div>
		 			</a>
	 			</li> 
 			</transition-group>
	 	</ul>
		<div v-else-if="!processing && !categories.length" class="row small-gutters">
		 	<div class="col text-center">
		 		<p>No categories found</p>
		 		<a class="btn_1" href="/" role="button">Go to homepage</a>
		 	</div>
		</div>
		<div v-if="meta && meta.pagination && meta.pagination.total_pages > 1" class="pagination__wrapper">
	        <pagination :pagination="meta.pagination" for="categories"></pagination>
	    </div>
	</div>
</template>
<script>
	import bus from '../assets/js/bus.js'
	import settings from '../assets/js/settings.js'
	import pagination from '../partials/common/pagination.vue'

	export default {
		mixins: [settings],
		components: {
			pagination
		},
		data () {
			return {
		        section: '',
		        categories: [],
		        meta: null,
		        processing: true
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
                    this.processing = false;
                })
			},
			switchPage(page = this.route.query.page) {
				this.$router.replace({
					name: 'categories.index',
					query: {
						page
					}
				})
			}
		},
		mounted() {

			this.getCategories(1);

			bus.$on('categories.switched-page', this.switchPage);
			
		}
	}
</script>