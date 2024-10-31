<template>
	
<div v-if="checkout.cart" id="rootwizard">
  <div class="d-none d-sm-block">
  <ul class="nav nav-tabs checkout-steps nav-tabs-linetriangle nav-tabs-separator nav-stack-sm" role="tablist">
      <li class="nav-item">
        <a v-if="!checkout_session['checkout-customer-step'].step_is_reachable" href="#customerTab" :class="{'active': checkout_session['checkout-customer-step'].step_is_complete && !checkout_session['checkout-customer-step'].step_is_reachable}" @click.prevent><i class="aapl-user tab-icon"></i> <span>Customer</span></a>
        <a v-else href="#customerTab" :class="{'active': checkout_session['checkout-customer-step'].step_is_complete && !checkout_session['checkout-customer-step'].step_is_reachable}" data-toggle="tab"><i class="aapl-user tab-icon"></i> <span>Customer</span></a>
      </li>
      <li class="nav-item">
        <a v-if="!checkout_session['checkout-addresses-step'].step_is_reachable" :class="{'active': checkout_session['checkout-addresses-step'].step_is_complete && !checkout_session['checkout-addresses-step'].step_is_reachable}" href="#addressTab" @click.prevent><i class="aapl-map-marker-user tab-icon"></i> <span>Address</span></a>
        <a v-else :class="{'active': !checkout_session['checkout-addresses-step'].step_is_complete && checkout_session['checkout-addresses-step'].step_is_reachable}" href="#addressTab" data-toggle="tab"><i class="aapl-map-marker-user tab-icon"></i> <span>Address</span></a>
      </li>
      <li v-if="checkout.cart && checkout.cart.need_shipping" class="nav-item">
        <a v-if="!checkout_session['checkout-shipping-step'].step_is_reachable" :class="{'active': checkout_session['checkout-shipping-step'].step_is_complete && !checkout_session['checkout-shipping-step'].step_is_reachable}" href="#shippingTab" @click.prevent><i class="aapl-truck tab-icon"></i> <span>Shipping</span></a>
        <a v-else :class="{'active': !checkout_session['checkout-shipping-step'].step_is_complete && checkout_session['checkout-shipping-step'].step_is_reachable}" href="#shippingTab" data-toggle="tab"><i class="aapl-truck tab-icon"></i> <span>Shipping</span></a>
      </li>
      <li class="nav-item">
        <a v-if="!checkout_session['checkout-payment-step'].step_is_reachable" :class="{'active': checkout_session['checkout-payment-step'].step_is_complete && !checkout_session['checkout-payment-step'].step_is_reachable}" href="#paymentTab" @click.prevent><i class="aapl-credit-card tab-icon"></i> <span>Payment</span></a>
        <a v-else :class="{'active': !checkout_session['checkout-payment-step'].step_is_complete && checkout_session['checkout-payment-step'].step_is_reachable}" href="#paymentTab" data-toggle="tab"><i class="aapl-credit-card tab-icon"></i> <span>Payment</span></a>
      </li>
  </ul>
  </div>

  <div v-if="checkout.cart && checkout.cart.requires_splitting" class="tab-content p-3" style="background-color: #f2f2f2;">

    <div v-if="checkout.reservation_time && checkout.reservation_time_left" class="alert alert-warning" role="alert">
      <strong v-if="timer">Your order is reserved for {{ timer }} minutes.</strong>
      <strong v-else-if="timer === 0">Order reservation ended.</strong>
    </div>

    <warning/>

  </div>
              
  <div v-else-if="checkout.cart" class="tab-content p-3" style="background-color: #f2f2f2;">

    <div v-if="checkout.reservation_time && checkout.reservation_time_left && timer" class="alert alert-warning" role="alert">
      <strong v-if="timer">Your order is reserved for {{ timer }} minutes.</strong>
      <strong v-else-if="timer === 0">Order reservation ended.</strong>
    </div>

    <customer/>

    <customer-address/>

    <shipping/>

    <payment/>

  </div>
</div> 

</template>

<script>
	import bus from '../../bus'

  import { mapActions, mapGetters, mapMutations } from 'vuex'

  	export default {
        props: {
          checkoutId: {
            required: true,
            type: String
          },
          currency: {
            required: true,
            type: String
          },
          checkoutSession: {
            required: true,
            type: Object
          }
        },
        data () {
          return {
            timer: '',
            status: 0,
            show: false
          }
        },
        computed: {
          ...mapGetters({

            loggedin: 'loggedin',
            checkout_session: 'checkout_session',
            checkout: 'checkout'

          })
        },
        methods: {
          ...mapMutations({

              setCheckoutId: 'setCheckoutId',
              setCheckoutSession: 'setCheckoutSession'

          }),
          ...mapActions({

              getCheckout: 'getCheckout',
              getCountries: 'getCountries',
              getRates: 'getRates',
              getPayments: 'getPayments'

          }),
          reserveOrder(reservation_time, reservation_time_left) {

            var vm = this;
            this.status = 1;
            var date = new Date(Date.parse(reservation_time_left));
            var countDown = date.getTime();

            var now = new Date(Date.parse(reservation_time));
           
            var x = setInterval(function() {

              now.setSeconds(now.getSeconds() + 1);
              var distance = countDown - now;
 
              var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
              var seconds = Math.floor((distance % (1000 * 60)) / 1000);

              vm.timer = ("0" + minutes).slice(-2) + ':' + ("0" + seconds).slice(-2);
        
              if (distance < 0) {
                clearInterval(x);
                vm.timer = 0;
                setTimeout(() => { 
                  window.location.pathname = '/cart';
                }, 2000);
              }
            }, 1000);
          }
        },
        created () {
          this.setCheckoutId(this.checkoutId);
          this.setCheckoutSession(this.checkoutSession);
        },
        updated() {

          if(this.checkout && !this.status) {
            this.reserveOrder(this.checkout.reservation_time, this.checkout.reservation_time_left);
          }

        },
        mounted() {
          var vm = this;
          this.getCheckout();
          this.getCountries();

          if(this.checkoutSession['checkout-customer-step'].step_is_complete === false) {
            $('a[href="#customerTab"]').tab('show');
          } else if(this.checkoutSession['checkout-addresses-step'].step_is_complete === false) {
            $('a[href="#addressTab"]').tab('show');
          } else if(this.checkoutSession['checkout-shipping-step'].step_is_complete === false) {
            $('a[href="#shippingTab"]').tab('show');
          } else if(this.checkoutSession['checkout-payment-step'].step_is_complete === false) {
            $('a[href="#paymentTab"]').tab('show');
          } 
          
        },
    }
</script>