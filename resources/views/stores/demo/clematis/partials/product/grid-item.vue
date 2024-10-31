<template>
	<div class="grid_item">
		<figure>
			<span v-if="!product.in_stock && !product.backorder" class="ribbon sold">{{ product.outofstock_label ? product.outofstock_label : 'Sold out' }}</span>
			<span v-else-if="product.special_price > 0 && discount" class="ribbon off">-{{ discount }}%</span>
			<span v-else-if="isNew" class="ribbon new">New</span>
			<a :href="product.url">
				<img v-if="product.cover_image" class="img-fluid lazy" :src="product.cover_image.medium" :data-src="product.cover_image.medium" :alt="product.name">
				<img v-else class="img-fluid lazy" :src="`${shopbox.store.assetsPath}product_placeholder_square_medium.jpg`" :data-src="`${shopbox.store.assetsPath}product_placeholder_square_medium.jpg`" :alt="product.name">
				<img v-if="product.images.length" class="img-fluid lazy" :src="product['images'][0]['medium']" :data-src="product['images'][0]['medium']" :alt="product.name">
				<img v-else-if="product.cover_image" class="img-fluid lazy" :src="product.cover_image.medium" :data-src="product.cover_image.medium" :alt="product.name">
				<img v-else class="img-fluid lazy" :src="`${shopbox.store.assetsPath}product_placeholder_square_medium.jpg`" :data-src="`${shopbox.store.assetsPath}product_placeholder_square_medium.jpg`" :alt="product.name">
			</a>
			<div v-if="showTimer" :data-countdown="discountDuration" class="countdown"></div>
		</figure>
		<a :href="product.url">
			<h3>{{ product.name }}</h3>
		</a>
		<div v-if="discountPrice" class="price_box">
			<span class="new_price">{{ formatMoney(discountPrice) }}</span>
			<span class="old_price">{{ price }}</span>
		</div>
		<div class="price_box" v-else>
			<span class="new_price">{{ price }}</span>
		</div>
		<product-action :product="product" :authenticated="authenticated" :wishlists="wishlists"></product-action>
	</div>
</template>

<script>
	var moment = require('moment');
	import bus from '../../assets/js/bus.js'
	import settings from '../../assets/js/settings.js'

	export default {
		props: {
			product: {
				type: Object,
				required: true
			},
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
	    data () {
	    	return {
	    		regular_price: 0,
				discount_price: 0,
	    	}
	    },
	    computed: {
	    	discount () {
				if(this.product.special_price > 0) {
					return Math.floor((this.product.price_min - this.product.special_price) / this.product.price_min * 100);
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
		    showTimer () {
		    	let now = moment().format('YYYY-MM-DD');

		    	if(this.product.special_price > 0 && this.product.special_active_from && this.product.special_active_to && !moment(now).isAfter(moment(this.product.special_active_to).format('YYYY-MM-DD'))) {
			    	let active_from = moment(this.product.special_active_from).subtract(1, 'd').format('YYYY-MM-DD HH:mm:ss');
			    	let active_to = moment(this.product.special_active_to).add(1, 'd').format('YYYY-MM-DD HH:mm:ss');
			    	if (moment().isBetween(active_from, active_to)) {
			    		return true;
			    	}
		    	}
		    	
		    	return false;
		    },
		    discountDuration () {
		    	if (this.showTimer) {
		    		return moment(this.product.special_active_to).format('YYYY/MM/DD HH:mm:ss');
		    	}
		    },
		    isNew () {
		    	let now = moment().format('YYYY-MM-DD');
		    	let created = moment(this.product.created).add(this.settings.new_tag_days, 'd').format('YYYY-MM-DD');
		    	return moment(now).isSameOrBefore(created);
		    }
	    },
	    updated () {
			this.initCountdown();
	    },
	    mounted () {
	    	this.price = this.product.price_min;
			this.discountPrice = this.product.special_price;

	      	bus.$on('currency.switched', () => {
		        this.price = this.product.price_min;
	      	});
	    }
	}
</script>