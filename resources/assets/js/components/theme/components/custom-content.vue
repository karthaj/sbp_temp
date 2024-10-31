<template>

	<div class="form-group px-3">
		<label :for="getComponentID(configs.id, section)">{{ configs.label }}</label>
		<textarea  :id="getComponentID(configs.id, section)" cols="30" rows="10"  v-model="setting" @input="submit"
		class="form-control"></textarea>
    </div>
   
</template>

<script>

	import editorMixin from '../../../editorMixin'
	import frame from '../../../frame'
	import bus from "../../../bus"
	
	export default {
		props: ['configs', 'section', 'block-index'],
		mixins: [editorMixin],
		methods: {
			submit () {
				var vm = this;
				var timeout  = null;

		    	clearTimeout(timeout); 
	            timeout = setTimeout(function () {
		            bus.$emit('preview.changes', {
						section_id: vm.section,
						settings: vm.settings
					});
	            }, 1000);
			}
		}
	}
</script>