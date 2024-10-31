<template>
	<div>
		<div class="form-group px-3">
			<label>{{ configs.label }}</label>
			<div class="input-group color-picker" ref="colorpicker">
				<input type="text" class="form-control" v-model="setting" @focus="showPicker()" @input="updateFromInput" />
				<span class="input-group-addon color-picker-container">
					<span class="current-color" :style="'background-color: ' + setting" @click="togglePicker()"></span>
				</span>
				<button type="button" class="ml-2 btn btn-info btn-xs" @click.prevent="submit">Apply</button>
			</div>
			<color-picker :value="colors" @input="updateFromPicker" v-if="displayPicker" style="width: 216px"/>
		</div>
		<hr v-if="configs.divider == 'true'">
	</div>

</template>

<script>
	import editorMixin from '../../../editorMixin'
	import frame from '../../../frame'
	import bus from "../../../bus"
	import { Chrome } from 'vue-color'

	export default {
		props: {
			endpoint: {
				type: String,
				default: ''
			},
			configs: {
				type: Object,
				required: true
			},
			section: {
				type: String
			}
		},
		mixins: [editorMixin],
		data () {
			return {
				colors: {
					hex: '#000000',
				},
				displayPicker: false,
			}
		},
		components: {
		    'color-picker': Chrome
		},
		watch: {
			colorValue(val) {
				if(val) {
					this.updateColors(val);
					this.$emit('input', val);
				}
			}
		},
		methods: {
			submit () {
				this.hidePicker();

				if(this.endpoint) {

					axios.post(this.endpoint, {
			             settings: this.settings,
			        }).then((response) => { 
			        	this.loadStyles(response.data);
			        }).catch((error) => {
			            console.log(error)
			        })

				} else {

					// frame.contentWindow.postMessage(this.settings, '*');

					bus.$emit('preview.changes', {
						section_id: this.section,
						settings: this.settings
					});

				}
				
			},
			setColor(color) {
				this.updateColors(color);
			},
			updateColors(color) {
				if(color.slice(0, 1) == '#') {
					this.colors = {
						hex: color
					};
				}
				else if(color.slice(0, 4) == 'rgba') {
					var rgba = color.replace(/^rgba?\(|\s+|\)$/g,'').split(','),
						hex = '#' + ((1 << 24) + (parseInt(rgba[0]) << 16) + (parseInt(rgba[1]) << 8) + parseInt(rgba[2])).toString(16).slice(1);
					this.colors = {
						hex: hex,
						a: rgba[3],
					}
				}
			},
			showPicker() {
				document.addEventListener('click', this.documentClick);
				this.displayPicker = true;
			},
			hidePicker() {
				document.removeEventListener('click', this.documentClick);
				this.displayPicker = false;
			},
			togglePicker() {
				this.displayPicker ? this.hidePicker() : this.showPicker();
			},
			updateFromInput() {
				this.updateColors(this.setting);
			},
			updateFromPicker(color) {
				this.colors = color;
				if(color.rgba.a == 1) {
					this.setting = color.hex;
				}
				else {
					this.setting = 'rgba(' + color.rgba.r + ', ' + color.rgba.g + ', ' + color.rgba.b + ', ' + color.rgba.a + ')';
				}
			},
			documentClick(e) {
				var el = this.$refs.colorpicker,
					target = e.target;
				if(el !== target && !el.contains(target)) {
					//this.hidePicker()
				}
			}
		}
	}
</script>