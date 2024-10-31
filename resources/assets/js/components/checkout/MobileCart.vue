<template>
	
<div v-if="checkout.cart" id="orderSummary" class="card card-default d-lg-none collapse">
    <div class="card-block">
        <div class="scrollable">
            <div class="product-card-scrollable">
                <table class="product-table">
                  <thead>
                      <th></th>
                      <th></th>
                      <th></th>
                  </thead>
                  <tbody>
                      <tr v-for="item in checkout.cart.items" :key="item.id" class="product">
                          <td>
                            <div class="product-thumbnail">
                              <img :src="item.image" :alt="item.name" class="img-fluid rounded">
                            </div>
                          </td>
                          <td class="product__title">
                              <span>{{ item.name }}</span>
                              <small>{{ item.quantity }} x {{ item.sale_price ? formatMoney(item.sale_price) : formatMoney(item.selling_price) }}</small>
                          </td>
                          <td class="product__price">
                              <span>{{ formatMoney(item.line_price) }}</span>
                          </td>
                      </tr>
                  </tbody>
              </table>
            </div>
        </div>
    </div>
    <div class="col-md-12 py-2 top-line text-center" v-if="checkout.cart.total_discount != 0"></div>
    <div class="col-md-12 py-4 line" v-if="checkout.cart.total_discount == 0">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <input type="text" class="form-control input-sm mb-4" placeholder="COUPON CODE HERE"
                v-model="discount_code">
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-info btn-sm btn-block" :disabled="disabled" @click.prevent="applyDiscountCode()">Apply</button>
            </div>
        </div>
        <div v-if="error" class="alert alert-warning mt-3" role="alert">
          <i class="aapl-notification-circle"></i>
          {{ error }}
        </div>
    </div>
    
    <div class="col-md-12 pt-3">
         <table class="total-line-table">
        <thead>
          <tr>
            <th></th>
            <th></th>
          </tr>
        </thead>
          <tbody class="total-line-table__tbody">
            <tr class="total-line total-line--subtotal">
              <th class="total-line__name" scope="row">Subtotal</th>
              <td class="total-line__price">
                <span class="order-summary__emphasis">{{ formatMoney(checkout.subtotal) }}</span>
              </td>
            </tr>
            <tr class="total-line total-line--discount" v-if="checkout.cart.total_discount != 0" v-for="discount in checkout.cart.discounts">
              <th class="total-line__name" scope="row">Discount <span>[{{ discount.name }}] </span><a href="#" @click.prevent="removeDiscount(discount.id)"> <i class="fa fa-close"></i></a></th>
              <td class="total-line__price">
                <span>
                  -{{ formatMoney(discount.amount) }}
                </span>
              </td>
            </tr>
            <tr v-if="checkout.cart.need_shipping" class="total-line total-line--shipping">
              <th class="total-line__name" scope="row">Shipping</th>
              <td class="total-line__price">
                <span v-if="checkout.consignment.rate > 0">
                    {{ formatMoney(checkout.consignment.rate) }}          
                </span>
                <span v-else>{{ checkout.consignment.name }}</span>
              </td>
            </tr>
            <tr class="total-line total-line--taxes">
              <th class="total-line__name" scope="row">{{ checkout.tax.name }}</th>
              <td class="total-line__price">
                <span>{{ formatMoney(checkout.tax.amount) }}</span>
              </td>
            </tr>
            <tr v-if="checkout.surcharge" class="total-line">
              <th class="total-line__name" scope="row">Surcharge</th>
              <td class="total-line__price">
                <span>{{ formatMoney(checkout.surcharge) }}</span>
              </td>
            </tr>
            <tr v-if="store_credits" class="total-line">
              <th class="total-line__name" scope="row">Store Credit</th>
              <td class="total-line__price">
                <span>-{{ formatMoney(store_credits) }}</span>
              </td>
            </tr>
          </tbody>
          <tfoot class="total-line-table__footer">
            <tr class="total-line">
              <th class="total-line__name payment-due-label" scope="row">
                <span class="payment-due-label__total">Total</span>
              </th>
              <td class="total-line__price payment-due">
                <span class="payment-due__price">{{ grandTotal }}</span>
              </td>
            </tr>
            <tr v-if="currencyDiffers">
              <th scope="row">
                <span>Estimated Amount</span>
              </th>
              <td>
                <span>{{ formatMoney(convert(checkout.grand_total - store_credits), currency) }}</span>
              </td>
            </tr>
          </tfoot>
      </table>
    </div>
</div>

</template>

<script>

  import { mapActions, mapGetters, mapMutations } from 'vuex'
  import currency from '../../currency'

	export default {
    mixins: [currency],
    data () {
        return {
          disabled: false,
          error: '',
          discount_code: ''
        }
    },
    computed: {
      ...mapGetters({

        checkout_id: 'checkout_id',
        checkout: 'checkout',
        store_credits: 'store_credits'

      }),
      currency () {
        return localStorage.userCurrency;
      },
      grandTotal () {
        return this.formatMoney(this.checkout.grand_total - this.store_credits)
      }
    },
    methods: {
      ...mapActions({

        getCheckout: 'getCheckout'

      }),
       ...mapMutations({

        setCheckout: 'setCheckout'

      }),
      applyDiscountCode () {

        this.disabled = true;

        axios.post(`/checkout/${this.checkout_id}/discounts`, {
          discount_code: this.discount_code
        }).then((response) => { 
          this.error = '';
          this.disabled = false;
          this.getCheckout();
        }).catch((error) => {

          if(error.response.data.status === 'expired') {
              window.location.href = error.response.data.return_url;
          }

          if(error.response) {
            this.error = error.response.data.data.message;
          }
          
          this.disabled = false;
        })
      },
      removeDiscount (discount) {

        if(!discount) {
          return;
        }

        axios.delete(`/checkout/${this.checkout_id}/discounts/${discount}`).then((response) => { 
           this.setCheckout(response.data.data);
        }).catch((error) => {
          if(error.response.data.status === 'expired') {
            window.location.href = error.response.data.return_url;
          }
          console.log(error);
        })
      }
    }
  }
</script>