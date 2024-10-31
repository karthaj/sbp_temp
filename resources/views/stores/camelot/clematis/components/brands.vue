<template>
	<div class="container margin_30">
	 	<ul v-if="!processing && brands.length" id="banners_grid" class="clearfix">
	 		<transition-group name="fade">
		 		<li v-for="brand in brands" :key="brand.id">
		 			<a :href="brand.url" class="img_container">
		 				<img v-if="brand.image.medium" :src="brand.image.medium" :data-src="brand.image.medium" :alt="brand.name" class="lazy"> 
		 				<img v-else :src="`${shopbox.store.assetsPath}banners_cat_placeholder.jpg`" :data-src="`${shopbox.store.assetsPath}banners_cat_placeholder.jpg`" :alt="brand.name" class="lazy"> 
		 				<div v-if="settings.show_brand_overlay" :style="`background-color: rgba(0, 0, 0, ${ settings.brand_overlay_opacity / 100 })`" class="short_info opacity-mask">
		 					<h3 v-if="settings.show_brand_title">{{ brand.name }}</h3> 
		 				</div>
		 				<div v-else class="short_info">
		 					<h3 v-if="settings.show_brand_title">{{ brand.name }}</h3> 
		 				</div>
		 			</a>
	 			</li> 
				</transition-group>
	 	</ul>
		<div v-else-if="!processing && !brands.length" class="row small-gutters">
		 	<div class="col text-center">
		 		<p>No brands found</p>
		 		<a class="btn_1" href="/" role="button">Go to homepage</a>
		 	</div>
		</div>
		<div v-if="meta && meta.pagination && meta.pagination.total_pages > 1" class="pagination__wrapper">
	        <pagination :pagination="meta.pagination" for="brands"></pagination>
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
		        brands: [],
		        meta: null,
		        processing: true
			}
		},
		watch: {
			'$route.query' (query) {
				this.getBrands(query.page);
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
                    this.processing = false;
                })
			},
			switchPage(page = this.route.query.page) {
				this.$router.replace({
					name: 'brands.index',
					query: {
						page
					}
				})
			}
		},
		mounted() {

			this.getBrands(1);

			bus.$on('brands.switched-page', this.switchPage);
			
		}
	}
</script>