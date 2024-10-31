<template>
	<div class="container margin_30">
        <div class="row">
	        <div class="col-md-6" :class="{'magnific-gallery': settings.product_layout_style == 'sticky'}">
	        	<image-slider v-if="settings.product_layout_style == 'default'" :coverImage="product.cover_image" :images="product.images"></image-slider>
	        	<template v-else-if="settings.product_layout_style == 'sticky'">
	        		<p v-if="product.cover_image">
	                    <a :href="product.cover_image.large" :title="product.name" data-effect="mfp-zoom-in">
	                    	<img :src="product.cover_image.large" :alt="product.name" class="img-fluid">
	                    </a>
	                </p>
					<p v-else>
	                    <a :href="defaultImg" :title="product.name" data-effect="mfp-zoom-in">
	                    	<img :src="defaultImg" :alt="product.name" class="img-fluid">
	                    </a>
	                </p>
	                <p v-if="product.images.length" v-for="image in product.images">
	                    <a :href="image.large" :title="product.name" data-effect="mfp-zoom-in">
	                    	<img :src="image.large" :alt="product.name" class="img-fluid">
	                    </a>
	                </p>
	        	</template>
	        </div>
	        <div class="col-md-6" id="sidebar_fixed">
	        	<div class="theiaStickySidebar">
		        	<div class="breadcrumbs">
	                    <ul>
	                        <li><a href="/">Home</a></li>
	                        <li>{{ product.name }}</li>
	                    </ul>
	                </div>
	                <div class="prod_info">
	                    <h1>{{ product.name }}</h1>
	                    <p v-if="sku"><small>SKU: {{ sku }}</small></p>
	                    <p v-html="product.short_description"></p>
	                    <div class="prod_options">
	                    	<product-options v-if="product.type === 'variant'" :option-selectors="product.options" :variants="product.variants" :preorder="product.preorder" :backorder="product.backorder"></product-options>
	                    	<qty :product="product"></qty>
	                    </div>
	                    <div class="row">
	                        <div class="col-lg-5 col-md-6">
	                            <div v-if="discountPrice" class="price_main">
	                            	<span class="new_price">{{ formatMoney(discountPrice) }}</span>
	                            	<span class="percentage">-{{ discount }}%</span> 
	                            	<span class="old_price">{{ price }}</span>
	                            </div>
	                            <div v-else class="price_main">
	                            	<span class="new_price">{{ price }}</span>
	                            </div>
	                        </div>
	                        <div class="col-lg-4 col-md-6">
	                        	<add-to-cart :product="product"></add-to-cart>
	                        </div>
	                    </div>
	                    <div class="product_actions">
		                    <ul>
		                        <li>
		                        	<add-to-wishlist :product_id="product.id" :authenticated="authenticated" :wishlists="wishlists"></add-to-wishlist>
		                        </li>
		                    </ul>
		                </div>
	                </div>
	            </div>
	        </div>
    	</div>
	</div>
</template>

<script>
	
	import bus from '../assets/js/bus'
	import settings from  '../assets/js/settings.js'
	import ImageSlider from '../partials/product/image-slider.vue'
	import AddToCart from '../partials/product/add-to-cart.vue'
	import AddToWishlist from '../partials/product/add-to-wishlist.vue'
	import Qty from '../partials/product/qty.vue'
	import ProductOptions from '../partials/product/options.vue'

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
		mixins: [settings],
		components: {
			ImageSlider,
			AddToCart,
			Qty,
			ProductOptions,
			AddToWishlist
		},
		data () {
	    	return {
	    		product: {},
	    		item_code: '',
    		 	regular_price: 0,
	            discount_price: 0,
	            special_price: 0,
	            original_price: 0,
	    	}
	    },
	    computed : {
	    	sku : {
		        get: function () {
		         return this.item_code;
		        },
		        set: function (value) {
		          this.item_code = value;
		        }
		        
	      	},
	    	discount () {
				if(this.special_price > 0) {
					return Math.floor((this.original_price - this.special_price) / this.original_price * 100);
				}

				return;
			},
			price: {
		        set: function(amount) {
		          this.regular_price = this.formatMoney(amount);
		        },
		        get: function() {
		          return this.regular_price;
		        }
		    },
		    discountPrice: {
		        set: function(amount) {
		        	if(amount > 0) {
		        		this.discount_price = amount;
		        	} else {
						this.discount_price = 0;
					}
		        },
		        get: function() {
		          return this.discount_price;
		        }
		    },
	    },
	    methods: {
	    	initStickyBar () {
	    		$('#sidebar_fixed').theiaStickySidebar({
					minWidth: 991,
					updateSidebarHeight: false,
					additionalMarginTop: 90
				});
			},
			loadDefaultPrice () {
				this.price = this.product.price_min;
				this.original_price = this.product.price_min;
				this.discountPrice = this.product.special_price;
				this.special_price = this.product.special_price;
			}
	    },
		created () {
		  	this.product = JSON.parse(document.getElementById('productJson').innerHTML).data;
		   	this.sku = this.product.sku;
			this.loadDefaultPrice()
	    },
	    mounted () {

	    	bus.$on('currency.switched', () => {
	            this.price = this.original_price;
	            this.original_price = this.original_price;
	            this.discountPrice = this.special_price;
	            this.special_price = this.special_price;
	        });
	        
	        bus.$on('variation.refresh', (variation) => {
				this.sku = variation.sku;
				this.price = variation.price;
				this.discountPrice = variation.special_price;
				this.special_price = variation.special_price;
				
	        	if($("[data-variation]").length) {
	        		$("[data-variation]").remove();
	        	}
		        if(variation.image) {
		        	$(".magnific-gallery").prepend(`<p data-variation>
	                    <a href="${variation.image.large}" :title="product.name" data-effect="mfp-zoom-in">
	                    	<img src="${variation.image.large}" alt="${variation.sku}" class="img-fluid">
	                    </a>
	                </p>`);
		        }
	      	});

	    }
	}

</script>