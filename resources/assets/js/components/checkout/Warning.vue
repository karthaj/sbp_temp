<template>
	
<div v-if="checkout.cart && checkout.cart.requires_splitting" class="tab-pane sm-no-padding active slide-left">
    <div class="row">
        <div class="col-md-12">
          <div class="alert alert-warning" role="alert">
            <i class="aapl-warning"></i> Following product(s) cannot be checkout, click continue to remove and proceed further.
          </div>
          <ul v-if="checkout.cart" class="list-group">
            <li v-for="item in checkout.cart.items" v-if="item.requires_splitting" class="list-group-item">
              <div class="media">
                <img :src="item.image" class="mr-3 img-fluid" width="50" :alt="item.name">
                <div class="media-body">
                  <h6 class="mt-0">{{ item.name }}</h6>
                </div>
              </div>
            </li>
          </ul>
        </div>
    </div>
    <div class="padding-20 sm-padding-5 sm-m-b-20 sm-m-t-20 bg-white clearfix">
      <ul class="pager wizard no-style">
        <li class="next">
          <button class="btn btn-info btn-cons pull-right" type="button" @click.prevent="update()" :disabled="disabled">
            <span>Continue</span>
          </button>
        </li>
      </ul>
    </div>
</div>

</template>

<script>
  
import { mapActions, mapGetters } from 'vuex'
import currency from '../../currency'

export default {
  mixins:[currency],
  data () {
    return {
      disabled: false,
      products: []
    }
  },
  computed: {
    ...mapGetters({

      checkout: 'checkout',
      checkout_id: 'checkout_id',

    })
  },
  methods: {
    ...mapActions({

        getCheckout: 'getCheckout'

    }),
    update() {

      this.disabled = true;
      var products = [];
      var items = _.filter(this.checkout.cart.items, ['requires_splitting', true]);
      
      _.forEach(items, function(value) {
        products.push(value.id);
      });

      axios.patch(`/checkout/${this.checkout_id}/update`, {
        products
      }).then((response) => { 
         window.location.pathname = '/checkout';
      }).catch((error) => {
          if(error.response.data.status === 'expired') {
            window.location.href = error.response.data.return_url;
          }
          this.errors = error.response.data;
          this.disabled = false;
      })
    },
    remove() {

      this.disabled = true;
      var products = [];
      var items = _.filter(this.checkout.cart.items, ['stock_count', 0]);
      
      _.forEach(items, function(value) {
        products.push(value.id);
      });

      axios.patch(`/checkout/${this.checkout_id}/update`, {
        products
      }).then((response) => { 
         // window.location.pathname = '/checkout';
      }).catch((error) => {
          this.errors = error.response.data;
          this.disabled = false;
      })

    }
  },
}

</script>