<template>
	<div class="row">
        <label class="col-xl-5 col-lg-5  col-md-6 col-6"><strong>Quantity</strong></label>
        <div class="col-xl-4 col-lg-5 col-md-6 col-6">
            <div class="numbers-row">
                <input type="text" id="quantity_1" class="qty2" min="1" v-model.number="qty" v-on:keypress="isNumber($event)">
            	<div class="inc button_inc" @click="increment">+</div>
            	<div class="dec button_inc" @click="decrement">-</div>
            </div>
        </div>
    </div>
</template>

<script>
	import bus from '../../assets/js/bus'

	export default {
		props: {
			product: {
				type: Object,
				required: true
			}
		},
		data () {
			return {
				qty: 1,
				stock: 0,
			}
		},
	 	watch: {
	 		 qty: function (val) {
 		 	 	if(!this.product.preorder && !this.product.backorder) {
		            if(val > this.stock) {
		              	this.qty = this.stock;
		            } 
		        }

		        if(val === '' || val === 0 || val < 0) {
			          this.qty = 1;
		        } 

		        bus.$emit('qty.changed', this.qty);
	 		 }
	 	},
	 	methods: {
	 		 increment() {
	            if(!this.product.preorder && !this.product.backorder) {
	                if(this.qty < this.stock) {
	                  this.qty++;
	                  return;
	                }
	            }
	            this.qty++;
	        },
	        decrement() {
	            if(this.qty === 1) {
	              this.qty = 1;
	            } else {
	              this.qty--;
	            }
	        },
	        isNumber: function(evt) {
	            evt = (evt) ? evt : window.event;
	            var charCode = (evt.which) ? evt.which : evt.keyCode;
	            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
	              evt.preventDefault();;
	            } else {
	              return true;
	            }
	        },
	 	},
	 	mounted () {
	 		this.stock = this.product.stock;

	 		bus.$on('variation.refresh', (variation) => {
	            this.stock = variation.stock;
	            this.qty = 1;
	        });
	 	}
	}
</script>