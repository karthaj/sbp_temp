<template>
	
	<table v-if="!loading && cart.items && cart.items.length" class="table table-striped cart-list">
		<thead>
			<tr>
				<th>
					Product
				</th>
				<th>
					Price
				</th>
				<th>
					Quantity
				</th>
				<th>
					Subtotal
				</th>
				<th>
					
				</th>
			</tr>
		</thead>
		<tbody>
			<product v-for="item in cart.items" :key="item.id" :product="item" :stockReserved="cart.stock_reserved"></product>
		</tbody>
	</table>
	<div v-else-if="!loading && !cart.items.length" class="col-12 text-center">
		<h5>Your cart is currently empty.</h5>
	</div>
	<div v-else class="col-12 vld-parent text-center">
		<loading :active.sync="loading" :is-full-page="false"></loading>
	</div>
</template>

<script>
	import Loading from 'vue-loading-overlay';
	import settings from '../assets/js/settings.js'
	import product from '../partials/cart/item'

	export default {
		props: ['endpoint'],
		mixins: [settings],
		components: {
			product,
			Loading
		}
	}

</script>