<template>
	<form action="#">
		<div v-for="option, index in productOptions" :key="option.id" class="row mb-2">
			<label class="col-xl-5 col-lg-5  col-md-6 col-6 pt-0"><strong>{{ option.name }} </strong></label>
			<div class="col-xl-7 col-lg-7 col-md-6 col-6" :class="{'colors': option.type === '[CS]'}">
                <div v-if="option.type === '[S]'" class="custom-select-form" :data-index="index">
                    <select class="wide" v-model="chosen[index]" @change="selectedVariant(index)">
						<option value="" :data-display="`Select ${option.name}`">Select {{ option.name }}</option>
                        <option :value="`${index},${value.id}`" v-for="value in option.values" :key="value.id" :disabled="value['disabled']">{{ value.name }} <span v-if="value['disabled']">(N/A)</span></option>
                    </select>
                </div>
                <div v-if="option.type === '[RB]'" v-for="value in option.values" :key="value.id" class="custom-control custom-radio custom-control-inline">
				  <input type="radio" :value="value.id" v-model="chosen[index]" :id="`attribute-${value.id}`" class="custom-control-input" :disabled="value['disabled']" @change="selectedVariant(index)">
				  <label class="custom-control-label" :for="`attribute-${value.id}`" :class="{'disabled-control': value['disabled']}">{{ value.name }}</label>
				</div>
                <ul v-if="option.type === '[CS]'">
                    <li v-for="value in option.values" :key="value.id">
                    	<input type="radio" :id="`option-${value.id}`" class="input-color" name="color" :value="value.id" v-model="chosen[index]" @change="selectedVariant(index)" :disabled="value['disabled']">
                    	<label :for="`option-${value.id}`">
                    		<span v-if="value.color" class="color" :class="{'disabled-control': value['disabled'], 'active': chosen[index] === value.id }" :style="{'background-color': value.color}" data-toggle="tooltip" :title="value.name" :data-original-title="value.name" ></span>
                    		<span v-else-if="value.pattern" class="color" :class="{'disabled-control': value['disabled'], 'active': chosen[index] === value.id }" :style="getPattern(value.pattern)" data-toggle="tooltip" :title="value.name" :data-original-title="value.name" ></span>
                    	</label>
                    </li>
                </ul>
                <div v-if="option.type === '[RT]'" class="form-field-rectangle">
				  <div v-for="value in option.values" :key="value.id" class="form-field-control">
				  		<input class="form-input form-rectangle" :value="value.id" v-model="chosen[index]" :id="`attribute-${value.id}`" type="radio" :disabled="value['disabled']" @change="selectedVariant(index)">
				      	<label :for="`attribute-${value.id}`" class="form-label rectangle" :class="{'soldout': value['disabled']}">
					        <span class="rectangle-text form-label-text" :class="{'disabled-control': value['disabled']}">{{ value.name }}</span>
					        <img v-if="value['disabled']" class="crossed-out" :src="soldoutPlaceholder" alt="Sold out">
				      	</label>
				  </div>
				</div>
            </div>
		</div>
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
    		chosen: {},
    		productOptions: []
		}
	},
	computed: {
		soldoutPlaceholder () {
			return this.shopbox.store.assetsPath + 'soldout.webp';
		}
	},
    methods: {
    	getPattern(pattern) {
    		return {
    			backgroundImage: 'url(' + this.shopbox.store.patternPath + pattern + ')'
    		}
    	},
    	clear () {
    		this.chosen = {};
    		this.loadOptions(this.optionSelectors);
    		bus.$emit('selection.cleared');

    		$('.custom-select-form select').niceSelect('destroy');
    		$('.custom-select-form select').val('');
    		
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
	      	var selected = parseInt(this.chosen[index]);;
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

	    	$('.custom-select-form select').niceSelect()
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