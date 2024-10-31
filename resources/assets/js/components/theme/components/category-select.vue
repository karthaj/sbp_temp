<template>

    <div ref="setting_container">
	    <div class="form-group px-3">
	        <label :for="getComponentID(configs.id, section)">{{ configs.label }}</label><br>
	        <input type="text" :id="getComponentID(configs.id, section, blockIndex)" class="form-control typeahead sample-typehead" placeholder="Search category" autocomplete="off"> 
	    </div>

	    <div v-if="category" class="d-flex align-items-center justify-content-between px-3">
	    	<img v-if="category.image" :src="category.image" :alt="category.name" class="img-fluid" width="50" height="50">
	    	<i v-else class="aapl-picture"></i>
	    	<span class="ml-2 text-master text-truncate">{{ category.name }}</span>
	    	<a href="#" @click.prevent="remove"><i class="aapl-trash2"></i></a>
	    </div>
    </div>

</template>

<script>
	import editorMixin from '../../../editorMixin'
	import frame from '../../../frame'
	import bus from "../../../bus"

	export default {
		props: ['endpoint', 'configs', 'section', 'block-index'],
		mixins: [editorMixin],
		data () {
			return {
				category: '',
				element: ''
			}
		},
		methods: {

			init () {
				var vm = this;

				var engine = new Bloodhound({
	              remote: {
	                  url: '/merchant/collections/dropdown.json?q=%QUERY%',
	                  wildcard: '%QUERY%'
	              },
	              datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
	              queryTokenizer: Bloodhound.tokenizers.whitespace
	          	});

				var categories = $("#"+this.element).typeahead({
		            hint: true,
		            highlight: true,
		            minLength: 1
		        }, 
	            {
	              source: engine,
	              name: 'categories',
	              displayKey: 'name',
	              limit: 20,
	              templates: {
	                empty: [
	                    '<div class="empty-message">',
	                      'No category found',
	                    '</div>'
	                  ].join('\n'),
	                //suggestion: Handlebars.compile('<div><strong>{{name}}</strong>  ({{qty}})</div>')
	              }
	            }).bind('typeahead:select', function(ev, suggestion) {
	              vm.category = suggestion;
	              vm.setting = suggestion.handle;
	              // frame.contentWindow.postMessage(vm.settings, '*');
	              categories.typeahead('val', '');

	              bus.$emit('preview.changes', {
					section_id: vm.section,
					settings: vm.settings
				  });
				  
	            });
			},
			remove () {
				this.category = '';
				this.setting = '';
				frame.contentWindow.postMessage(this.settings, '*');
			}
		},
		mounted() {
		
			this.element = this.getComponentID(this.configs.id, this.section, this.blockIndex);
              
	        this.init();

	        bus.$on('category.preview', (section) => {
	        	if($(this.$refs.setting_container).closest(`[data-view-id=${section}]`).css('display') == 'inline-block') {
	        		if(this.setting && !this.category) {
				        axios.get(`/merchant/collections/${this.setting}`).then((response) => {
							this.category = response.data;
						})
			        }
	        	}
	        });

	        // if(this.setting) {
	        // 	for(var $i = 0; $i < this.categories.length; $i++) {

		       //  	if(this.categories[$i].handle === this.setting) {
		       //  		this.category = this.categories[$i];
		       //  		break;
		       //  	}

		       //  }
	        // }

		}
	}
</script>