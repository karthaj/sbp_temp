<template>
	
<ul v-if="checkout.cart" class="list-group d-md-block d-lg-none">
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <span>
       <i class="fa fa-shopping-cart fa-2x pr-3"></i>
       <a href="#" class="order-summary-toggle" data-toggle="collapse" data-target="#orderSummary" aria-expanded="false" aria-controls="orderSummary">Order summary</a> 
    </span>
    <span class="font-weight-bold">{{ total }}</span>
  </li>
</ul>

</template>

<script>

import { mapGetters } from 'vuex'
import currency from '../../currency'

export default {
   mixins: [currency],
   computed: {
    ...mapGetters({

      checkout: 'checkout',
      store_credits: 'store_credits'

    }),
    total () {
      return this.formatMoney(this.checkout.grand_total - this.store_credits)
    }
  }
}

</script>