<template>

	<div class="form-group px-3">
        <label :for="getComponentID(configs.id, section)">{{ configs.label }}</label>
           <!-- <textarea :id="getComponentID(configs.id, section)" cols="30" rows="30" class="form-control richtext">
        </textarea> -->
        <redactor v-model="setting" :config="configOptions" @input="contentChanged"/>
  	</div>

</template>

<script>
	import editorMixin from '../../../editorMixin'
	import frame from '../../../frame'
	import bus from "../../../bus"
	import redactor from './redactor.vue'

	export default {
		props: {
			configs: {
				type: Object
			},
			section: {
				type: String
			},
			blockIndex: {
				type: String
			}
		},
		mixins: [editorMixin],
		components: {
			redactor
		},
		data () {
			return {
				configOptions: {}
			}
		},
		methods: {
			contentChanged () {
				// frame.contentWindow.postMessage(this.settings, '*');
				bus.$emit('preview.changes', {
					section_id: this.section,
					settings: this.settings
				});
			}
		},
	}
</script>