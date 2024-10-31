<template>
	<div class="all">
		<div class="slider">
			<div class="owl-carousel owl-theme main">
				<div v-if="coverImage.large" :style="`background-image: url(${coverImage.large});`" class="item-box"></div>
				<div v-if="images.length" v-for="image in images" :key="image.id" :style="`background-image: url(${image.large});`" class="item-box"></div>
			</div>
			<div class="left nonl"><i class="ti-angle-left"></i></div>
            <div class="right"><i class="ti-angle-right"></i></div>
		</div>
		<div class="slider-two">
            <div class="owl-carousel owl-theme thumbs">
                <div v-if="coverImage.large" :style="`background-image: url(${coverImage.small});`" class="item active"></div>
                <div v-if="images.length" v-for="image in images" :key="image.id" :style="`background-image: url(${image.small});`" class="item"></div>
            </div>
            <div class="left-t nonl-t"></div>
            <div class="right-t"></div>
        </div>
	</div>
</template>

<script>
	import bus from '../../assets/js/bus'

	export default	{
		props: {
			images: {
				type: Array,
				default: []
			},
			coverImage: {
				type: Object,
				default: {}
			}
		},
		methods: {
			initSlider () {
				var changeSlide = 4; // mobile -1, desktop + 1
				// Resize and refresh page. slider-two slideBy bug remove
				var slide = changeSlide;
				if ($(window).width() < 600) {
					var slide = changeSlide;
					slide--;
				} else if ($(window).width() > 999) {
					var slide = changeSlide;
					slide++;
				} else {
					var slide = changeSlide;
				}

				$(".main").owlCarousel({
					nav: true,
					items: 1
				});
				$(".thumbs").owlCarousel({
					nav: true,
					margin: 15,
					mouseDrag: false,
					touchDrag: true,
					responsive: {
						0: {
							items: changeSlide - 1,
							slideBy: changeSlide - 1
						},
						600: {
							items: changeSlide,
							slideBy: changeSlide
						},
						1000: {
							items: changeSlide + 1,
							slideBy: changeSlide + 1
						}
					}
				});
				var owl = $(".main");
				owl.owlCarousel();
				owl.on("translated.owl.carousel", function (event) {
					$(".right").removeClass("nonr");
					$(".left").removeClass("nonl");
					if ($(".main .owl-next").is(".disabled")) {
						$(".slider .right").addClass("nonr");
					}
					if ($(".main .owl-prev").is(".disabled")) {
						$(".slider .left").addClass("nonl");
					}
					$(".slider-two .item").removeClass("active");
					var c = $(".slider .owl-item.active").index();
					$(".slider-two .item")
						.eq(c)
						.addClass("active");
					var d = Math.ceil((c + 1) / slide) - 1;
					$(".slider-two .owl-dots .owl-dot")
						.eq(d)
						.trigger("click");
				});
				owl.on('changed.owl.carousel', function(event) {
				    // console.log(event.item.count, event.item.index, event.item.count - 1)
				    // $(event.target).attr('data-index', event.item.count)
				    // owl.trigger('to.owl.carousel', 6)
				});
				$(".right").click(function () {
					$(".slider .owl-next").trigger("click");
				});
				$(".left").click(function () {
					$(".slider .owl-prev").trigger("click");
				});
				$(".slider-two .item").click(function () {
					var b = $(".item").index(this);
					$(".slider .owl-dots .owl-dot")
						.eq(b)
						.trigger("click");
					$(".slider-two .item").removeClass("active");
					$(this).addClass("active");
				});
				var owl2 = $(".thumbs");
				owl2.owlCarousel();
				owl2.on("translated.owl.carousel", function (event) {
					$(".right-t").removeClass("nonr-t");
					$(".left-t").removeClass("nonl-t");
					if ($(".two .owl-next").is(".disabled")) {
						$(".slider-two .right-t").addClass("nonr-t");
					}
					if ($(".thumbs .owl-prev").is(".disabled")) {
						$(".slider-two .left-t").addClass("nonl-t");
					}
				});
				$(".right-t").click(function () {
					$(".slider-two .owl-next").trigger("click");
				});
				$(".left-t").click(function () {
					$(".slider-two .owl-prev").trigger("click");
				});
			},
		},
		updated () {
			//this.initSlider();
		},
		mounted () {
			this.initSlider();

			bus.$on('variation.refresh', (variation) => {
		        if(variation.image) {
		          	$('.main').trigger('add.owl.carousel', [
			          	`<div class="item-box" style="background-image: url(${variation.image.large});"></div>`
		          	], 0);
		          	$('.main').trigger('refresh.owl.carousel');
		        }
	      	});
		}
	}
</script>