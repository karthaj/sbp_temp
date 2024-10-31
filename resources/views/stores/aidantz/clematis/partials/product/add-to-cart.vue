<template>
	<div class="btn_add_to_cart">
		<button v-if="product.preorder || product.backorder || product.in_stock" type="button" class="btn_1" :class="{'button-disabled': disabled}" @click.prevent="submit" :disabled="disabled">{{ label }}</button>
		<button v-else type="button" class="btn_1 button-disabled" disabled>{{ product.outofstock_label ? product.outofstock_label : 'Sold out' }}</button>
	</div>
</template>

<script>
	import bus from '../../assets/js/bus'
	import settings from '../../assets/js/settings'

	export default {
		props: {
			product: {
				type: Object,
				required: true
			}
		},
		mixins: [settings],
	 	data () {
	        return {
	            disabled: true,
	            label: 'add to cart',
	            qty: 1,
	            product_id: '',
	            attribute_id: '',
	            stock: 0,
	            availability: '',
	        }
	    },
	    methods: {
	    	submit () {
	            this.disabled = true;
	            this.label = 'adding'

	            axios.post('/cart/add', {
	                product_id: this.product_id,
	                attribute_id: this.attribute_id,
	                qty: this.qty
	            }).then((response) => { 
	                this.getCart();
	                this.disabled = false;
	                this.label = 'added';
	                setTimeout(() => {
	                    this.label = 'add to cart'; 
	                }, 1000);
	            }).catch((error) => {

	                this.disabled = false;
	                this.label = 'add to cart';

	                if(error.response.data.product_id.length) {
	                    this.$snotify.warning(error.response.data.product_id[0]);
	                } else if(error.response.data.attribute_id.length) {
	                    this.$snotify.warning(error.response.data.attribute_id[0]);
	                } else if(error.response.data.qty.length) {
	                    this.$snotify.warning(error.response.data.qty[0]);
	                }
	            })
	        }
	    },
	    mounted () {

	        if(this.product.type !== 'variant') {

	            if(this.product.preorder || this.product.backorder) {
	                this.disabled = false;
	            } else if(this.product.in_stock) {
	                this.disabled = false;
	            }
	            
	        }

	        this.stock = this.product.stock;
	        this.product_id = this.product.id;

	        if(this.product.preorder) {
	            this.label = 'Pre order';
	        }
	        
	        bus.$on('variation.refresh', (variation) => {

	            if(!this.product.preorder && !this.product.backorder) {
	                this.disabled = !Boolean(variation.stock);
	            } else {
	                this.disabled = false;
	            }
	           
	            this.stock = variation.stock;
	            this.attribute_id = variation.id;
	            this.qty = 1;

	        });

	        bus.$on('selection.cleared', () => {
	            this.disabled = true;
	        });

	        bus.$on('qty.changed', (qty) => {
	            this.qty = qty;
	        });
	    }
	}
</script>