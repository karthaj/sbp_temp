<template>
<form action="#" @submit.prevent="submit">
  <div class="row">
      <div class="col-sm-6 form-group" v-bind:class="{'has-danger': errors.option_name}">
          <label>Option Name</label>
          <input type="text" id="option_name" class="form-control" v-bind:class="{'form-control-danger': errors.option_name}" required 
          v-model="option_name">
          <div class="form-control-feedback" v-if="errors.option_name">{{ errors.option_name[0] }}</div>
      </div>
      <div class="col-sm-6 form-group" v-bind:class="{'has-danger': errors.option_name}">
          <label>Display Name</label>
          <input type="text" id="display_name" class="form-control" v-bind:class="{'error': errors.display_name}" required name="display_name" v-model="display_name">
          <label id="display_name-error" class="error" for="display_name-error" v-if="errors.display_name">{{ errors.display_name[0] }}</label>
      </div>
  </div>
  <div class="row">
      <div class="form-group col-sm-4">
          <label for="display_type">Display Type</label><br>
          <select id="display_type" class="cs-select cs-skin-slide form-control"v-bind:class="{'error': errors.display_type}" v-model="group_type" required>
              <option :value="group_type">{{ group_type }}</option>
          </select>
          <label id="display_type-error" class="error" for="display_type-error" v-if="errors.display_type">{{ errors.display_type[0] }}</label>
      </div>
      <div class="form-group col-sm-5" v-if="group_type === 'multiple choice'">
          <label>Display Style </label><br>
          <select name="display_style" id="display_style" class="cs-select cs-skin-slide form-control" v-bind:class="{'error': errors.display_style}" v-model="display_type" required>
              <option value="[RT]">Rectangle (good for sizes)</option>
              <option value="[S]">Dropdown</option>
              <option value="[RB]">Radio buttons</option>
          </select>
           <label id="display_style-error" class="error" for="display_style-error" v-if="errors.display_style">{{ errors.display_style[0] }}</label>
      </div>
  </div>
  <div class="row" v-if="group_type === 'multiple choice'">
      <div class="form-group col-sm-12">
        <label>List of values</label>
        <ul id="sortable">
          <draggable :list="values" :options="{'handle': '.DraggableHolder'}" @start="drag=true" @end="drag=false" @change="update" class="draggable">
            <template v-for="value, index in values">
              <li class="ui-state-default mb-3 col-sm-12 px-0">
                <div class="DraggableHolder"></div>
                <input type="text" class="draggable-input"  v-bind:class="{'error': errors['values.'+ index +'.name']}" v-model="values[index].name">
                <label class="error"  v-if="errors['values.'+ index +'.name']">{{ errors['values.'+ index +'.name'][0] }}</label>
                ({{ value.sort_order }})
                <span>
                      <a href="#" class="row-edit-add px-1" @click.prevent="addValue" title=""><i class="pg-plus_circle"></i></a>
                      <a href="#" class="row-del px-1" @click.prevent="remove(values[index].id)"><i class="pg-minus_circle"></i></a>
                </span>
              </li>
            </template>
          </draggable>
        </ul>
      </div>
  </div>
  <div class="row swatch" v-else-if="group_type === 'swatch'">
    <div class="form-group col-sm-12">
      <div class="col-sm-12">
          <label>Colors / Patterns</label> 
      </div>
      <div id="swatchConfig">
        <draggable :list="values" :options="{'handle': '.DraggableHolder'}" @start="drag=true" @end="drag=false" @change="update" class="draggable">
          <template v-for="value, index in values">
            <div class="row align-items-center mb-2 swatch-item">
              <div class="DraggableHolder"></div>
              <div class="col-lg-3 col-md-3 col-sm-3  col-10 pl-0">
                <input type="text" class="form-control" placeholder="Swatch name" v-model="values[index].name">
              </div>
              <div class="col-lg-2 col-md-2 col-sm-3 col-12">
                <select ref="uniqueName" class="form-control" :data-id="index">
                    <option {{ values[index].color ? 'selected' : '' }}  value="color" >Solid</option>
                    <option {{ values[index].pattern ? 'selected' : '' }} value="pattern" >Pattern</option>
                </select>
              </div>
                  <template v-if="values[index].color">
                      <div class="col-sm-4 input-group colorpicker-component" :data-color="values[index].color" data-init-plugin="colorpicker">
                        <input type="text" class="form-control color-picker" name="swatch_color[]" v-model="values[index].color">
                        <span class="input-group-addon">
                          <i></i>
                        </span>
                      </div>
                      <span class="col-lg-2 col-md-2 col-sm-2 col-5">
                            <a href="javascript:;" class="row-edit-add-swatch px-1" title="" @click.prevent="addValue"><i class="pg-plus_circle"></i></a>
                            <a href="javascript:;" class="row-del-swatch px-1" title="" @click.prevent="remove(values[index].id)"><i class="pg-minus_circle"></i></a>
                      </span>
                  </template>
                  <template v-else-if="values[index].pattern">
                      <div class="col-lg-4 col-md-5 swatch-pattern">
                          <input type="file" v-on:change="fileChange($event, index)">
                      </div>
                      <span class="col-lg-1 col-md-1">
                         <a href="javascript:;" class="row-add-swatch px-1" title=""><i class="pg-plus_circle"></i></a>
                         <a href="javascript:;" class="row-del-swatch px-1" title="" @click.prevent="remove(values[index].id)"><i class="pg-minus_circle"></i></a>
                      </span>
                      <a :href="'/storage/sharun/pattern'+values[index].pattern" target="_blank" v-if="values[index].pattern != null"><i class="pg-image"></i></a>
                  </template>
            </div>
          </template>
        </draggable>
      </div>
    </div>  
  </div>
<div class="container-fluid container-fixed-lg footer action-wrapper">
    <div class="small no-margin pull-right sm-pull-reset text-center">
      <a :href="indexuri" class="btn btn-default btn-default-custom mr-2" >Cancel</a>
      <button class="btn btn-custom-v1" type="submit">Save</button>
    </div>
</div>
</form>
</template>

<script>
    import draggable from 'vuedraggable'
    export default {
        components: {
          draggable
        },
        data () {
          return {
              attribute: '',
              option_name: '',
              display_name: '',
              group_type: '',
              display_type: '',
              swatch_type: 'color',
              values: [],
              errors: [],
              sort_order: '',
              deleted_values: [],
              pattern: [],
              fileData: new FormData(),
              deleted_pattern: []
          }
        },
        props: [
          'data', 'adduri', 'indexuri'
        ],
        methods: {
          submit () {  
            //console.log(this.option_name)
            this.fileData.append('option_name',this.option_name)
            this.fileData.append('display_name',this.display_name)
            this.fileData.append('display_style',this.display_type)
            this.fileData.append('display_type',this.group_type)
            this.fileData.append('values',JSON.stringify(this.values))
            this.fileData.append('deleted_values[]',this.deleted_values)
            this.fileData.append('pattern[]',this.pattern)
            this.fileData.append('deleted_pattern[]',this.deleted_pattern)
            axios.post('/merchant/attributes/'+this.data.id, this.fileData).then((response) => { 
              this.message();
              this.loadData(response);
              this.deleted_pattern = []; 
              this.pattern = [];  
              this.deleted_values = [];
            }).catch((error) => {
              this.errors = error.response
            })
          },
          update (e) {
            this.values.map((value, index) => { 
              value.sort_order = index + 1
            })
          },
          loadData (response) { 
            this.attribute = this.data.id
            this.option_name = response.data.attribute.name
            this.display_name = response.data.attribute.public_name
            this.group_type = response.data.attribute.group_type
            this.display_type = response.data.attribute.display_style
            this.values = response.data.attribute.options
          },
          message () {
            $('.page-content-wrapper').pgNotification({
                  style: 'simple',
                  message: 'Attribute saved!',
                  position: 'top-right',
                  timeout: 5000,
                  type: 'success'
            }).show();
          },
          addValue () {
              axios.post(this.adduri, {
                sort_order: this.sort_order + 1,
                attribute: this.attribute
              }).then((response) => { 
                this.loadData(response)
                this.sort_order += 1  
                this.initColorpicker()
              }).catch((error) => {
                console.error
              })
          },
          remove (id) { 
             if(this.sort_order !== 1) {
                this.deleted_values.push(id)
                this.sort_order -= 1
             }
          },
          initColorpicker () { 
            console.log('dasdsas')
              $(".colorpicker-component").colorpicker();
          },
          fileChange (e, index) {
            this.fileData.append('image[]', e.target.files[0])
            this.values[index].pattern = this.values[index].id
            this.pattern.push(this.values[index].id)
            this.deleted_pattern = []
          },
          swatchType (type, index) {
            if(type == 'color') {
              this.deleted_pattern.push(this.values[index].id)
              this.values[index].pattern = ''
              this.pattern = []
            }
          },
          startDateChanged (e) {
            this.start_date = e.srcElement.value
          },
          endDateChanged (e) {
            this.end_date = e.srcElement.value
          }  

        },
        mounted() {
            this.attribute = this.data.id
            this.option_name = this.data.name
            this.display_name = this.data.public_name
            this.group_type = this.data.group_type
            this.display_type = this.data.display_style
            this.values = this.data.options
            this.sort_order = this.values.length 
  
        },
    }

</script>
