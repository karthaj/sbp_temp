<template>
    
<div class="tab-pane slide-left sm-no-padding" :class="{'active': active}" id="paymentTab">
    <div class="row row-same-height">
        <div class="col-md-12">
            <h4 class="semi-bold">Payment</h4> 
            <ul class="list-group" v-if="checkout.cart">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-12 col-md-2">Contact:</div>
                        <div class="col-9 col-md-8">{{ checkout.cart.email }}</div>
                        <div class="col-3 col-md-2">
                            <a href="#" @click.prevent="switchTab('#customerTab')">Change</a>
                        </div>
                    </div>
                </li>
                <li v-if="checkout.cart.need_shipping && checkout.shipping_address" class="list-group-item">
                    <div class="row">
                        <div class="col-12 col-md-2">Ship to:</div>
                        <div class="col-9 col-md-8">
                            <address>{{ checkout.shipping_address.address1 }},
                                <template v-if="checkout.shipping_address.address2">
                                    {{ checkout.shipping_address.address2 }},
                                </template>
                                {{  checkout.shipping_address.city }},
                                <template v-if="checkout.shipping_address.state">
                                    {{  checkout.shipping_address.state }},
                                </template>
                                <template v-if="checkout.shipping_address.postcode">
                                    {{ checkout.shipping_address.postcode }},
                                </template>
                                {{ checkout.shipping_address.country }}
                            </address>
                        </div>
                        <div class="col-3 col-md-2">
                            <a href="#" @click.prevent="switchTab('#addressTab')">Change</a>
                        </div>
                    </div>
                </li>
                <li v-if="checkout.cart.need_shipping && checkout.consignment" class="list-group-item">
                    <div class="row">
                        <div class="col-12 col-md-2">Method:</div>
                        <div class="col-9 col-md-8">{{ checkout.consignment.name }} : 
                            <strong>{{ formatMoney(checkout.consignment.rate) }}</strong>
                        </div>
                          <div class="col-3 col-md-2">
                            <a href="#" @click.prevent="switchTab('#shippingTab')">Change</a>
                          </div>
                    </div>
                </li>
            </ul>

            <h5 class="semi-bold">Payment method</h5> 
            <div v-if="checkout.customer && checkout.customer.store_credits > 0" class="form-group">
                <div class="checkbox check-info">
                    <input type="checkbox" id="store_credit" v-model="apply_store_credit" @change="storeCredit()">
                    <label for="store_credit">Redeem store credit (Available: {{ formatMoney(checkout.customer.store_credits) }})</label>
                </div>
            </div>
            <ul v-if="!loading" class="list-group">
                <template v-if="paymentRequired && payments.length">
                    <li class="list-group-item d-flex justify-content-between align-items-center"  
                      v-for="payment, index in payments" :key="payment.id">
                        <div class="radio radio-info">
                            <input type="radio" :id="'checkout_payment_'+payment.id" :value="payment.alias" v-model="payment_method" @change="update(payment)" :checked="index === 0">
                            <label :for="'checkout_payment_'+payment.id">
                                <span>{{ payment.config.name }}</span>
                            </label>
                        </div>
                        <img v-if="payment.logo" class="mr-2" :src="payment.logo" :alt="payment.alias" height="25">
                    </li>
                </template>
                <li v-else-if="!payments.length" class="list-group-item d-flex justify-content-center"><span>No payment options found.</span></li>
            </ul>
            <ul v-else class="list-group vld-parent">
                <li class="list-group-item d-flex justify-content-center">
                    <loading :active.sync="loading" 
                    :is-full-page="false"
                    loader="dots"
                    :height="40"
                    :width="40"
                    ></loading>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="row mt-5 justify-content-between">
      <div class="col-md-4 col-12 order-2 order-md-1 text-center text-sm-left">
        <a href="/cart">Return to cart</a>
      </div>
      <div class="col-md-4 col-12 order-1 order-md-2 mb-4">
        <button class="btn btn-info btn-cons btn-block" type="button" :disabled="disabled" @click.prevent="store()">
            <span>{{ label }}</span>
        </button>
      </div>
    </div>
</div>

</template>

<script>

    import { mapActions, mapGetters, mapMutations } from 'vuex'
    import Loading from 'vue-loading-overlay';
    import currency from '../../currency'
    
    export default {
        components: {
            Loading
        },
        mixins:[currency],
        data () {
            return {
                disabled: true,
                loading: true,
                label: 'Place order',
                payment_method: '',
                apply_store_credit: false,
                ignore: false,
                payments: []
            }
        },
        computed: {
          ...mapGetters({

            checkout: 'checkout',
            checkout_id: 'checkout_id',
            checkout_session: 'checkout_session',
            store_credits: 'store_credits'

          }),
          paymentRequired () {
            if(this.checkout.grand_total - this.store_credits === 0 || this.checkout.grand_total === 0) {
                this.label = 'Place order';
                return false;
            }
            return true;
          },
          active () {

            if(this.checkout_session && this.checkout_session['checkout-payment-step'].step_is_complete === false && this.checkout_session['checkout-payment-step'].step_is_reachable === true) {
              return true;
            }

            return false;
          }
        },
        methods: {
            ...mapMutations({

                setStoreCredits: 'setStoreCredits'

            }),
            switchTab (value) {
              $('a[href="'+value+'"]').tab('show');
            },
            update (payment) {
                
                if(payment.type === 'api') {
                    this.label = 'Pay now';
                } else if(payment.type === 'offline') {
                    this.label = 'Place order';
                }

            },
            getPayments () {
                axios.get('/checkout/payments').then((response) => {
                    this.payments = response.data;
                    if(this.payments.length) {
                        this.payment_method = this.payments[0].alias;
                        this.update(this.payments[0]);
                    }
                    this.loading = false;
                });
            },
            storeCredit () {
               
                if(this.apply_store_credit) {
                    if(this.checkout.grand_total > this.checkout.customer.store_credits) {
                        this.setStoreCredits(this.checkout.customer.store_credits);
                    } else {
                        this.setStoreCredits(this.checkout.grand_total);
                    }
                } else {
                    this.setStoreCredits(0);
                }
            },
            store () {
                this.ignore = true;
                this.disabled = true;
                var form = {
                    store_credits: this.store_credits
                };

                if(this.paymentRequired) {
                    form['payment_method'] = this.payment_method
                }


                axios.post(`/checkout/${this.checkout_id}/place-order`, form).then((response) => { 
                    window.location.href = response.data.url;
                }).catch((error) => {
                    if(error.response.data.status === 'expired') {
                        window.location.href = error.response.data.return_url;
                    }
                    this.disabled = false;
                })
            }
        },
        updated() {

            if(!this.ignore) {
                if(this.paymentRequired && this.payments && this.payments.length) {
                    this.disabled = false;
                } else if(this.paymentRequired === false) {
                    this.disabled = false;
                }
            }
            
        },
        mounted() {
            var vm = this;

            $('a[href="#paymentTab"]').on('shown.bs.tab', function (e) {
                vm.getPayments();
            });

            this.getPayments();

        }
    }

</script>