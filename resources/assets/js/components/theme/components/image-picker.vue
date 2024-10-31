<template>
	<div>
		<div v-if="errors['image']" class="alert alert-danger" role="alert">
          <button class="close" data-dismiss="alert"></button>
          <p>{{ errors['image'][0] }}</p>
        </div>
		<div class="form-group px-3 styled-file-input">
			<label class="mb-2" for="configs.id">{{ configs.label }}</label>
			<span v-if="configs.info" class="ml-2" data-placement="right" data-toggle="tooltip" :data-original-title="configs.info"> 
				<i class="fa fa-question-circle"></i>
	        </span>
	        <br>
			<div class="btn button">
		        <input type="file" class="form-control" :id="getComponentID(configs.id, section)" @change="fileChange" style="overflow: hidden;">
		        <label class="mb-0">Upload image</label>
		    </div>
		</div>
	    <div class="form-group px-3">
	        <img class="img-fluid img-thumbnail placeholder-image" :src="source">
	    </div>
	    <div v-if="checkImageExists()" class="form-group text-center">
	    	<button type="button" class="btn-action-delete" @click.prevent="destroy">Delete</button>
	    </div>
	</div>
</template>

<script>
	import editorMixin from '../../../editorMixin'
	import { mapGetters } from 'vuex'
	import frame from '../../../frame'
	import bus from "../../../bus"

	export default {
		props: ['theme-id', 'placeholder', 'configs', 'section', 'image-path', 'block-index'],
		mixins: [editorMixin],
		data () {
			return {
				filename: '',
				errors: [],
				preview: ''
			}
		},
		computed: {
			source: {
			    get: function () {
			    	if(this.preview) {
			    		return this.preview;
			    	} else if(this.setting) {
			    		return this.imagePath + this.setting;
			    	}

					return this.placeholder;

			    },
			    set: function (image) {

			      this.setting = image;

			    }
			}
		},
		methods: {
			'fileChange': function (e) {
				var vm = this;
				var reader = new FileReader;

                reader.onload = function (e) {
                    vm.preview = e.target.result;
                };

                reader.readAsDataURL(e.target.files[0]);

			    axios.post(`/theme/${this.themeId}/uploads`, this.packageUploads(e)).then((response) => {
			    	this.errors = [];
			    	this.source = response.data.image;
			    	this.preview = "";
			    	// frame.contentWindow.postMessage(this.settings, '*');

			    	bus.$emit('preview.changes', {
						section_id: this.section,
						settings: this.settings
					});
			    	
			    }).catch((error) => {
			        this.errors = error.response.data;
			    })
		    },
		    'packageUploads': function(e) {
		      	let fileData = new FormData();
		      	fileData.append('image', e.target.files[0]);
		      	return fileData;
		    },
		    destroy () {

				this.setting = "";
				this.preview = "";
				// frame.contentWindow.postMessage(this.settings, '*');
				bus.$emit('preview.changes', {
					section_id: this.section,
					settings: this.settings
				});
		    },
		    checkImageExists() {
		    	
				if(this.setting) {
					return true;
				}

				return false;
		    }
		}
	}
</script>