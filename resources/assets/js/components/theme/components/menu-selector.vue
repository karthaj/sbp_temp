<template>

	<div ref="setting_container">
	    <div class="form-group px-3">
	        <label :for="getComponentID(configs.id, section)">{{ configs.label }}</label><br>
	        <input type="text" ref="linklist" :id="getComponentID(configs.id, section, blockIndex)" class="form-control typeahead sample-typehead" placeholder="Search menu" autocomplete="off"> 
	    </div>

	    <div v-if="menu" class="d-flex align-items-center justify-content-between px-3">
	    	<span class="ml-2 text-master text-truncate">{{ menu.name }}</span>
	    	<a href="#" @click.prevent="remove"><i class="aapl-trash2"></i></a>
	    </div>
    </div>

</template>

<style>
	
.tt-menu {
	position: relative !important;
}

</style>

<script>
	import editorMixin from '../../../editorMixin'
	import frame from '../../../frame'
	import bus from "../../../bus"
	
	export default {
		props: ['endpoint', 'configs', 'section', 'block-index'],
		mixins: [editorMixin],
		data () {
			return {
				menu: '',
				element: ''
			}
		},
		methods: {
			init () {
				var vm = this;

				var engine = new Bloodhound({
	              remote: {
	                  url: '/merchant/linklists/dropdown.json?q=%QUERY%',
	                  wildcard: '%QUERY%'
	              },
	              datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
	              queryTokenizer: Bloodhound.tokenizers.whitespace
	          	});

				var menus = $(this.$refs.linklist).typeahead({
		            hint: true,
		            highlight: true,
		            minLength: 1
		        }, 
	            {
	              source: engine,
	              name: 'menus',
	              displayKey: 'name',
	              limit: 20,
	              templates: {
	                empty: [
	                    '<div class="empty-message">',
	                      'No menu found',
	                    '</div>'
	                  ].join('\n'),
	                //suggestion: Handlebars.compile('<div><strong>{{name}}</strong>  ({{qty}})</div>')
	              }
	            }).bind('typeahead:select', function(ev, suggestion) {
	              vm.menu = suggestion;
	              vm.setting = suggestion.handle;
	              // frame.contentWindow.postMessage(vm.settings, '*');
	              menus.typeahead('val', '');

	              bus.$emit('preview.changes', {
					section_id: vm.section,
					settings: vm.settings
				  });

	            });
			},
			remove () {
				this.menu = '';
				this.setting = '';
				// frame.contentWindow.postMessage(this.settings, '*');
			 	bus.$emit('preview.changes', {
					section_id: this.section,
					settings: this.settings
			  	});
			}
		},
		mounted() {
		
			this.element = this.getComponentID(this.configs.id, this.section, this.blockIndex);
              
	        this.init();

	        bus.$on('menu.preview', (section) => {

	        	if($(this.$refs.setting_container).closest(`[data-view-id=${section}]`).css('display') == 'inline-block') {
    		     	if(this.setting && !this.menu) {
				        axios.get(`/merchant/linklists/${this.setting}`).then((response) => {
							this.menu = response.data;
						})
			        }
	        	} 

	        });

		}
	}
</script>