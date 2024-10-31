<template>
	<div ref="setting_container">
	    <div class="form-group px-3">
	        <label :for="getComponentID(configs.id, section)">{{ configs.label }}</label><br>
	        <input type="text" :id="getComponentID(configs.id, section, blockIndex)" class="form-control typeahead sample-typehead" placeholder="Search product" autocomplete="off"> 
	    </div>

	    <div v-if="product" class="d-flex align-items-center px-3">
	    	<img v-if="product.image" :src="product.image" :alt="product.name" class="img-fluid" width="50" height="50">
	    	<i v-else class="aapl-picture"></i>
	    	<span class="ml-2 text-master text-truncate">{{ product.name }}</span>
	    </div>
    </div>

</template>

<script>
	import editorMixin from '../../../editorMixin'
	import frame from '../../../frame'
	import bus from "../../../bus"

	export default {
		props: ['configs', 'section', 'block-index'],
		mixins: [editorMixin],
		data() {
			return {
				product: '',
				element: ''
			}
		},
		methods: {
			init () {
				var vm = this;

				var engine = new Bloodhound({
	              remote: {
	                  url: '/merchant/products/dropdown.json?q=%QUERY%',
	                  wildcard: '%QUERY%'
	              },
	              datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
	              queryTokenizer: Bloodhound.tokenizers.whitespace
	          	});

				var products = $("#"+this.element).typeahead({
		            hint: true,
		            highlight: true,
		            minLength: 1
		        }, 
	            {
	              source: engine,
	              name: 'products',
	              displayKey: 'name',
	              limit: 20,
	              templates: {
	                empty: [
	                    '<div class="empty-message">',
	                      'No products found',
	                    '</div>'
	                  ].join('\n'),
	                //suggestion: Handlebars.compile('<div><strong>{{name}}</strong>  ({{qty}})</div>')
	              }
	            }).bind('typeahead:select', function(ev, suggestion) {
	              vm.product = suggestion;
	              vm.setting = suggestion.handle;
	              // frame.contentWindow.postMessage(vm.settings, '*');
	              products.typeahead('val', '');

	              bus.$emit('preview.changes', {
					section_id: vm.section,
					settings: vm.settings
				  });
	            });
			}
		},
		mounted() {
		
			this.element = this.getComponentID(this.configs.id, this.section, this.blockIndex);
              
	        this.init();

	        bus.$on('product.preview', (section) => {
	        	if($(this.$refs.setting_container).closest(`[data-view-id=${section}]`).css('display') == 'inline-block') {
	        		if(this.setting && !this.product) {
				        axios.get(`/merchant/products/${this.setting}`).then((response) => {
							this.product = response.data;
							if(this.blockIndex) {
								bus.$emit('thumbnail.preview', {
									blockId: this.blockIndex,
									block: this.settings.sections[this.section]['blocks'][this.blockIndex],
									product: this.product
								});
							}
						})
			        }
	        	}
	        });

	        // if(this.setting) {
	        // 	for(var $i = 0; $i < this.products.length; $i++) {

		       //  	if(this.products[$i].handle === this.setting) {
		       //  		this.product = this.products[$i];
		       //  		break;
		       //  	}

		       //  }
	        // }

		}
	}
</script>