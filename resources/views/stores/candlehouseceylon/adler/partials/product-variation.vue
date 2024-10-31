<template>
	<div>
		<div class="b-product_attr_single" v-for="option, index in productOptions" :key="option.id">
		    <ul class="pl-0 list-unstyled clearfix d-inline-block d-flex align-items-center" v-if="option.type === '[CS]'">
		      <li><span class="b-product_attr_title pt-1">{{ option.name }}:</span></li>
		     
		      	<li  v-for="value in option.values" :key="value.id">
		      		<label class="mb-0" :class="{'active': chosen[index] === value.id}">
		      		<input type="radio" name="color" class="input-color" :value="value.id" v-model="chosen[index]" @change="selectedVariant($event, index)" :disabled="value['disabled']">
					<span v-if="value.color" data-toggle="tooltip" title="" :data-original-title="value.name"
		      	class="b-color_attr" :class="{'disabled-control': value['disabled'] }" :style="{'background-color': value.color}"></span>
		      		<span v-else-if="value.pattern" data-toggle="tooltip" title="" :data-original-title="value.name"
		      	class="b-image_attr" :class="{'disabled-control': value['disabled'] }" :style="getPattern(value.pattern)"></span>
		      		</label>
		      	</li>
		    </ul>

		    <ul class="pl-0 list-unstyled clearfix d-inline-block d-flex align-items-center" 
		    v-if="option.type === '[RT]' || option.type === '[RB]'">
	          <li><span class="b-product_attr_title">{{ option.name }}:</span></li>
	          <li v-for="value in option.values" :key="value.id">
	          	<label class="mb-0" :class="{'active': chosen[index] === value.id}">
		      		<input type="radio" name="color" class="input-rectangle" :value="value.id" v-model="chosen[index]" @change="selectedVariant($event, index)" :disabled="value['disabled']">
					<span class="b-size_attr" :class="{'striked-out': value['disabled']}">{{ value.name }}</span>
		      	</label>
	          </li>
	        </ul>
	        <ul class="pl-0 list-unstyled clearfix d-inline-block d-flex align-items-center" 
	        v-if="option.type === '[S]'" >
	        	<li><span class="b-product_attr_title">{{ option.name }}:</span></li>
	        	<li>
	        		<select class="form-control" v-model="chosen[index]" @change="selectedVariant($event, index)">
		        		<option value="">select {{ option.name }}</option>
		        		<option :value="value.id" v-for="value in option.values" :key="value.id" :disabled="value['disabled']">{{ value.name }} <span v-if="value['disabled']">(N/A)</span>
		        		</option>
		        	</select>
	        	</li>
	        </ul>
		</div>
		<button v-if="Object.keys(chosen).length === productOptions.length" type="button" class="btn btn-xs btn-default mb-4" @click.prevent="clear">clear</button>
	</div>
</template>

<script>
	import bus from '../assets/bus'
	const axios = require('axios')
	import settings from '../assets/settings'

	export default {
		props: {
			productHandle: {
				type: String,
				required: true

			},
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
	    	},
	    	loadOptions (options) {
	    		options.forEach((option, index) => {

	    			if(option.type === '[S]') {
	    				this.chosen[index] = '';
	    			}

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

	    		var variant = this.variants.find((variant, i) => {
	    			return _.difference(variant.options, selected).length === 0;
				})

	    		if(variant) {
	    			bus.$emit('variation.refresh', variant);
	    		}

	    	},
	      	selectedVariant (e, index) {

		      	var selected = parseInt(e.target.value);

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

	      	}
	    },
	    created () {

	      this.loadOptions(this.optionSelectors);

	    }
	}
</script>