const fx = require("money");

export default {
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
      var value = accounting.unformat(amount); // clean up number (eg. user input)
      var convertedValue = fx(value).from(localStorage.storeCurrency).to(localStorage.userCurrency);

      return accounting.formatMoney(convertedValue, {
        symbol: localStorage.userCurrency,
        format: "%s %v"
      });
    }
  }
}