<template>
	
<div class="tab-pane sm-no-padding slide-left" :class="{'active': active}" id="customerTab">
    <div class="row row-same-height">
        <div class="col-md-12">
            <div class="row align-items-end">
              <div class="col-md-5">
                <h4 class="font-weight-normal">Contact Information</h4>   
              </div>
                <p v-if="guest && !loggedin" class="col-md-7 text-left text-sm-right">Already have a ShopBox customer account? 
                  <a href="#" @click.prevent="toggleCustomer">Log in</a>
                </p>
                <p v-else-if="!guest && !loggedin" class="col-md-7 text-left text-sm-right">
                  <a  href="#" @click.prevent="toggleGuest">Continue as a guest</a>
                </p>
            </div>
            <div v-if="errors['error']" class="alert alert-danger" role="alert">
              <i class="aapl-notification-circle mr-2"></i> {{ errors['error'] }}
            </div>
            
            <p v-if="guest && !loggedin" class="layout-flex__item my-5">Checking out as a <strong>Guest?</strong> You'll be able to save your details to create an account with us later.</p>

            <div v-if="guest && !loggedin" class="form-group form-group-default required" :class="{'has-error': errors['email'] }">
              <label>Email</label>
              <input type="text" id="email" class="form-control" v-model="form.email">
            </div>

            <label v-if="errors['email'] && guest" id="email-error" class="error" for="email">{{ errors['email'][0] }}</label>

            <div v-if="guest && !loggedin" class="checkbox check-info checkbox-circle">
                <input type="checkbox" id="newsletter" value="1" v-model="form.newsletter">
                <label for="newsletter">Keep me up to date on news and exclusive offers</label>
            </div>
            <label v-if="errors['newsletter']" id="newsletter-error" class="error" for="newsletter">{{ errors['newsletter'][0] }}</label>
            
            <form v-if="!guest && !loggedin" action="#" method="post" @submit.prevent="login()">
               <div class="row justify-content-center mt-5">
                  <div class="col-md-6">
                      <div class="row">
                        <div class="col-12">
                            <div class="form-group form-group-default required" :class="{'has-error': errors['email'] }">
                              <label>Email</label>
                              <input type="text" id="email" name="email" class="form-control" autocomplete="off" v-model="form.email">
                            </div>
                            <label v-if="errors['email']" id="email-error" class="error" for="email">{{ errors['email'][0] }}</label>

                              <div class="form-group form-group-default required" :class="{'has-error': errors['password'] }">
                                <label>Password</label>
                                <input type="password" id="password" name="password" class="form-control" v-model="form.password">
                              </div>
                              <label v-if="errors['password']" id="password-error" class="error" for="password">{{ errors['password'][0] }}</label>

                              <button type="submit" class="btn btn-info btn-sm btn-block mb-3" :disabled="disabled">Login</button>
                              <div class="col-12 text-center">
                                <a href="/register">Create account</a> <br>
                                <a href="/password/reset">Reset password</a>
                              </div>
                        </div>
                      </div>
                  </div>
              </div>
            </form>

            <div class="media" v-if="loggedin  && checkout.customer">
              <img v-if="checkout.customer.avatar"class="mr-3 rounded" :src="checkout.customer.avatar" alt="profile picture">
              <img v-else class="mr-3 rounded" src="https://via.placeholder.com/64" alt="Generic placeholder image">
              <div class="media-body">
                <p class="mb-0">{{ checkout.customer.firstname }} {{ checkout.customer.lastname }}</p>
                <p class="mb-0">{{ checkout.customer.email }}</p>
                <a  href="#" @click.prevent="logout()">Logout</a>
              </div>
            </div>

        </div>
    </div>
    <div v-if="guest && !loggedin" class="row mt-5 justify-content-between">
      <div class="col-md-4 col-12 order-2 order-md-1 text-center text-sm-left">
        <a href="/cart">Return to cart</a>
      </div>
      <div class="col-md-4 col-12 order-1 order-md-2 mb-4">
        <button class="btn btn-info btn-cons btn-block" type="button" 
          @click.prevent="saveCustomer()" :disabled="disabled">
            <span>Continue to address</span>
          </button>
      </div>
    </div>
</div>

</template>

<script>
  import bus from '../../bus'
  import { mapActions, mapGetters } from 'vuex'

	export default {
        data () {
            return {
                guest: true,
                form: {
                  email: '',
                  password: '',
                  newsletter: 0,
                },
                disabled: false,
                errors: []
            }
        },
        computed: {
          ...mapGetters({

            loggedin: 'loggedin',
            checkout: 'checkout',
            checkout_id: 'checkout_id',
            checkout_session: 'checkout_session'

          }),
          active () {

            if(this.checkout_session && this.checkout_session['checkout-customer-step'].step_is_complete === false && this.checkout_session['checkout-customer-step'].step_is_reachable === true) {
              return true;
            }

            return false;
          }
        },
        methods: {
          ...mapActions({

              getCheckout: 'getCheckout'

          }),
            toggleCustomer () {
              this.errors = [];
              this.guest = false;
            },
            toggleGuest () {
              this.errors = [];
              this.guest = true;
            },
            saveCustomer () {
              this.disabled = true;
             
              axios.post(`/checkout/${this.checkout_id}/customer`, this.form).then((response) => { 
                 this.errors = [];
                 this.disabled = false;
                 this.getCheckout();
                 $('a[href="#addressTab"]').tab('show')
              }).catch((error) => {
                  if(error.response.data.status === 'expired') {
                    window.location.href = error.response.data.return_url;
                  }
                  this.errors = error.response.data;
                  this.disabled = false;
              })
            },
            login () {
              this.disabled = true;

              axios.post('/checkout/customer', this.form).then((response) => { 
                 this.errors = [];
                 this.disabled = false;
                 this.getCheckout();
                 $('a[href="#addressTab"]').tab('show');
              }).catch((error) => {
                  if(error.response.data.status === 'expired') {
                    window.location.href = error.response.data.return_url;
                  }
                  this.errors = error.response.data;
                  this.disabled = false;
              })
            },
            logout () {
              this.disabled = true;
              axios.delete('/checkout/customer').then((response) => { 
                this.getCheckout();
                this.form.email = '';
                this.form.password = '';
                this.guest = true;
                this.disabled = false;
              }).catch((error) => {
                  if(error.response.data.status === 'expired') {
                    window.location.href = error.response.data.return_url;
                  }
                  this.disabled = false;
              })
            }
        },
        mounted() {
     
          if(this.checkout.cart) {
            this.form.email = this.checkout.cart.email;
            this.form.newsletter = this.checkout.buyer_accepts_marketing;
          }

        }
    }
</script>