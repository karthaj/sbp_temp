<template>
	
<div class="tab-pane slide-left sm-no-padding" :class="{'active': active}" id="addressTab">
    <div class="row row-same-height">
        <div class="col-md-12">
            <h4>Address</h4>
            <ul class="list-group" v-if="checkout.cart">
                <li class="list-group-item">
                  <div class="row">
                      <div class="col-12 col-md-2">Contact:</div>
                      <div class="col-9 col-md-8">{{ checkout.cart.email }}</div>
                      <div class="col-3 col-md-2">
                          <a href="#" @click.prevent="switchTab">Change</a>
                      </div>
                  </div>
                </li>
            </ul>

            <h5>Billing address</h5>
            <div class="form-group form-group-default required"
            v-if="loggedin">
                <label>Saved addresses</label>
                <select class="form-control" v-model="billing_address_id" @change="getBillingAddress">
                  <option value="">New address…</option>
                  <option v-if="checkout.customer.addresses.length" v-for="address in checkout.customer.addresses" :key="address.id" :value="address.id">{{ address.alias }}</option>
                </select>
            </div>
            <div class="row clearfix">
                <div class="col-sm-6">
                    <div class="form-group form-group-default required" 
                    :class="{'has-error': errors['billing.firstname'] }">
                      <label>First name</label>
                      <input type="text" class="form-control" v-model="form.billing.firstname">
                    </div>
                    <label v-if="errors['billing.firstname']" class="error">{{ errors['billing.firstname'][0] }}</label>
                </div>
                <div class="col-sm-6">
                    <div class="form-group form-group-default required" :class="{'has-error': errors['billing.lastname'] }">
                      <label>Last name</label>
                      <input type="text" class="form-control" v-model="form.billing.lastname">
                    </div>
                    <label v-if="errors['billing.lastname']" class="error">{{ errors['billing.lastname'][0] }}</label>
                </div>
            </div>

            <div class="form-group form-group-default required" :class="{'has-error': errors['billing.address1'] }">
              <label>Address </label>
              <input type="text" class="form-control" v-model="form.billing.address1">
            </div>
            <label v-if="errors['billing.address1']" class="error">{{ errors['billing.address1'][0] }}</label>

            <div class="form-group form-group-default" :class="{'has-error': errors['billing.address2'] }">
              <label>Apt, suite, etc. (optional)</label>
              <input type="text" class="form-control" v-model="form.billing.address2">
            </div>
            <label v-if="errors['billing.address2']" class="error">{{ errors['billing.address2'][0] }}</label>

            <div class="row clearfix">
                <div :class="{'col-sm-12': billingStates.length === 0, 'col-sm-6':  billingStates.length > 0}">
                    <div class="form-group form-group-default required" :class="{'has-error': errors['billing.country'] }">
                        <label>Country</label>
                        <select class="form-control" v-model="form.billing.country" @change="getBillingStates()" autocomplete="billing country">
                            <option v-for="country in countries" :key="country.id" :value="country.iso_code">{{ country.name }}</option>
                        </select>
                    </div>
                    <label v-if="errors['billing.country']" class="error">{{ errors['billing.country'][0] }}</label>
                </div>
                <div class="col-sm-6" v-if="billingStates.length">
                    <div class="form-group form-group-default required" :class="{'has-error': errors['billing.state'] }">
                        <label>State</label>
                        <select class="form-control" v-model="form.billing.state" @change="getBillingStateCities()">
                          <option v-for="state in billingStates" :key="state.id" :value="state.iso_code">{{ state.name }}</option>
                        </select>
                    </div>
                    <label v-if="errors['billing.state']" class="error">{{ errors['billing.state'][0] }}</label>
                </div>
            </div>
            
            <div class="row clearfix">
              <div class="col-sm-6" v-if="billingCities.length">
                  <div class="form-group form-group-default required" :class="{'has-error': errors['billing.city'] }">
                      <label>City</label>
                      <select class="form-control" v-model="form.billing.city" @change="getBillingCityPostCode()">
                        <option v-for="city in billingCities" :key="city.id" :value="city.name">{{ city.name }}</option>
                      </select>
                  </div>
                  <label v-if="errors['billing.city']" class="error">{{ errors['billing.city'][0] }}</label>
              </div>
              <div v-else class="col-sm-6">
                <div class="form-group form-group-default required" :class="{'has-error': errors['billing.city'] }">
                  <label>city</label>
                  <input type="text" class="form-control" v-model="form.billing.city">
                </div>
                <label v-if="errors['billing.city']" class="error">{{ errors['billing.city'][0] }}</label>
              </div>
              <div class="col-sm-6">
                    <div class="form-group form-group-default required" :class="{'has-error': errors['billing.postcode'] }">
                      <label>post code</label>
                      <input type="text" class="form-control" v-model="form.billing.postcode" :readonly="form.billing.country === 'LK'">
                    </div>
                    <label v-if="errors['billing.postcode']" class="error">{{ errors['billing.postcode'][0] }}</label>
                </div>
            </div>    
            
            <div class="form-group form-group-default required" :class="{'has-error': errors['billing.phone'] }">
              <label>Phone</label>
              <input type="text" class="form-control" v-model="form.billing.phone">
            </div>
            <label v-if="errors['billing.phone']" class="error">{{ errors['billing.phone'][0] }}</label>
   
            <h5 v-if="checkout.cart && checkout.cart.need_shipping">Shipping address</h5>

            <ul v-if="checkout.cart && checkout.cart.need_shipping" class="list-group">
                <li class="list-group-item">
                    <div class="radio radio-info">
                      <input type="radio" id="same" checked name="address_choice" value="true" v-model="form.same_address">
                      <label for="same">Same as billing address</label>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="radio radio-info">
                      <input type="radio" id="different" name="address_choice" value="false" v-model="form.same_address">
                      <label for="different">Use a different shipping address</label>
                    </div>
                    <div class="collapse" id="shippingAddress" :style="{display: different}">
                      <div class="form-group form-group-default required" :class="{'has-error': errors['shipping.address_id'] }"
                      v-if="loggedin">
                          <label>Saved addresses</label>
                          <select class="form-control" v-model="shipping_address_id" @change="getShippingAddress">
                            <option value="">New address…</option>
                            <option v-if="checkout.customer.addresses.length" v-for="address in checkout.customer.addresses" :key="address.id" :value="address.id">{{ address.alias }}</option>
                          </select>
                      </div>
                      <label v-if="errors['shipping.address_id']" class="error">{{ errors['shipping.address_id'][0] }}</label>
                      <div class="row clearfix">
                          <div class="col-sm-6">
                              <div class="form-group form-group-default required" :class="{'has-error': errors['shipping.firstname'] }">
                                <label>First name</label>
                                <input type="text" class="form-control" v-model="form.shipping.firstname">
                              </div>
                              <label v-if="errors['shipping.firstname']" class="error">{{ errors['shipping.firstname'][0] }}</label>
                          </div>
                          <div class="col-sm-6">
                              <div class="form-group form-group-default required" :class="{'has-error': errors['shipping.lastname'] }">
                                <label>Last name</label>
                                <input type="text" class="form-control" v-model="form.shipping.lastname">
                              </div>
                              <label v-if="errors['shipping.lastname']" class="error">{{ errors['shipping.lastname'][0] }}</label>
                          </div>
                      </div>
                      <div class="form-group form-group-default required" :class="{'has-error': errors['shipping.address1'] }">
                        <label>Address</label>
                        <input type="text" class="form-control" v-model="form.shipping.address1">
                      </div>
                      <label v-if="errors['shipping.address1']" class="error">{{ errors['shipping.address1'][0] }}</label>
 
                      <div class="form-group form-group-default" :class="{'has-error': errors['shipping.address2'] }">
                        <label>Apt, suite, etc. (optional)</label>
                        <input type="text" class="form-control" v-model="form.shipping.address2">
                      </div>
                      <label v-if="errors['shipping.address2']" class="error">{{ errors['shipping.address2'][0] }}</label>

                      <div class="row clearfix">
                        <div :class="{'col-sm-12': shippingStates.length === 0, 'col-sm-6':  shippingStates.length > 0}">
                            <div class="form-group form-group-default required" :class="{'has-error': errors['shipping.country'] }">
                                <label>Country</label>
                                <select class="form-control" v-model="form.shipping.country" @change="getShippingStates()" autocomplete="shipping country">
                                    <option v-for="country in countries" :key="country.id" :value="country.iso_code">{{ country.name }}</option>
                                </select>
                            </div>
                            <label v-if="errors['shipping.country']" class="error">{{ errors['shipping.country'][0] }}</label>
                        </div>
                        <div class="col-sm-6" v-if="shippingStates.length">
                            <div class="form-group form-group-default required" :class="{'has-error': errors['shipping.state'] }">
                                <label>State</label>
                                <select class="form-control" v-model="form.shipping.state" @change="getShippingStateCities()">
                                  <option v-for="state in shippingStates" :key="state.id" :value="state.iso_code">{{ state.name }}</option>
                                </select>
                            </div>
                            <label v-if="errors['shipping.state']" class="error">{{ errors['shipping.state'][0] }}</label>
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                      <div class="col-sm-6" v-if="shippingCities.length">
                          <div class="form-group form-group-default required" :class="{'has-error': errors['shipping.city'] }">
                              <label>City</label>
                              <select class="form-control" v-model="form.shipping.city" @change="getShippingCityPostCode()">
                                <option v-for="city in shippingCities" :key="city.id" :value="city.name">{{ city.name }}</option>
                              </select>
                          </div>
                          <label v-if="errors['shipping.city']" class="error">{{ errors['shipping.city'][0] }}</label>
                      </div>
                      <div v-else class="col-sm-6">
                        <div class="form-group form-group-default required" :class="{'has-error': errors['shipping.city'] }">
                          <label>city</label>
                          <input type="text" class="form-control" v-model="form.shipping.city">
                        </div>
                        <label v-if="errors['shipping.city']" class="error">{{ errors['shipping.city'][0] }}</label>
                      </div>
                      <div class="col-sm-6">
                            <div class="form-group form-group-default required" :class="{'has-error': errors['shipping.postcode'] }">
                              <label>post code</label>
                              <input type="text" class="form-control" v-model="form.shipping.postcode" :readonly="form.shipping.country === 'LK'">
                            </div>
                            <label v-if="errors['shipping.postcode']" class="error">{{ errors['shipping.postcode'][0] }}</label>
                        </div>
                    </div>


                      <div class="form-group form-group-default required" :class="{'has-error': errors['shipping.phone'] }">
                        <label>Phone</label>
                        <input type="text" class="form-control" v-model="form.shipping.phone">
                      </div>
                      <label v-if="errors['shipping.phone']" class="error">{{ errors['shipping.phone'][0] }}</label>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="row mt-5 justify-content-between">
      <div class="col-md-4 col-12 order-2 order-md-1 text-center text-sm-left">
        <a href="/cart">Return to cart</a>
      </div>
      <div class="col-md-4 col-12 order-1 order-md-2 mb-4">
        <button class="btn btn-info btn-cons btn-block" type="button" :disabled="disabled" @click.prevent="save()">
            <span v-if="checkout.cart.need_shipping">Continue to shipping</span>
            <span v-else="checkout.cart.need_shipping">Continue to payment</span>
          </button>
      </div>
    </div>
</div>

</template>

<script>
  import bus from '../../bus'
  import { mapActions, mapGetters, mapMutations } from 'vuex'

	export default {
        data () {
            return {
                billing_address_id: '',
                shipping_address_id: '',
                form: {
                  billing: {
                    firstname: '',
                    lastname: '',
                    address1: '',
                    address2: '',
                    city: '',
                    country: '',
                    state: '',
                    phone: '',
                    postcode: ''
                  },
                  shipping: {
                    firstname: '',
                    lastname: '',
                    address1: '',
                    address2: '',
                    city: '',
                    country: '',
                    state: '',
                    phone: '',
                    postcode: ''
                  },
                  same_address: 'true'
                },
                disabled: false,
                errors: [],
                billingStates:[],
                shippingStates:[],
                billingCities:[],
                shippingCities:[],
                billingCountry: {},
                shippingCountry: {},
                billingState: {},
                shippingState: {}
            }
        },
        computed: {

          ...mapGetters({

            loggedin: 'loggedin',
            checkout: 'checkout',
            checkout_id: 'checkout_id',
            checkout_session: 'checkout_session',
            countries: 'countries'

          }),
          defaultAddress () {
            var address = this.addresses.find((address) => {
              return address.default == true;
            });
            
            return address.id;
          },
          active () {

            if(this.checkout_session && this.checkout_session['checkout-addresses-step'].step_is_complete === false && this.checkout_session['checkout-addresses-step'].step_is_reachable === true) {
              return true;
            }

            return false;
          },
          different () {

            if(!this.form.same_address) {
              return 'block';
            }

            return 'none';

          }
          
        },
        methods: {
          ...mapActions({

              getCheckout: 'getCheckout'

          }),
          switchTab () {
            $('a[href="#customerTab"]').tab('show')
          },
          getBillingStates () {
            var country = this.countries.find((country) => {
                            return country.iso_code === this.form.billing.country;
                          });
            
            if(country) {
              this.billingCities = [];
              this.billingCountry = country;
              this.billingStates = country.states;
            } else {
              this.billingCountry = {};
              this.billingCities = [];
            }

          },
          getShippingStates () {
            var country = this.countries.find((country) => {
                            return country.iso_code === this.form.shipping.country;
                          });
            if(country) {
              this.billingCities = [];
              this.shippingCountry = country;
              this.shippingStates = country.states;
            } else {
              this.billingCountry = {};
              this.shippingCities = [];
            }
      
          },
          getBillingStateCities () {
            var state = this.billingCountry.states.find((state) => {
                            return state.iso_code === this.form.billing.state;
                          });

            if(!state) {
              return;
            }

            this.form.billing.city = '';
            this.form.billing.postcode = '';
            this.billingState = state;
            this.billingCities = state.cities;
            
          },
          getShippingStateCities () {
            var state = this.shippingCountry.states.find((state) => {
                            return state.iso_code === this.form.shipping.state;
                          });

            if(!state) {
              return;
            }
            
            this.form.shipping.city = '';
            this.form.shipping.postcode = '';
            this.shippingState = state;
            this.shippingCities = state.cities;
            
          },
          getBillingCityPostCode () {

            var city = this.billingState.cities.find((city) => {
                            return city.name === this.form.billing.city;
                          });

            if(!city) {
              return;
            }

            this.form.billing.postcode = city.zip_code;
          },
          getShippingCityPostCode () {
            var city = this.shippingState.cities.find((city) => {
                            return city.name === this.form.shipping.city;
                          });

            if(!city) {
              return;
            }

            this.form.shipping.postcode = city.zip_code;
          },
          getIpInfo() {
            axios.get('https://ipinfo.io/json').then((response) => {
              if(response.data) {
                this.form.billing.country = response.data.country;
                this.getBillingStates();
                this.form.shipping.country = response.data.country;
                this.getShippingStates();
              }
            })
          },
          save () {
            this.disabled = true;
            
            if(this.loggedin) {
              this.form['billing_address_id'] = this.billing_address_id;
              this.form['shipping_address_id'] = this.shipping_address_id;
            }

            axios.post(`/checkout/${this.checkout_id}/address`, this.form).then((response) => { 
               this.errors = [];
               this.disabled = false;
               this.getCheckout();

               if(this.checkout.cart.need_shipping) {
                  $('a[href="#shippingTab"]').tab('show');
               } else {
                  $('a[href="#paymentTab"]').tab('show');
               }
               
            }).catch((error) => {
                if(error.response.data.status === 'expired') {
                  window.location.href = error.response.data.return_url;
                }
                this.errors = error.response.data;
                this.disabled = false;
            })

          },
          getBillingAddress () {
            var address = this.checkout.customer.addresses.find((address) => {
              return address.id === this.billing_address_id;
            });

            if(address) {
              this.form.billing.city = '';
              this.form.billing.postcode = '';
              this.form.billing = address;
              this.billingCities = [];
              this.getBillingStates();
              this.getBillingStateCities();
            } else {
              this.form.billing = {};
              this.billingStates = [];
              this.billingCities = [];
            }

          },
          getShippingAddress () {

            var address = this.checkout.customer.addresses.find((address) => {
              return address.id === this.shipping_address_id;
            });

            if(address) {
              this.form.shipping = address;
              this.shippingCities = [];
              this.getShippingStates();
              this.getShippingStateCities();
            } else {
              this.form.shipping = {};
              this.shippingStates = [];
              this.shippingCities = [];
            }

          }
        },
        mounted() {
          var vm = this;
          this.form.same_address = String(this.checkout_session['checkout-addresses-step'].use_same_address);
          this.getIpInfo();
          $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            if(vm.checkout.billing_address) {
              vm.form.billing = vm.checkout.billing_address;
              vm.billing_address_id = vm.checkout.billing_address.id;
              vm.getBillingStates();
              vm.getBillingStateCities();
            }

            if(!vm.form.same_address && vm.checkout.shipping_address) {
              vm.form.shipping = vm.checkout.shipping_address;
              vm.shipping_address_id = vm.checkout.shipping_address.id;
              vm.getShippingStates();
              vm.getBillingStateCities();
            }
          })
        },  
    }
</script>