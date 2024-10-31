window.Vue = require('vue');
window.axios = require('axios');
const fx = require("money");

Vue.mixin({
  data () {
    return {
      fx: false
    }
  },
  methods: {
  	initMoney () {
      axios.get('https://openexchangerates.org/api/latest.json?app_id=9be6338b123a43928e306471b48ab699')
      .then((response) => { 
          // Check money.js has finished loading:
          if ( typeof fx !== "undefined" && fx.rates ) {
              fx.rates = response.data.rates;
              fx.base = response.data.base;
          } else {
              // If not, apply to fxSetup global:
              var fxSetup = {
                  rates : response.data.rates,
                  base : response.data.base
              }
          }
          this.fx = true;
      })
    },
  	formatMoney (amount) {
  		return accounting.formatMoney(amount, {
  			symbol: localStorage.storeCurrency,
  			format: "%s %v"
  		})
  	},
  	convertMoney (amount) {
      var value = accounting.unformat(amount); // clean up number (eg. user input)
      var convertedValue = fx(value).from(localStorage.storeCurrency).to(localStorage.userCurrency);

      return accounting.formatMoney(convertedValue, {
        symbol: localStorage.userCurrency,
        format: "%s %v"
      });
    }
  }
})

Vue.component('customer', require('./components/checkout/Customer.vue'));
Vue.component('checkout', require('./components/checkout/Checkout.vue'));
Vue.component('mobile-cart', require('./components/checkout/MobileCart.vue'));
Vue.component('order-summary', require('./components/checkout/OrderSummary.vue'));
Vue.component('customer-address', require('./components/checkout/Address.vue'));
Vue.component('shipping', require('./components/checkout/Shipping.vue'));
Vue.component('payment', require('./components/checkout/Payment.vue'));
Vue.component('wizard-button-group', require('./components/checkout/ButtonGroup.vue'));
Vue.component('cart', require('./components/checkout/Cart.vue'));


const app = new Vue({
    el: '#app'
});

$(document).on('change', '[name="address_choice"]', function () {
    $("#shippingAddress").slideToggle();
})