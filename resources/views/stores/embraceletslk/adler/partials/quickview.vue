<template>

	<div class="modal fade product_view" id="b-qucik_view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content"> 
              <button type="button" class="btn btn-close btn-secondary" data-dismiss="modal">
                <i class="aapl-cross-circle icons"></i>
              </button>
              <div class="modal-body p-0">
                  <div class="row" v-if="!loading">
                      <div class="col-md-6 product_img">
                          <div class="owl-carousel owl-theme" id="b-product_pop_slider">
                            <div v-if="product.cover_image">
                              <img :src="product.cover_image.medium" :alt="product.cover_image.alt_text" class="img-fluid d-block m-auto">
                            </div>
                            <div v-else><img :src="defaultImg" alt="image coming soon" class="img-fluid d-block m-auto"></div>
                            <div v-if="product.images.length" v-for="image in product.images" :key="image.id">
                              <img :src="image.medium" :alt="image.alt_text" class="img-fluid d-block m-auto">
                            </div>
                          </div>
                      </div>
                      <div class="col-md-6 product_content pr-5 pt-4">
                         <div class="b-product_single_summary">
                            <h1>{{ product.name }}</h1>
                            <p class="b-price">
                              <span class="b-amount">{{ price }}</span>
                            </p>
                            <div class="b-produt_description" v-html="product.short_description"></div>
                            <div class="b-product_attr" v-if="product.type === 'variant'">
                              <product-variation :option-selectors="product.options" :variants="product.variants" :product-handle="product.handle" :preorder="product.preorder" :backorder="product.backorder"/>
                            </div>
                            <add-to-cart :product="product"/>
                            <div class="b-product_single_option">
                              <ul class="pl-0 list-unstyled"> 
                                <li><b class="text-uppercase">Sku</b>: {{ sku }}</li>
                              </ul>
                            </div>
                         </div>
                      </div>
                  </div>
                  <div v-else class="text-center my-5 vld-parent">
                    <loading :active.sync="loading" :is-full-page="false" :height="32" :width="32"></loading>
                  </div>
              </div>
          </div>
        </div>
    </div>

</template>

<script>
  import settings from '../assets/settings.js' 
	import bus from '../assets/bus'
  import productVariation from './product-variation.vue'
  import addToCart from './add-to-cart.vue'
  import Loading from 'vue-loading-overlay';

	export default {
    mixins: [settings],
    components: {
      'add-to-cart': addToCart,
      'product-variation': productVariation,
      Loading
    },
		data () {
			return {
        product: '',
        loading: true,
        productSKU: '',
        productPrice: 0
			}
		},
		computed: {
			sku : {
        get: function () {
         return this.productSKU ? this.productSKU : 'n/a';
        },
        set: function (value) {
          this.productSKU = value;
        }
        
      },
      price : {
        get: function () {
         return this.productPrice;
        },
        set: function (value) {
          this.productPrice = value;
        }
        
      },
		},
    methods: {
      ProductPopSlider () {
        if($("#b-product_pop_slider").length > 0){
          $('#b-product_pop_slider').owlCarousel({
              loop:true,
              margin:0,
              nav:true,
              dots: false,
              responsive:{
                  0:{
                      items:1
                  },
                  600:{
                      items:1
                  },
                  1000:{
                      items:1
                  }
              }
          })
        }
      },
      getImage (image) {

        if(image) {
          return `<div>
                    <img src="${image.medium}" alt="${image.alt_text}" class="img-fluid d-block m-auto">
                  </div>`;
        }

      },
      loadDefaultPrice () {
        if(this.product.price_min !== this.product.price_max) {
          this.price = this.formatMoney(this.product.price_min) + ' - ' + this.formatMoney(this.product.price_max);
        } else {
          this.price = this.formatMoney(this.product.price);
        }
      }, 
      loadDefaults () {

        if(!this.product) {
          return;
        }
        
        this.loadDefaultPrice();

        this.sku = this.product.sku
      }
    },
    updated () {
      this.ProductPopSlider();
    },
		mounted () {
      var vm = this;

      $('#b-qucik_view').on('shown.bs.modal', function (e) {
        var endpoint = $(e.relatedTarget).data('src');
        axios.get(endpoint).then((response) => { 
          vm.loading = false;
          vm.product = response.data.data;
          vm.sku = vm.product.sku;
          vm.loadDefaultPrice();
        }).catch((error) => {
            console.log(error)
        })
      });

      bus.$on('variation.refresh', (variation) => {

        if(!this.product) {
          return;
        }

        this.price = this.formatMoney(variation.price);
        this.sku = variation.sku;

        if(variation.image) {

          var item = this.getImage(variation.image);
          $('#b-product_pop_slider').trigger('replace.owl.carousel', [item]);
          
        } 

      });

      bus.$on('selection.cleared', () => {

        this.loadDefaults();

      });

      $('#b-qucik_view').on('hide.bs.modal', function (e) {
        vm.loading = true;
      })
		}
	}

</script>