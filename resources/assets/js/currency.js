import { mapGetters } from 'vuex'

export default {

  computed: {
    ...mapGetters({

        checkout: 'checkout'

    }),
    currencyDiffers () {
      
      if(this.checkout.cart.currency !== localStorage.userCurrency) {

        return true;

      }

      return false;

    }

  },
  methods: {

    formatMoney (amount, currency = this.checkout.cart.currency) {

      return accounting.formatMoney(amount, {
        symbol: currency,
        format: "%s %v"
      })

    },
    convert (amount) {

      return Currency.convert(amount, this.checkout.cart.currency, localStorage.userCurrency);
      
    }
  }

}