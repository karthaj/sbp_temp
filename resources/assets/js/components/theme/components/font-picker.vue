<template>

	<div ref="setting_container">
	    <div class="form-group px-3">
	        <label :for="getComponentID(configs.id, section)">{{ configs.label }}</label><br>
	        <div v-if="font" class="d-flex align-items-center justify-content-between px-3">
		    	<h6 :style="preview">{{ font.name }}</h6>
		    </div>
	        <input type="text" :id="getComponentID(configs.id, section)" class="form-control typeahead sample-typehead" placeholder="Search font" autocomplete="off"> 
	    </div>
    </div>

</template>

<script>
	import editorMixin from '../../../editorMixin'
	import frame from '../../../frame'
	import bus from "../../../bus"

	export default {
		props: ['endpoint', 'configs', 'fonts', 'section'],
		mixins: [editorMixin],
		data () {
			return {
				font: '',
				element: ''
			}
		},
		computed: {
			preview () {
				return {
					fontFamily: this.formatFont(this.font.family, this.font.fallbacks),
					fontWeight: this.font.variation
				}
			},
		},
		methods: {
			submit () {
				var formData = {};

				axios.post(this.endpoint, {
		             settings: this.settings,
		        }).then((response) => { 
		        	this.loadStyles(response.data);
		        }).catch((error) => {
		            console.log(error)
		    	})
		    
		    },
		    init () {
				var vm = this;

				var engine = new Bloodhound({
	              remote: {
	                  url: '/merchant/fonts/dropdown.json?q=%QUERY%',
	                  wildcard: '%QUERY%'
	              },
	              datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
	              queryTokenizer: Bloodhound.tokenizers.whitespace
	          	});

				var fonts = $("#"+this.element).typeahead({
		            hint: true,
		            highlight: true,
		            minLength: 0
		        }, 
	            {
	              source: engine,
	              name: 'fonts',
	              displayKey: 'name',
	              limit: 20,
	              templates: {
	                empty: [
	                    '<div class="empty-message">',
	                      'No font found',
	                    '</div>'
	                  ].join('\n'),
	                suggestion: Handlebars.compile(`<div><p style="font-family: {{family}}; font-weight: {{ variation }}">{{name}}</p></div>`)
	              }
	            }).bind('typeahead:select', function(ev, suggestion) {
	              vm.font = suggestion;
	              vm.setting = suggestion.handle;
	              vm.submit();
	              fonts.typeahead('val', '');
	            });
			},
			formatFont (font, fallbacks) {
				return font +','+ fallbacks;
			},
			getSelectedFont () {
				axios.get(`/merchant/fonts/${this.setting}/dropdown.json`).then((response) => { 
		        	this.font = response.data;
		        }).catch((error) => {
		            console.log(error.response)
		    	})
			}
		},
		mounted () {
		
			this.element = this.getComponentID(this.configs.id, this.section);
              
	        this.init();


	        bus.$on('font.preview', (section) => {

	        	if($(this.$refs.setting_container).closest(`[data-view-id=${section}]`).css('display') == 'inline-block') {
    		     	if(this.setting && !this.font) {
						axios.get(`/merchant/fonts/${this.setting}/dropdown.json`).then((response) => { 
				        	this.font = response.data;
				        })
			        }
	        	} 

	        });

	        // if(this.setting) {
	        // 	// this.getSelectedFont();
	        // }

		}
	}
</script>