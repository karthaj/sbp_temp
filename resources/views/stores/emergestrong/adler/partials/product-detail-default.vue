<template>

	<div id="product-detail">
		<div class="b-product_single py-3">
            <div class="container">
              <div class="row clearfix">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="row clearfix b-product-display">
                    <div class="b-display-single" :class="{'col-xl-9 col-lg-9 col-md-9 col-sm-12': thumbnails.length, 'col-xl-12 col-lg-12 col-md-12 col-sm-12': !thumbnails.length}">
                      <div class="b-product-carousel owl-carousel" id="bSingleProductCarousel" data-slider-id="bSingleProductCarousel">
                        <div class="b-produt-item">
                          <img v-if="product.data.cover_image" :src="product.data.cover_image.medium" :alt="product.data.cover_image.alt_text" class="img-fluid"
                          :data-zoomed="product.data.cover_image.large">
                          <img v-else :src="defaultImg" alt="image coming soon" class="img-fluid"
                          :data-zoomed="defaultImg">
                        </div>
                        <div class="b-produt-item" v-if="product.data.images.length" v-for="image in product.data.images" :key="image.id">
                          <img :src="image.medium" :alt="image.alt_text" class="img-fluid"
                          :data-zoomed="image.large">
                        </div>
                      </div>
                    </div>
                    <div v-if="thumbnails.length" class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                      <div class="b-display-list-wrapper">
                        <div class="owl-thumbs b-display-item-list" id="bSingleProduct" data-slider-id="bSingleProductCarousel">
                          <div class="owl-thumb-item b-display-item">
                            <img v-if="product.data.cover_image" :src="product.data.cover_image.standard" :alt="product.data.cover_image.alt_text" class="img-fluid">
                            <img v-else :src="defaultImg" alt="image coming soon" class="img-fluid">
                          </div>
                          <div class="owl-thumb-item b-display-item" v-for="image in thumbnails" :key="image.id">
                            <img :src="image.standard" :alt="image.alt_text" class="img-fluid">
                          </div>
                        </div>

                        <div class="b-slider-action">
                          <button class="slick-prev"><i class="aapl-chevron-up"></i></button>
                          <button class="slick-next"><i class="aapl-chevron-down"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                  <div class="b-product_single_summary">
                    <h2>{{ product.data.name }}</h2>
                    <p v-if="discountPrice" class="b-price">
                      <del class="old-product-price">{{ price }}</del>
                      <span class="b-amount">{{ formatMoney(discountPrice) }}</span>
                    </p>
                    <p v-else class="b-price">
                      <span class="b-amount">{{ price }}</span>
                    </p>
                    <div class="b-produt_description" v-if="product.data.short_description">
                      <p v-html="product.data.short_description"></p>
                    </div>
                    <p v-if="product.data.preorder && product.data.available_on">{{ product.data.available_on }}</p>
                    <p v-if="!product.data.in_stock && !product.data.backorder && !product.data.preorder" class="out-of-stock">{{ product.data.outofstock_label }}</p>
                    <div class="b-product_attr" v-if="product.data.type === 'variant'">

                      <product-variation :option-selectors="product.data.options" :variants="product.data.variants" :product-handle="product.data.handle" :preorder="product.data.preorder" :backorder="product.data.backorder"/>

                    </div>
                
                    <add-to-cart v-if="product.data.backorder || product.data.preorder || product.data.in_stock" :product="product.data"/>
                    

                    <div class="b-product_single_option">
                      <ul class="pl-0 list-unstyled">
                        <li v-if="favorite"><a href="#" @click.prevent="remove"><i class="aapl-heart icons"></i> Listed</a></li>
                        <li v-else><a href="#" @click.prevent="wishlist"><i class="aapl-heart icons"></i> Add to wishlist</a></li>
                        <li><b class="text-uppercase">Sku</b>: {{ sku }}</li>
                        <li v-if="enableSocialSharing">
                          <b>Share</b>:
                          <span class="b-share_product">
                            <a target="_blank" :href="'//www.facebook.com/sharer.php?u='+product.data.url" class="fa fa-facebook"></a>
                            <a target="_blank" :href="'//twitter.com/share?text='+product.data.name+'&amp;url='+product.data.url" class="fa fa-twitter"></a>
                            <a v-if="product.data.cover_image" target="_blank" :href="'//pinterest.com/pin/create/button/?url='+product.data.url+'&amp;media='+product.data.cover_image.medium+'&amp;description='+product.data.name" class="fa fa-pinterest"></a>
                          </span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        
        <div class="b-product_tabs" v-if="product.data.description">
          <div class="container">
            <div class="row text-center pt-2 pt-3">
              <div class="col-sm-12"><h5>DESCRIPTION</h5></div>
            </div>
            <div class="row py-3">
              <div class="col-md-12 fr-view"  v-html="product.data.description"></div>
            </div>
          </div>
        </div>
      
        <related-products :products="product.data.related_products"></related-products>

	    <div class="container">
	        <hr class="pb-4">
	    </div>
	</div>

</template>

<script>
  import settings from '../assets/settings.js' 
  import relatedProducts from './related-products.vue'
  import productVariation from './product-variation.vue'
	import addToCart from './add-to-cart.vue'
  import bus from '../assets/bus'

	export default {
    props: {
      authenticated: {
        type: Boolean,
        default: false
      },
      wishlists: {
        type: Array,
        default() {
            return []
        }
      },
      section: {
        type: String,
        required: true
      }
    },
    mixins: [settings],
    components: {
      'related-products': relatedProducts,
      'product-variation': productVariation,
      'add-to-cart': addToCart
    },
		data () {
			return {
        product: {},
        thumbnails: [],
        productSKU: '',
        productPrice: 0,
        originalPrice: 0,
        discountPrice: 0,
        list: []
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
      favorite () {

        if(this.list.length) {
          var exists = this.list.find((p) => {
                  return p === this.product.data.id
              })

              if(exists) {
                return true;
              }
        }

        return false;

      },
      enableSocialSharing () {
        return this.settings['sections'][this.section]['settings']['enable_social_sharing'];
      }
    },
    methods: {
      SingleProductCarousel () {
        if($("#bSingleProductCarousel").length > 0){
          $('#bSingleProductCarousel').owlCarousel({
              loop:false,
              margin:0,
              nav:false,
              dots: false,
              items: 1,
              mouseDrag: false,
              thumbs: true,
              thumbsPrerendered: true
          })
        }
      },
      singleProductSlide () {
        if($("#bSingleProduct").length > 0){
          $('#bSingleProduct').slick({
            dots: false,
            arrows: false,
            speed: 800,
            infinite: false,
            autoplay: false,
            slidesToShow: 3,
            slidesToScroll: 3,
            vertical: true,
            draggable: false,
          });

          $(document).on('click', '.b-display-list-wrapper .b-slider-action .slick-prev', function(){
             $('#bSingleProduct').slick('slickPrev');
           });

           $(document).on('click', '.b-display-list-wrapper .b-slider-action .slick-next', function(){
              $('#bSingleProduct').slick('slickNext');
            });

            $('.b-display-list-wrapper .owl-thumb-item:first-of-type').addClass('thumb-active');

            $(document).on('click', '.b-display-list-wrapper .owl-thumb-item' ,function(){
              $('.b-display-list-wrapper .owl-thumb-item').removeClass('thumb-active');
              $(this).addClass('thumb-active');
            });
        }
      },
      getImage (image) {

        return `<div class="b-produt-item">
                          <img src="${image.medium}" alt="${image.alt_text}" class="img-fluid"
                          data-zoomed="${image.large}">
                        </div>`;

      },
      ProductZoom () {
        $('.b-produt-item').each(function(){
          $(this).find('img').zoomIt();
        });
      },
      loadDefaultPrice () {
        this.price = this.formatMoney(this.product.data.price_min);
        this.originalPrice = this.product.data.price_min;
        this.discountPrice = this.product.data.special_price;
      }, 
      loadDefaults () {
        this.loadDefaultPrice();
        this.sku = this.product.data.sku
      },
      wishlist () {

      if(!this.authenticated) {
        window.location.pathname = 'login';
        return;
      }

      axios.post('/wishlist', {
        product_id: this.product.data.id
      }).then((response) => { 
             this.list.push(this.product.data.id)
             bus.$emit('wishlist.added', this.list.length)
          }).catch((error) => {
              console.log(error);
          })
      },
      remove () {

        if(!this.authenticated) {
          window.location.pathname = 'login';
          return;
        }

        axios.post('/wishlist/item/remove', {
          product_id: this.product.data.id
        }).then((response) => { 
               this.list.splice(this.list.indexOf(this.product.data.id), 1);   
               bus.$emit('wishlist.removed', this.list.length)
            }).catch((error) => {
                console.log(error);
            })
      },
    },
    updated () {
      this.SingleProductCarousel();
      this.ProductZoom();
    },
    created () {
      this.product = JSON.parse(document.getElementById('productJson').innerHTML);
      this.thumbnails = this.product.data.images;
      this.sku = this.product.data.sku;
      this.loadDefaultPrice()

    },
		mounted () {
      this.singleProductSlide();
      this.SingleProductCarousel();
      this.ProductZoom();

      this.list = this.wishlists;
    
      bus.$on('variation.refresh', (variation) => {

        this.price = this.formatMoney(variation.price);
        this.originalPrice = variation.price;
        this.discountPrice = variation.special_price;
        this.sku = variation.sku;

        if(variation.image) {
      
          var item = this.getImage(variation.image);

          $('#bSingleProductCarousel').trigger('replace.owl.carousel', [item]);

          
        }

      });

      bus.$on('selection.cleared', () => {

        this.loadDefaults();

      });

      bus.$on('currency.switched', () => {
        this.price = this.formatMoney(this.originalPrice);
      });
	}
}

  
</script>