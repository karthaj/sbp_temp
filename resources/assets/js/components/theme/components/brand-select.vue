<template>
	<div>
	    <div class="form-group px-3">
	        <label :for="getComponentID(configs.id, section)">{{ configs.label }}</label><br>
	        <input type="text" :id="getComponentID(configs.id, section, blockIndex)" class="form-control typeahead sample-typehead" placeholder="Search brand" autocomplete="off"> 
	    </div>

	    <div v-if="brand" class="d-flex align-items-center px-3">
	    	<img v-if="brand.image" :src="brand.image" :alt="brand.name" class="img-fluid" width="50" height="50">
	    	<i v-else class="aapl-picture"></i>
	    	<span class="ml-2 text-master text-truncate">{{ brand.name }}</span>
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
				brand: '',
				element: ''
			}
		},
		methods: {
			init () {
				var vm = this;

				var engine = new Bloodhound({
	              remote: {
	                  url: '/merchant/brands/dropdown.json?q=%QUERY%',
	                  wildcard: '%QUERY%'
	              },
	              datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
	              queryTokenizer: Bloodhound.tokenizers.whitespace
	          	});

				var brands = $("#"+this.element).typeahead({
		            hint: true,
		            highlight: true,
		            minLength: 1
		        }, 
	            {
	              source: engine,
	              name: 'brands',
	              displayKey: 'name',
	              limit: 20,
	              templates: {
	                empty: [
	                    '<div class="empty-message">',
	                      'No brands found',
	                    '</div>'
	                  ].join('\n'),
	                //suggestion: Handlebars.compile('<div><strong>{{name}}</strong>  ({{qty}})</div>')
	              }
	            }).bind('typeahead:select', function(ev, suggestion) {
	              vm.brand = suggestion;
	              vm.setting = suggestion.handle;
	              brands.typeahead('val', '');

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

	        bus.$on('brand.preview', (section) => {
	        	if(this.setting && section == this.section && !this.brand) {
			        axios.get(`/merchant/brands/${this.setting}`).then((response) => {
						this.brand = response.data;
					})
		        }
	        });
		}
	}
</script>