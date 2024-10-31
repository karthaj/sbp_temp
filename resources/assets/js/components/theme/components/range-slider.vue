<template>

	<div class="form-group px-3">
	    <label :for="configs.id" class="mb-5">{{ configs.label}}</label>
	    <div :id="configs.id + '_slider'" class="bg-master range-slider m-b-10"></div>
		<input type="hidden" :id="configs.id +'_value'" name="setting['.$setting['id'].']" value="'.$config[$setting['id']].'">
	</div>

</template>

<script>
	export default {
		props: ['configs'],
		data () {
			return {
				//
			}
		},
		methods: {
			//
		},
		mounted() {
			

			$('#'+this.configs.id + '_slider').noUiSlider({
	            start: 40,
	            connect: "lower",
	            step: this.configs.step,
	            range: {
	                'min': this.configs.min,
	                'max': this.configs.max
	            }
	        });

	        $('#'+this.configs.id + '_slider').Link('lower').to('-inline-<div class="tooltip fade top in" style="top: -33px;left: -14px;opacity: 0.7;"></div>', function(value) {
	            // The tooltip HTML is 'this', so additional
	            // markup can be inserted here.
	            $(this).html(
	                '<div class="tooltip-inner">' +
	                '<span>' + value + '</span>' +
	                '</div>'
	            );
	        });
			
		}
	}
</script>