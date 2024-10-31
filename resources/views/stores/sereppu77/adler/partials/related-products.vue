<template>
	
	<section id="b-products" v-if="products.length">
      <div class="b-section_title">
         <h4 class="text-center text-uppercase">
          RELATED PRODUCTS
          <span class="b-title_separator"><span></span></span>
         </h4>
      </div>
      <div class="b-products b-product_grid b-product_grid_four mb-4">
          <div class="container">
              <div class="clearfix owl-carousel owl-theme" id="b-related_products">
                
                  <div v-for="product in products" :key="product.id">
                    <div class="b-product_grid_single">
                       <div class="b-product_grid_header">
                           <a :href="product.url">
                             <img :data-src="images(product)" :src="cover(product)" class="img-fluid img-switch d-block m-auto" :alt="product.name" style="">
                           </a>
                           <div class="b-hover_img"><a :href="product.url"><img :src="hoverImage(product)" class="img-fluid img-switch d-block" alt=""></a></div> 
                          <quickview-button :endpoint="`/api/products/${product.handle}`"></quickview-button>
                       </div>
                       <div class="b-product_grid_info">
                            <h3 class="product-title">
                                <a :href="product.url">{{ product.name }}</a>
                            </h3>
                            <div class="clearfix">
                              <div class="b-product_grid_toggle float-left">
                                  <span class="b-price money">{{ product.price }}</span>
                                  <quick-action :endpoint="`/api/products/${product.handle}`" :product="product"></quick-action>
                              </div>
                            </div>
                       </div>
                    </div>
                  </div>
              </div>
          </div>
      </div>
  </section>

</template>

<script>
	
  import settings from '../assets/settings.js'

	export default {
		props: ['products'],
    mixins: [settings],
			data () {
				return {
	        placeholder: 'https://via.placeholder.com/263x336',
				}
			},
			
	    methods: {
	   
	     cover (product) {

            if(product.cover_image) {
              return product.cover_image.medium;
            }
            
            return this.placeholder;
        },
        images (product) {

          if(product.images[0] && product.images[1]) {

            return product.images[0].medium + ', ' + product.images[1].medium;

          } else if(product.images[0]) {

            return product.images[0].medium + ', ' + product.images[0].medium;

          } else if(product.cover_image) {
            return product.cover_image.medium + ', ' + product.cover_image.medium;
          }

          
          return this.placeholder + ', ' + this.placeholder;
         
        },
        hoverImage (product) {

          if(product.images[0]) {

            return product.images[0].medium;

          } else if(product.cover_image) {

            return product.cover_image.medium;

          }

          return this.placeholder;
        },

	    }
	}


</script>