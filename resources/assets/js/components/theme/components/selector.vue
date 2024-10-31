<template>

	<div class="form-group px-3">
	    <label :for="getComponentID(configs.id, section)">{{ configs.label }}</label>
	    <select :id="getComponentID(configs.id, section)" class="full-width form-control" v-model="setting" @change="submit">
	    	<option value="">Select</option>
    		<option  v-for="option, i in configs.options" :key="i" :value="option.value">{{ option.label}}</option>
		</select>
	</div>

</template>

<script>
	import editorMixin from '../../../editorMixin'
	import frame from "../../../frame"
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