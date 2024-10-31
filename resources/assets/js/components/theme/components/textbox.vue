<template>
	
	<div>
		<div class="form-group px-3">
			<label :for="getComponentID(configs.id, section)">{{ configs.label }}</label>
			<span v-if="configs.info" class="ml-2" data-placement="right" data-toggle="tooltip" :data-original-title="configs.info"> 
				<i class="fa fa-question-circle"></i>
	        </span>
			<input type="text" :id="getComponentID(configs.id, section)" class="form-control" 
	       v-model="setting">
	    </div>
	    <hr v-if="configs.divider == 'true'">
	</div>
   
</template>

<script>
	import editorMixin from '../../../editorMixin'
	import frame from '../../../frame'
	import bus from "../../../bus"

	export default {
		props: ['endpoint', 'configs', 'section', 'block-index'],
		mixins: [editorMixin],
		methods: {
			submit () {
				// frame.contentWindow.postMessage(this.settings, '*');
				
				bus.$emit('preview.changes', {
					section_id: this.section,
					settings: this.settings
				});

			}
		},
		mounted() {
			var vm = this;
			var timeout  = null;

			$(function () {
			  $('[data-toggle="tooltip"]').tooltip()
			})

			$(`#${this.getComponentID(this.configs.id, this.section)}`).on('keyup', function(){
		       	clearTimeout(timeout); 
		       	timeout = setTimeout(function () {
			       vm.submit();
			    }, 1000);
			});
		
		}
	}
</script>