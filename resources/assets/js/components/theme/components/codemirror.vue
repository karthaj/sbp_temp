<template>

	<div class="form-group px-3">
		<label :for="getComponentID(configs.id, section)">{{ configs.label }}</label>
		<codemirror ref="myCm" v-model="setting" :options="cmOptions"></codemirror>
    </div>
   
</template>

<script>
	
	import editorMixin from '../../../editorMixin'
	import frame from '../../../frame'
	import bus from "../../../bus"
	import { codemirror } from 'vue-codemirror'
	import 'codemirror/lib/codemirror.css'
  	// Addons
	import 'codemirror/addon/scroll/simplescrollbars.css'
	import 'codemirror/addon/scroll/simplescrollbars.js'
  	import 'codemirror/addon/selection/active-line.js'
  	import 'codemirror/addon/display/autorefresh.js'
  	// language
  	import 'codemirror/mode/javascript/javascript.js'
  	import 'codemirror/mode/css/css.js'
	
	export default {
		props: ['endpoint', 'configs', 'section'],
		mixins: [editorMixin],
		components: {
		    codemirror
		},
		data () {
			return {
				cmOptions: {
					mode: this.configs.mode,
			        tabSize: 4,
			        lineNumbers: true,
			        line: true,
			        scrollbarStyle: "simple",
		          	styleActiveLine: true,
		          	autoRefresh: true
				}
			}
		},
		computed: {
		    codemirror() {
		      return this.$refs.myCm.codemirror
		    }
		}
	}
</script>