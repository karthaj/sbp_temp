<template>

	<div class="form-group px-3">
		<label :for="getComponentID(configs.id, section)">{{ configs.label }}</label>
		<span v-if="configs.info" class="ml-2" data-placement="right" data-toggle="tooltip" :data-original-title="configs.info"> 
			<i class="fa fa-question-circle"></i>
        </span>
		<input type="number" :id="getComponentID(configs.id, section)" class="form-control" 
       v-model="setting" @change="submit" min="0">
   </div>
   
</template>

<script>
	import editorMixin from '../../../editorMixin'
	import frame from '../../../frame'
	import bus from "../../../bus"
	
	export default {
		props: ['endpoint', 'configs', 'section', 'process', 'block-index'],
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