<template>
	
<form action="#">
    <div v-for="option, index in productOptions" :key="option.id" class="product-variants">
        <div class="product-variants-item">

        	<template v-if="option.type === '[S]'">
        		<span class="control-label">{{ option.name }}:</span>
	            <select v-model="chosen[index]" @change="selectedVariant(index)">
	            	<option value="" data-display="Select">{{ option.name }}</option>
	            	<option :value="`${index},${value.id}`" v-for="value in option.values" :key="value.id" :disabled="value['disabled']">{{ value.name }} <span v-if="value['disabled']">(N/A)</span></option>
	            </select>
        	</template>

        	<template v-if="option.type === '[CS]'">
        		<div class="form-field form-field-options form-field-swatch">
					<div class="form-field-title">{{ option.name }}:</div>
				  	<div class="form-field-control">
				      	<label v-for="value in option.values" :key="value.id" class="swatch-wrap">
					        <input class="form-input swatch-radio" :value="value.id" v-model="chosen[index]" :id="`attribute-${value.id}`" type="radio" :disabled="value['disabled']" @change="selectedVariant(index)">
					        <span class="swatch" data-toggle="tooltip" :data-original-title="value.name">
					            <span v-if="value.pattern" class="swatch-pattern" :class="{'disabled-control': value['disabled'] }" :style="getPattern(value.pattern)"></span>
					            <span v-else class="swatch-color" :class="{'disabled-control': value['disabled'] }" :style="`background-color: ${value.color}`"></span>
					        </span>
				      	</label>
				  	</div>
				</div>
        	</template>

        	<template v-if="option.type === '[RT]'">
        		<div class="form-field form-field-options form-field-rectangle">
				  <div class="form-field-title">{{ option.name }}:</div>
				  <div class="form-field-control">
				      <label v-for="value in option.values" :key="value.id" class="form-label rectangle">
				        <input class="form-input form-rectangle" :value="value.id" v-model="chosen[index]" :id="`attribute-${value.id}`" type="radio" :disabled="value['disabled']" @change="selectedVariant(index)">
				        <span class="rectangle-text form-label-text" :class="{'disabled-control': value['disabled'] }">{{ value.name }}</span>
				      </label>
				  </div>
				</div>
        	</template>

        	<template v-if="option.type === '[RB]'">
        		<div class="form-field form-fleld-options">
        			<div class="form-field-title">{{ option.name }}:</div>
        			<div v-for="value in option.values" :key="value.id" class="custom-control custom-radio custom-control-inline">
					  <input type="radio" :value="value.id" v-model="chosen[index]" :id="`attribute-${value.id}`" class="custom-control-input" :disabled="value['disabled']" @change="selectedVariant(index)">
					  <label class="custom-control-label" :for="`attribute-${value.id}`">{{ value.name }}</label>
					</div>
        		</div>
        	</template>

        </div>
    </div>
    <a href="#" v-if="Object.keys(chosen).length === productOptions.length" @click.prevent="clear"><em>Clear selection</em></a>
</form>


</template>

<script>
	
import bus from '../../assets/js/bus'
import settings from '../../assets/js/settings'

export default {
	props: {
		optionSelectors: {
			type: Array,
			required: true,
		},
		variants: {
			type: Array,
			required: true,
		},
		preorder: {
			type: Boolean,
			required: true,
		},
		backorder: {
			type: Boolean,
			required: true,
		}
	},
	mixins: [settings],
	data () {
		return {
			test: '',
    		chosen: {},
    		productOptions: []
		}
	},
    methods: {
    	getPattern(pattern) {
    		return {
    			backgroundImage: 'url(' + this.shopbox.store.assetsPath + '/pattern/' + pattern + ')'
    		}
    	},
    	clear () {
    		this.chosen = {};
    		this.loadOptions(this.optionSelectors);
    		bus.$emit('selection.cleared');

    		$('select').niceSelect('destroy');
    		$('select').val('');
    		
    		this.iniSelect();
    	},
    	loadOptions (options) {
    		options.forEach((option, index) => {
				if(!this.preorder && !this.backorder) {
					option.values.forEach((value) => {
						value['disabled'] = this.checkVariantAvailability(value.id)
					});
				}
    		});
    
    		this.productOptions = options;
    	},
    	checkVariantAvailability (value) {
			
			var disabled = true;

    		var variants = this.variants.filter((variant) => {
				return variant.options.find((option) => {
					return option === value;
				})
			})
		
    		for (var i = 0; i < variants.length; i++) {
    			if(variants[i].stock) {
    				disabled = false;
    				break;
    			}
    		}
		
			return disabled;

    	},
    	checkVariantAvailabilityForSelectedOption (selected, index) {
    		var variants = this.variants.filter((variant) => {
    			return variant.options.find((option) => {
					return option === selected;
				})
			})

    		variants.forEach((variant) => {

				variant.options.forEach((option) => {
		
					if(option !== selected) {

						this.productOptions.forEach((selector, i) => {

							if(i !== index) {

								selector.values.forEach((value) => {

									if(value.id === option) {

										value['disabled'] = variant.stock == 0 ? true : false;
									}

								})
							}

						})
					}

				})

			})
    	},
    	getVariant () {

    		var selected = Object.values(this.chosen);
    		var variant = this.variants.filter((variant) => {
				return _.difference(variant.options, selected).length === 0;
			})
    
    		if(variant.length) {
    			bus.$emit('variation.refresh', variant[0]);
    		}

    	},
    	update (index, value) {
    		this.$set(this.chosen, index, value);
    		this.selectedVariant(index);
    	},
      	selectedVariant (index) {

	      	var selected = parseInt(this.chosen[index]);
	   
			if(!selected) {

				this.clear();

				return;
			}
			
			if(!this.preorder && !this.backorder) {
				this.checkVariantAvailabilityForSelectedOption(selected, index);
			}

			if(Object.keys(this.chosen).length === this.productOptions.length) {
				this.getVariant();
			}

      	},
      	iniSelect() {
      		var vm = this;

	    	$('select').niceSelect()
	    	.on('change', function (e) {
	    		var data = e.target.value.split(',');

	    		vm.update(parseInt(data[0]), parseInt(data[1]));
	    	});
      	}
	},
	created () {

      this.loadOptions(this.optionSelectors);

    },
    mounted () {
    	
    	this.iniSelect();
    	$('[data-toggle="tooltip"]').tooltip();

    }
}

</script>