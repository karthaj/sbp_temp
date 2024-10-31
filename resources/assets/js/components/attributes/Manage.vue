<template>
	
	<form :action="endpoint" method="post" enctype="multipart/form-data" autocomplete="off" @submit.prevent="save">
		<div class="row">
	        <div class="col-lg-12">
	           	<div class="card card-transparent">
	              	<div class="card-block no-padding">
		                <div class="card card-default">
		                  	<div class="card-block">
		                      	<div class="row">
		                          <div class="col-sm-6 form-group" :class="{'has-error': errors['option_name'] }">
		                              <label>Option Name</label>
		                              <input type="text" id="option_name" class="form-control" :class="{'form-control-danger': errors['option_name'] }"  v-model="form.option_name" aria-describedby="optionNameHelp">
		                              <small id="optionNameHelp" class="form-text text-muted">Used Internally</small>
		                              <div v-if="errors['option_name']" class="form-control-feedback">{{ errors['option_name'][0] }}</div>
		                          </div>
		                          <div class="col-sm-6 form-group" :class="{'has-error': errors['display_name'] }">
		                              <label>Display Name</label>
		                              <input type="text" id="display_name" class="form-control" :class="{'form-control-danger': errors['display_name'] }"  v-model="form.display_name" aria-describedby="displayNameHelp">
		                              <small id="displayNameHelp" class="form-text text-muted">Visible to Customers</small>
		                              <div v-if="errors['display_name']" class="form-control-feedback">{{ errors['display_name'][0] }}</div>
		                          </div>
		                      	</div>
		                      	<div class="row">
		                          	<div class="form-group col-sm-6" :class="{'has-error': errors['display_type'] }">
			                            <label>Display Type</label><br>
			                            <select id="display_type" v-model="form.display_type" class="form-control" :class="{'form-control-danger': errors['display_type'] }">
			                                <option value="">Choose a display style</option>
			                                <option value="swatch">Swatch</option>
			                                <option value="multiple choice">Multiple choice</option>
			                            </select>
			                            <div v-if="errors['display_type']" class="form-control-feedback">{{ errors['display_type'][0] }}</div>
		                          	</div>
		                          	<div class="form-group col-sm-6" :class="{'has-error': errors['display_style'] }" v-if="form.display_type === 'multiple choice'">
		                              <label>Display Style</label><br>
		                              <select id="display_style" v-model="form.display_style" class="form-control" :class="{'form-control-danger': errors['display_style'] }">
		                                  <option value="">Select a style</option>
		                                  <option value="[RT]">Rectangle (good for sizes)</option>
		                                  <option value="[S]">Dropdown</option>
		                                  <option value="[RB]">Radio buttons</option>
		                              </select>
		                              <div v-if="errors['display_style']" class="form-control-feedback">{{ errors['display_style'][0] }}</div>
		                          </div>
		                      	</div>
		                      	<div class="row" v-if="form.display_type === 'multiple choice'">
	                              	<div class="col-sm-12">
		                                <label>List of values</label>
		                                <ul id="sortable">
		                                	<draggable :list="form.values" :options="{'handle': '.DraggableHolder'}" @start="drag=true" @end="drag=false">
			                                    <li class="ui-state-default" v-for="value, index in form.values"> 
			                                    	<div class="row align-items-center">
			                                    		<div class="DraggableHolder">
			                                    			<i class="aapl-menu"></i>
			                                    		</div>
				                                        <div class="col-sm-6">
				                                        	<div class="form-group" :class="{'has-error': errors['values.'+index] }">
					                                        	<input type="text" class="form-control" :class="{'form-control-danger': errors['values.'+index] }" v-model="value.label">
					                                        	<div v-if="errors['values.'+index]" class="form-control-feedback">{{  errors['values.'+index][0] }}</div>
					                                        </div>
				                                        </div>
				                                        <div class="col-sm-2">
				                                            <a href="javascript:;" class="px-1" @click.prevent="addValue"><i class="aapl-plus-circle"></i></a>
				                                            <a v-if="form.values.length > 1" href="javascript:;" class="px-1" @click.prevent="removeValue(index)"><i class="aapl-circle-minus"></i></a> 
				                                        </div>
			                                    	</div>
			                                    </li>
			                                </draggable>
		                                </ul>
	                              	</div>
	                          	</div>
	                          	<div class="row" v-if="form.display_type === 'swatch'">
	                              	<div class="col-sm-12">
		                                <label>Colors / Patterns</label>
		                                <ul id="sortable">
		                                	<draggable :list="form.swatches" :options="{'handle': '.DraggableHolder'}" @start="drag=true" @end="drag=false">
			                                    <li class="ui-state-default" v-for="value, index in form.swatches"> 
			                                    	<div class="row align-items-center">
			                                    		<div class="DraggableHolder">
			                                    			<i class="aapl-menu"></i>
			                                    		</div>
				                                        <div class="col-sm-3">
				                                        	<div class="form-group" :class="{'has-error': errors['swatches.'+index+'.label'] }">
					                                        	<input type="text" class="form-control" :class="{'form-control-danger': errors['swatches.'+index+'.label'] }" placeholder="Name" v-model="value.label">
					                                        	<div v-if="errors['swatches.'+index+'.label']" class="form-control-feedback">{{ errors['swatches.'+index+'.label'][0] }}</div>
					                                        </div>
				                                        </div>
				                                        <div class="col-sm-2">
				                                        	<div class="form-group" :class="{'has-error': errors['swatches.'+index+'.type'] }">
				                                        		<select class="form-control" :class="{'form-control-danger': errors['swatches.'+index+'.type'] }" v-model="value.type">
				                                        			<option value="color">Solid</option>
				                                        			<option value="pattern">Pattern</option>
				                                        		</select>
				                                        		<div v-if="errors['swatches.'+index+'.type']" class="form-control-feedback">{{ errors['swatches.'+index+'.type'][0] }}</div>
				                                        	</div>
				                                        </div>
				                                        <div class="col-sm-4" v-if="value.type === 'color'">
				                                        	<colorPicker @apply="updateColor($event, index)" :color="value.color"></colorPicker>
				                                        </div>
				                                        <div class="col-sm-4" v-else-if="value.type === 'pattern'">
				                                        	<div class="form-group" :class="{'has-error': errors['swatches.'+index+'.image'] }">
				                                        		<input type="file" class="form-control" :class="{'form-control-danger': errors['swatches.'+index+'.image'] }"v-on:change="fileChange($event, index)">
				                                        		<div v-if="errors['swatches.'+index+'.image']" class="form-control-feedback">{{ errors['swatches.'+index+'.image'][0] }}</div>
				                                        	</div>
				                                        </div>
				                                        <div class="col-sm-2">
				                                            <a href="javascript:;" class="px-1" @click.prevent="addSwatch"><i class="aapl-plus-circle"></i></a>
				                                            <a v-if="form.swatches.length > 1" href="javascript:;" class="px-1" @click.prevent="removeSwatch(index)"><i class="aapl-circle-minus"></i></a> 
				                                            <a v-if="editMode && value.pattern" :href="value.pattern" class="px-1" target="_blank"><i class="aapl-picture"></i></a>
				                                        </div>
			                                    	</div>
			                                    </li>
			                         		</draggable>
		                                </ul>
	                              	</div>
	                          	</div>
		                  	</div>
		                </div>
	              	</div>
	            </div>    
	        </div>
	    </div>
	    <div class="container-fluid container-fixed-lg footer action-wrapper">
	    	<div class="small no-margin pull-right sm-pull-reset text-center">
	    		<button type="button" class="btn btn-action-cancel mr-2" :disabled="disabled" @click.prevent="redirect">Cancel</button>
	    		<button type="submit" class="btn btn-action-save" :disabled="disabled">Save</button>
	    	</div>
		</div>
	</form>

</template>

<script>
    import draggable from 'vuedraggable'
    import colorPicker from './partials/color-picker'

    export default {
        components: {
          draggable,
          colorPicker
        },
        data () {
          return {
              form: {
              	option_name: '',
              	display_name: '',
              	display_type: '',
              	display_style: '',
              	values: [],
              	swatches: []
              },
              formData: new FormData(),
              errors: [],
              disabled: false,
              deleted: []
          }
        },
        props: {
        	endpoint: {
        		type: String,
        		required: true
        	},
        	cancelEndpoint: {
        		type: String,
        		required: true
        	},
        	redirectTo: {
        		type: String,
        		required: true
        	},
        	attribute: Object,
        	editMode: {
        		type: Boolean,
        		default: false
        	}
        },
        watch: {
        	'form.display_type': function (value) {

        		this.deleted = [];

        		if(value === 'multiple choice') {

        			if(this.attribute && this.attribute.display_type !== 'swatch') {
        				
        				this.form.swatches = [];
        				this.form.values = [];
        				this.fillForm(this.attribute);

        			}

        			if(this.attribute && this.attribute.display_type !== 'multiple choice') {

        				var list = {
		        		'label': '',
			        	}

	        			this.form.values.push(list);

        			}	

        			if(!this.editMode) {

        				var list = {
		        		'label': '',
			        	}

	        			this.form.values.push(list);

        			}

        		} else if(value === 'swatch') {

        			if(this.attribute && this.attribute.display_type !== 'multiple choice') {

        				this.form.values = [];
        				this.form.swatches = [];
        				this.fillForm(this.attribute);

        			}

        			if(this.attribute && this.attribute.display_type !== 'swatch') {

        				var swatch = {
			        		'label': '',
			        		'type': 'color',
			        		'color': '#000000',
			        		'image': ''
			        	}

			        	this.form.swatches.push(swatch);

        			}

        			if(!this.editMode) { 

        				var swatch = {
			        		'label': '',
			        		'type': 'color',
			        		'color': '#000000',
			        		'image': ''
			        	}

			        	this.form.swatches.push(swatch);

        			}
        			
        		}
        	}
        },
        methods: {
          addValue() {
          	var list = {
        		'label': '',
        	}
          	this.form.values.push(list);
          },
          removeValue(index) {

          	if(this.editMode) {

          		this.deleted.push(this.form.values[index].id);

          	}

          	this.form.values.splice(index, 1);
          },
          save() {
          	this.disabled = true;
          	this.getFormData();
          	axios.post(this.endpoint, this.formData).then((response) => { 
	 			this.errors = [];
	 			localStorage.success = true;
	 			window.location.replace(this.redirectTo);
	        }).catch((error) => {
	            this.errors = error.response.data;
	            this.disabled = false;
	        })
          },
          getFormData () {

          	if(this.editMode) {

          		this.formData.append('option_id', this.attribute.id);
          		this.formData.append('options[]', this.deleted);

          	}

          	this.formData.append('option_name',this.form.option_name);
          	this.formData.append('display_name',this.form.display_name);
          	this.formData.append('display_type',this.form.display_type);
          	this.formData.append('display_style',this.form.display_style);

          	if(this.form.display_type === 'multiple choice') {

          		this.form.values.forEach((value, index) => {

          			if(this.editMode) {
          				this.formData.append('values['+index+'][id]', value.id);
          			}

	          		this.formData.append('values['+index+'][label]', value.label);
	          	})
          	}

          	if(this.form.display_type === 'swatch') {

          		this.form.swatches.forEach((swatch, index) => {

          			if(this.editMode) {
          				this.formData.append('swatches['+index+'][id]', swatch.id);
          			}

	          		this.formData.append('swatches['+index+'][label]', swatch.label);
	          		this.formData.append('swatches['+index+'][type]', swatch.type);
	          		this.formData.append('swatches['+index+'][color]', swatch.color);
	          		this.formData.append('swatches['+index+'][image]', '');

	          	})
          	} 
          
          },
          addSwatch() {

          	var swatch = {};

          	if(this.editMode) {
          		var swatch = {
          			'id': 0,
	        		'label': '',
	        		'type': 'color',
	        		'color': '#000000'
	        	}
          	} else {
          		var swatch = {
	        		'label': '',
	        		'type': 'color',
	        		'color': '#000000'
	        	}
          	}	
          	

          	this.form.swatches.push(swatch);
          },
          removeSwatch(index) {

          	if(this.editMode) {

          		this.deleted.push(this.form.swatches[index].id);

          	}

          	this.form.swatches.splice(index, 1);
          },
          updateColor (color, index) {
          	this.form.swatches[index]['color'] = color;
          },
          fileChange(e, index) {
          
		    this.formData.append('swatches['+index+'][image]', e.target.files[0]);
		    
          },
          fillForm(attribute) {

          	this.form.option_name = attribute.name;
          	this.form.display_name = attribute.display_name;
          	this.form.display_type = attribute.display_type;
          	this.form.display_style = attribute.display_style;

          	if(attribute.display_type === 'multiple choice' && attribute.options.length) {

          		attribute.options.forEach((value, index) => {

          			var list = {
          				'id': value.id,
          				'label': value.name,
          			}

          			this.form.values.push(list);

          		})
          	} else if(attribute.display_type === 'swatch' && attribute.options.length) {

          		attribute.options.forEach((value, index) => {

          			var swatch = {
          				'id': value.id,
          				'label': value.name,
		        		'type': value.type,
		        		'color': value.color,
		        		'pattern': value.pattern
          			}

          			this.form.swatches.push(swatch);

          		})
          	}

          },
          redirect () {
          	window.location.replace(this.cancelEndpoint);
          }
        },
        mounted () {

        	if(this.editMode) {
        		this.fillForm(this.attribute);
        	}

        }
    }

</script>