import Cookies from 'js-cookie'
import { mapActions, mapMutations, mapGetters } from 'vuex'

export default {

  computed: {
  	...mapGetters({

        settings: 'settings',
        defaultImg: 'defaultImg',
        shopbox: 'shopbox',
        cart: 'cart',
        cart_loading: 'cart_loading'

    }),
  },
  methods: {
    ...mapActions({

      getCart: 'getCart',
      removeItemFromCart: 'removeItemFromCart',

    }),
    ...mapMutations({
      
      setCart: 'setCart',
      setLoading: 'setLoading'

    }),
    formatMoney (amount) {
      var currency = Cookies.get('currency');
      var value = accounting.unformat(amount);
      var convertedValue = Currency.convert(value, this.shopbox.store.currency, currency);
      
      return accounting.formatMoney(convertedValue, {
        symbol: currency,
        format: "%s %v"
      });
    }
  }

}