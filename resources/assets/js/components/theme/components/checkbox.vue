<template>
	
	<div class="form-group px-3">
    	<div class="checkbox check-info">
    		<input type="checkbox" :id="getComponentID(configs.id, section)" v-model="setting" 
    		@change="submit">
            <label :for="getComponentID(configs.id, section)">{{ configs.label }}</label>
        </div>
    </div>

</template>

<script>
	import editorMixin from '../../../editorMixin'
	import frame from "../../../frame"
	import bus from "../../../bus"
	
	export default {
		props: ['configs', 'section', 'block-index'],
		mixins: [editorMixin],
		methods: {
			submit () {
				
				// frame.contentWindow.postMessage(this.settings, '*');

				bus.$emit('preview.changes', {
					section_id: this.section,
					settings: this.settings
				});
		        
			}
		}
	}
</script>