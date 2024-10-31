<template>
<div v-if="cart.total_price" class="box_cart">
	<div class="container">
		<div class="row justify-content-end">
			<div class="col-xl-4 col-lg-4 col-md-6">
				<ul>
					<li>
						<span>Subtotal</span> {{ subtotal }}
					</li>
				</ul>
				<a :href="`cart/${this.cart.id}/reserve`" class="btn_1 full-width cart" :class="{'disabled-control': !cart.inventory_check}">Proceed to Checkout</a>
			</div>
		</div>
	</div>
</div>
</template>

<script>
	import bus from '../assets/js/bus'
	import settings from '../assets/js/settings.js'

	export default {
		mixins: [settings],
		data () {
			return {
				total: 0
			}
		},
		computed: {
            subtotal: {
                set: function(amount) {
                  this.total = this.formatMoney(amount);
                },
                get: function() {
                  return this.total;
                }
            },
        },
        updated () {
            this.subtotal = this.cart.total_price;
        },
        mounted () {
            bus.$on('currency.switched', () => {
                this.subtotal = this.cart.total_price;
            });
        }
	}
</script>