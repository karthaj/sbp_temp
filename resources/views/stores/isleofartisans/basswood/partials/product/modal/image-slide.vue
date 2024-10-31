<template>
	
<div class="col-md-5">
	<div class="tab-content product-details-large" id="myTabContent">
		<div v-if="variant_image" class="tab-pane fade show" :class="{'active' : variant_image}" role="tabpanel">
	      <div class="single-product-img img-full">
	        <img :src="variant_image.large" :alt="variant_image.alt_text">
	      </div>
	  	</div>

	  	<div v-if="cover_image" class="tab-pane fade show" :class="{'active': !variant_image}" id="single-slide0" role="tabpanel" aria-labelledby="single-slide-tab-0">
		    <div class="single-product-img img-full">
		        <img :src="cover_image.large" :alt="cover_image.alt_text">
		    </div>
	  	</div>
	  	<div v-else class="tab-pane fade show" :class="{'active': !variant_image}" id="single-slide0" role="tabpanel" aria-labelledby="single-slide-tab-0">
		    <div class="single-product-img img-full">
		        <img src="https://via.placeholder.com/600x756/f2f2f2/dcdfde?text=Image Coming Soon" alt="Image Coming Soon">
		    </div>
	  	</div>

	  <div v-if="images.length" v-for="image, index in images" :key="image.id" class="tab-pane fade" :id="`single-slide${index+1}`" role="tabpanel" :aria-labelledby="`single-slide-tab-${index+1}`">
	      <div class="single-product-img img-full">
	        <img :src="image.large" :alt="image.alt_text">
	      </div>
	  </div>
	</div>
	<div class="single-product-menu">
	    <div class="nav single-slide-menu" role="tablist">
	    	<div v-if="cover_image" class="single-tab-menu img-full">
	            <a class="active" data-toggle="tab" id="single-slide-tab-0" href="#single-slide0">
	            	<img :src="cover_image.standard" :alt="cover_image.alt_text">
	            </a>
	        </div>
	        <div v-else class="single-tab-menu img-full">
	            <a class="active" data-toggle="tab" id="single-slide-tab-0" href="#single-slide0"><img src="https://via.placeholder.com/600x756/f2f2f2/dcdfde?text=Image Coming Soon" alt=""></a>
	        </div>

	        <div v-if="images.length" v-for="image, index in images" :key="image.id" class="single-tab-menu img-full">
	            <a data-toggle="tab" :id="`single-slide-tab-${index+1}`" :href="`#single-slide${index+1}`">
	            	<img :src="image.standard" :alt="image.alt_text">
	            </a>
	        </div>
	    </div>
	</div>
</div>

</template>

<script>

import settings from '../../../assets/js/settings'
import bus from '../../../assets/js/bus'

export default {
	mixins: [settings],
	data () {
		return {
			images: [],
			cover_image: '',
			variant_image: ''
		}
	},
	methods: {
		initSlider () {
			$('.single-slide-menu').slick({
				prevArrow: '<i class="fa fa-angle-left"></i>',
				nextArrow: '<i class="fa fa-angle-right slick-next-btn"></i>',
		        slidesToShow: 3,
		        responsive: [
		            {
		              breakpoint: 1200,
		              settings: {
		                slidesToShow: 3,
		                slidesToScroll: 3
		              }
		            },
		            {
		              breakpoint: 991,
		              settings: {
		                slidesToShow: 2,
		                slidesToScroll: 2
		              }
		            },
		            {
		              breakpoint: 480,
		              settings: {
		                slidesToShow: 2,
		                slidesToScroll: 2
		              }
		            }
		          ]
			});
		}
	},
	updated () {

		$('.single-slide-menu').slick('unslick');

	    this.initSlider();

	    $(".single-slide-menu a").unbind('click');

	    $('.single-slide-menu a').on('click',function(e){
		 
		      e.preventDefault();
		     
		      var $href = $(this).attr('href');
		     
		      $('.single-slide-menu a').removeClass('active');
		      $(this).addClass('active');
		     
		      $('.product-details-large .tab-pane').removeClass('active show');
		      $('.product-details-large '+ $href ).addClass('active show');
		     
		})

	},
	mounted () {
		var vm = this;

		bus.$on('quickview', (product) => {

	        if(product) {
	          	this.cover_image = product.cover_image;
	          	this.images = product.images;
	        }

      	});

      	$('#modal-productDetail').on('hidden.bs.modal', function (e) {
		 	vm.images = [];
		 	$('.single-slide-menu').slick('unslick');
		 	vm.cover_image = '';
		})

		bus.$on('variation.refresh', (variant) => {

			if (variant.image) {
				this.variant_image = variant.image;
			}
		
		});

		bus.$on('selection.cleared', () => {

			this.variant_image = '';

		});

	},
	
}

</script>