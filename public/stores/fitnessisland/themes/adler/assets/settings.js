import { mapActions, mapMutations, mapGetters } from 'vuex'

export default {

  computed: {
  	...mapGetters({

        settings: 'settings',
        defaultImg: 'defaultImg',
        shopbox: 'shopbox',
        cart: 'cart',
        authenticated: 'authenticated',
        user: 'user'

    }),
  },
  methods: {
    ...mapActions({

      getCart: 'getCart',
      removeItemFromCart: 'removeItemFromCart',
      getUser: 'getUser'

    }),
    ...mapMutations({
      
      setCart: 'setCart',
      setAuth: 'setAuth'

    }),
  	formatMoney (amount) {
      var value = accounting.unformat(amount);
      var convertedValue = Currency.convert(value, this.shopbox.store.currency, localStorage.userCurrency);
      
      return accounting.formatMoney(convertedValue, {
        symbol: localStorage.userCurrency,
        format: "%s %v"
      });
    }
  }

}