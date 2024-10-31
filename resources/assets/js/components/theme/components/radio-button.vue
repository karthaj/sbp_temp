<template>
	
   	<div class="form-group px-3">
        <label>{{ configs.label }}</label>
    	<div class="radio radio-info" v-if="configs.options.length" v-for="option in configs.options">
    		<input type="radio" :value="option.value" :id="getComponentID(configs.id, section) +'_'+ option.value" v-model="setting" @change="submit">
    		<label :for="getComponentID(configs.id, section) +'_'+ option.value">{{ option.label }}</label>
    	</div>          
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