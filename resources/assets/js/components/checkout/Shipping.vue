<template>

<div class="tab-pane slide-left sm-no-padding" :class="{'active': active}" id="shippingTab">
    <div class="row row-same-height">
        <div class="col-md-12">
            <h4 class="semi-bold">Shipping</h4> 
            <ul class="list-group">
                <li v-if="checkout.cart" class="list-group-item">
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
                            <address>
                                {{ checkout.shipping_address.address1 }},
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
            </ul>
            <h5 class="semi-bold">Shipping method</h5> 
            <ul v-if="!loading" class="list-group">
                <template v-if="shipping_quotes.length">
                    <li class="list-group-item"
                      v-for="quote in shipping_quotes" :key="quote.id" >
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="radio radio-info">
                                <input type="radio" :id="'checkout_shipping_'+quote.id" :value="quote.id" v-model="shipping_id" @change="update">
                                <label :for="'checkout_shipping_'+quote.id">{{ quote.name }}</label>
                                <button v-if="quote.id === 0 && quote.instructions" class="btn btn-link" type="button" data-toggle="collapse" data-target="#Intructions" aria-expanded="true" aria-controls="Intructions">
                                    <small><em>Instructions</em></small>
                                </button>
                            </div>
                            <span>{{ formatMoney(quote.rate) }}</span>
                        </div>
                        
                        <div v-if="quote.id === 0 && quote.instructions" id="Intructions" class="collapse" v-html="quote.instructions"></div>
                    </li>
                </template>
                
                <li v-else class="list-group-item d-flex justify-content-center">
                    <span>We're sorry! We do not deliver to your area just yet. Sorry for the inconvenience caused.
                    </span>
                </li>
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
            <div class="form-group mt-3">
                <label for="order_comments">Order comments</label>
                <textarea id="order_comments" cols="10" rows="3" class="form-control" v-model="note"></textarea>
            </div>
        </div> 
    </div>
    <div class="row mt-5 justify-content-between">
      <div class="col-md-4 col-12 order-2 order-md-1 text-center text-sm-left">
        <a href="/cart">Return to cart</a>
      </div>
      <div class="col-md-4 col-12 order-1 order-md-2 mb-4">
        <button class="btn btn-info btn-cons btn-block" type="button" :disabled="disabled" @click.prevent="save()">
            <span>Continue to payment</span>
        </button>
      </div>
    </div>
</div>

</template>

<script>

import { mapActions, mapGetters, mapMutations } from 'vuex'
import currency from '../../currency'
import Loading from 'vue-loading-overlay';

export default {
    data () {
        return {
            loading: true,
            disabled: true,
            shipping_id: '',
            note: '',
            shipping_quotes: []
        }
    },
    components: {
        Loading
    },
    mixins:[currency],
    computed: {

      ...mapGetters({

        checkout: 'checkout',
        checkout_id: 'checkout_id',
        checkout_session: 'checkout_session'

      }),
      active () {

        if(this.checkout_session && this.checkout_session['checkout-shipping-step'].step_is_complete === false && this.checkout_session['checkout-shipping-step'].step_is_reachable === true) {
          return true;
        }

        return false;
      }

    },
    methods: {
        ...mapActions({

          getCheckout: 'getCheckout'

        }),
        ...mapMutations({

            setCheckout: 'setCheckout',

        }),
        switchTab (value) {
          $('a[href="'+value+'"]').tab('show')
        },
        update () {
            this.loading = true;
            this.disabled = true;
            axios.put(`/checkout/${this.checkout_id}/shipping`, {
                shipping_id: this.shipping_id
            }).then((response) => { 
                this.loading = false;
                this.disabled = false;
                this.setCheckout(response.data.data);
            }).catch((error) => {
                if(error.response.data.status === 'expired') {
                    window.location.href = error.response.data.return_url;
                }
                this.loading = false;
                this.disabled = false;
            })
        },
        save () {
            this.disabled = true;
            axios.post(`/checkout/${this.checkout_id}/shipping`, {
                note:this.note
            }).then((response) => { 
                this.disabled = false;
                this.getCheckout();
                $('a[href="#paymentTab"]').tab('show')
            }).catch((error) => {
                if(error.response.data.status === 'expired') {
                    window.location.href = error.response.data.return_url;
                }
                this.disabled = false;
            })
        },
        getShippingQuotes () {
            axios.get(`/checkout/${this.checkout_id}/shipping_quotes.json`).then((response) => {
                if(response.data.length) {
                    this.disabled = false;
                    this.shipping_quotes = response.data;
                    this.getCheckout();
                }
                this.loading = false;
            }).catch((error) => {
                this.disabled = false;
            })
        }
    },
    updated() {

        if(this.checkout.consignment) {
            this.shipping_id = this.checkout.consignment.id;
        }

        if(this.checkout.note) {
            this.note = this.checkout.note;
        }
       
    },
    mounted() {
        var vm = this;
        
        $('a[href="#shippingTab"]').on('shown.bs.tab', function (e) {
            vm.getShippingQuotes();
        })

        if(this.checkout.cart && this.checkout.cart.need_shipping) {
            this.getShippingQuotes();
        }
        
    }
}
</script>