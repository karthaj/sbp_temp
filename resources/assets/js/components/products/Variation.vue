<template>
<div>
    <div class="row">
      <div class="col px-0">
          <p>Add variants if this product comes in multiple versions, like different sizes or colors.</p>
      </div>
    </div>
    <div class="row align-items-end">
      <div class="col-sm-6 form-group">
          <label for="variation">Select variations</label>
          <select id="" class="form-control" v-model="option_set" name="option_set">
            <template v-if="variations">
              <option  v-for="variation in store_variations" :value="variation.id">{{ variation.name }}</option>    
            </template>
          </select>
      </div>
      <div class="col-sm-4 form-group">
        <button v-if="option_set" type="button" class="btn btn-custom-v1 btn-block" @click.prevent="generate">Generate</button>
        <button v-else type="button" class="btn btn-custom-v1 btn-block" @click.prevent="generate" disabled>Generate</button>
      </div>
    </div>
    <div class="row" v-if="loading">
      <div class="col-md-12 text-center">
        <div class="m-t-45 m-b-45">
          <img :src="loader" alt="loader">
        </div>
      </div>
    </div>
    <!-- start variation table -->
    <div v-if="attributes.length">
      <hr>
      <strong>Bulk Editor</strong>
      <div class="row">
    
          <div class="col-sm-2 form-group" :class="{'has-danger': errors['variation.0.cost_price'] }">
            <label for="cost_price">cost price</label> 
            <input type="text" id="cost_price" class="form-control" :class="{'form-control-danger': errors['variation.0.cost_price'] }" v-model="cost_price">
            <div class="form-control-feedback" v-if="errors['variation.0.cost_price']">{{ errors['variation.0.cost_price'][0] }} </div>
          </div>

          <div class="col-sm-2 form-group" :class="{'has-danger': errors['variation.0.selling_price'] }">
            <label for="selling_price">selling price</label> 
            <input type="text" id="selling_price" class="form-control" :class="{'form-control-danger': errors['variation.0.selling_price'] }" v-model="selling_price">
            <div v-if="errors['variation.0.selling_price']" class="form-control-feedback">{{ errors['variation.0.selling_price'][0] }}</div>
          </div> 

          <div class="col-sm-2 form-group" :class="{'has-danger': errors['variation.0.special_price'] }">
            <label for="special_price">special price</label> 
            <input type="text" id="variation_special_price" class="form-control" :class="{'form-control-danger': errors['variation.0.special_price'] }" v-model="special_price">
            <div v-if="errors['variation.0.special_price']" class="form-control-feedback">{{ errors['variation.0.special_price'][0] }}</div>
          </div> 
      </div>
      <div class="row">
        <div class="col-sm-3 form-group" :class="{'has-danger': errors['variation.0.special_active_date'] }">
          <label>special active date</label> 
          <date-picker @apply="onActiveDateChange" placeholder="Active on"></date-picker>
          <div v-if="errors['variation.0.special_active_date']" class="form-control-feedback">{{ errors['variation.0.special_active_date'][0] }}</div>
        </div>

        <div class="col-sm-3 form-group" :class="{'has-danger': errors['variation.0.special_active_time'] }">
          <label>special active time</label> 
          <time-picker @applyTime="onActiveTimeChange" placeholder="Active time"></time-picker>
          <div v-if="errors['variation.0.special_active_time']" class="form-control-feedback">{{ errors['variation.0.special_active_time'][0] }}</div>
        </div>

        <div class="col-sm-3 form-group" :class="{'has-danger': errors['variation.0.special_end_date'] }">
          <label>special end date</label> 
          <date-picker @apply="onEndDateChange" placeholder="End on"></date-picker>
          <div v-if="errors['variation.0.special_end_date']" class="form-control-feedback">{{ errors['variation.0.special_end_date'][0] }}</div>
        </div>

        <div class="col-sm-3 form-group" :class="{'has-danger': errors['variation.0.special_end_time'] }">
          <label>special end time</label> 
          <time-picker @applyTime="onEndTimeChange" placeholder="End time"></time-picker>
          <div v-if="errors['variation.0.special_end_time']" class="form-control-feedback">{{ errors['variation.0.special_end_time'][0] }}</div>
        </div>

      </div>

      <div class="form-group">
        <button type="button" class="btn btn-custom-v1" @click.prevent="saveVariations">Save</button>
      </div>  
      <div class="row">
        <div class="col-sm-4 form-group">
          <button class="btn button" @click.prevent="destroy"><i class="pg-trash"></i></button>
        </div>
      </div>
      <div class="table-responsive">
        <table id="variationTable" class="table table-hover">
            <thead>
                <tr>
                  <th style="width:1%">
                    <div  class="checkbox check-success">
                        <input type="checkbox" id="select_all" @change="selectAll(attributes)">
                        <label for="select_all" class="no-padding no-margin"></label>
                    </div>
                  </th>
                  <th style="width:8%">
                      Image
                  </th>
                  <th style="width:29%">
                      Variation
                  </th>
                  <th style="width:25%">
                      SKU
                  </th>
                  <th>
                      Selling Price
                  </th>
                  <th>Action</th>
                </tr>  
            </thead>
            <tbody>
              <template v-for="attribute, index in attributes">
                <tr>
                  <tr>
                    <input type="hidden" v-model="attribute.id" :name="'variation['+index+'][id]'"> 

                    <td class="v-align-middle">
                      <div  class="checkbox check-success text-center">
                          <input type="checkbox" :id="'product-attribute-combination-checkbox-'+index" @change="select(attribute,$event)">
                          <label :for="'product-attribute-combination-checkbox-'+index" class="no-padding no-margin"></label>
                      </div>
                    </td>
                   
                    <td class="v-align-middle" >
                      <img v-if="attribute.image" :src="attribute.image" alt="variant image" class="img-fluid">
                      <i v-else class="fa fa-image fa-2x"></i>
                    </td>
                    <td class="v-align-middle" >{{ attribute.variation }}</td>
                    <td class="v-align-middle">
                      <input name="" v-model="attribute.sku" class="form-control" type="text" :name="'variation['+index+'][sku]'">
                    </td>
                    <td class="v-align-middle">
                        <input v-model="attribute.selling_price" class="form-control" type="text" :name="'variation['+index+'][selling_price]'">
                      </td>
                    <td class="v-align-middle">
                      <div class="dropdown">
                        <a  data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                          <i class="card-icon card-icon-settings "></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                          <a href="#" class="dropdown-item" @click.prevent="edit(attribute)">Edit</a>
                          <a href="#" class="dropdown-item" @click.prevent="destroyVariation(attribute)">Delete</a>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tr>
              </template>
            </tbody>
        </table>
      </div>
      <div class="modal fade slide-up disable-scroll" id="editVariation" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-lg">
          <div class="modal-content-wrapper">
            <div class="modal-content">
              <div class="modal-header clearfix text-left">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                </button>
                <h5>Edit <span class="semi-bold">Variation</span></h5>
              </div>
              <div class="modal-body" v-if="variation">
                <form class="form-horizontal" role="form" autocomplete="off">
                  <div class="scrollable">
                    <div style="height: 350px;">
                    <div class="form-group row" :class="{'has-danger': errors['sku'] }">
                      <label for="sku" class="col-md-3" :class="{'form-control-danger': errors['sku'] }">SKU</label>
                      <div class="col-md-8">
                        <input type="text" class="form-control" id="sku" v-model="variation.sku">
                        <div class="form-control-feedback" v-if="errors['sku']">{{ errors['sku'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['barcode'] }">
                      <label for="barcode" class="col-md-3">Barcode</label>
                      <div class="col-md-8">
                        <input type="text" class="form-control" :class="{'form-control-danger': errors['barcode'] }" id="barcode" v-model="variation.barcode">
                        <div class="form-control-feedback" v-if="errors['barcode']">{{ errors['barcode'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['isbn'] }">
                      <label for="isbn" class="col-md-3">ISBN</label>
                      <div class="col-md-8">
                        <input type="text" class="form-control" :class="{'form-control-danger': errors['isbn'] }" id="isbn" v-model="variation.isbn">
                        <div class="form-control-feedback" v-if="errors['isbn']">{{ errors['isbn'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['upc'] }">
                      <label for="upc" class="col-md-3">UPC</label>
                      <div class="col-md-8">
                        <input type="text" class="form-control" :class="{'form-control-danger': errors['upc'] }" id="upc" v-model="variation.upc">
                        <div class="form-control-feedback" v-if="errors['upc']">{{ errors['upc'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['image'] }">
                      <label for="image" class="col-md-3">Image</label>
                      <div class="col-md-3 text-center" v-if="variation.image">
                        <img :src="variation.image" alt="variation image" class="img-fluid">
                        <a href="#" data-dismiss="modal" @click.prevent="clearImage(variation.id)">Clear</a>
                      </div>
                      <div class="col-md-8" v-else>
                        <input type="file" id="image" class="form-control" :class="{'form-control-danger': errors['image'] }" v-on:change="fileChange">
                        <div class="form-control-feedback" v-if="errors['image']">{{ errors['image'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['cost_price'] }">
                      <label for="cost_price" class="col-md-3">cost price</label>
                      <div class="col-md-8">
                        <input type="text"  class="form-control" :class="{'form-control-danger': errors['cost_price'] }" id="cost_price" v-model="variation.cost_price">
                        <div class="form-control-feedback" v-if="errors['cost_price']">{{ errors['cost_price'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['selling_price'] }">
                      <label for="selling_price" class="col-md-3">selling price</label>
                      <div class="col-md-8">
                        <input type="text"  class="form-control" :class="{'form-control-danger': errors['selling_price'] }" id="selling_price" v-model="variation.selling_price">
                        <div class="form-control-feedback" v-if="errors['selling_price']">{{ errors['selling_price'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['special_price'] }">
                      <label for="special_price" class="col-md-3">special price</label>
                      <div class="col-md-8">
                        <input type="text"  class="form-control" :class="{'form-control-danger': errors['special_price'] }" id="special_price" v-model="variation.special_price">
                        <div class="form-control-feedback" v-if="errors['special_price']">{{ errors['special_price'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['special_active_date'] }">
                      <label class="col-md-3">special active date</label>
                      <div class="col-md-8">
                        <date-picker @apply="onVariationActiveDateChange" placeholder="Active on" :value="variation.special_active_date"></date-picker>
                        <div class="form-control-feedback" v-if="errors['special_active_date']">{{ errors['special_active_date'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['special_active_time'] }">
                      <label class="col-md-3">special active time</label> 
                      <div class="col-md-8">
                        <time-picker @applyTime="onVariationActiveTimeChange" placeholder="Active time" :value="variation.special_active_time"></time-picker>
                        <div class="form-control-feedback" v-if="errors['special_active_time']">{{ errors['special_active_time'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['special_end_date'] }">
                      <label class="col-md-3">special end date</label>
                      <div class="col-md-8">
                        <date-picker @apply="onVariationEndDateChange" placeholder="End on" :value="variation.special_end_date"></date-picker>
                        <div class="form-control-feedback" v-if="errors['special_end_date']">{{ errors['special_end_date'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['special_end_time'] }">
                      <label class="col-md-3">special end time</label> 
                      <div class="col-md-8">
                        <time-picker @applyTime="onVariationEndTimeChange" placeholder="End time" :value="variation.special_end_time"></time-picker>
                        <div class="form-control-feedback" v-if="errors['special_end_time']">{{ errors['special_end_time'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['weight'] }">
                      <label for="weight" class="col-md-3">Weight</label>
                      <div class="col-md-8">
                        <input type="text" class="form-control" :class="{'form-control-danger': errors['weight'] }" id="weight" v-model="variation.weight">
                        <div class="form-control-feedback" v-if="errors['weight']">{{ errors['weight'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['height'] }">
                      <label for="height" class="col-md-3">Height</label>
                      <div class="col-md-8">
                        <input type="text" class="form-control" :class="{'form-control-danger': errors['height'] }" id="height" v-model="variation.height">
                        <div class="form-control-feedback" v-if="errors['height']">{{ errors['height'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['width'] }">
                      <label for="width" class="col-md-3">Width</label>
                      <div class="col-md-8">
                        <input type="text" class="form-control" :class="{'form-control-danger': errors['width'] }" id="width" v-model="variation.width">
                        <div class="form-control-feedback" v-if="errors['width']">{{ errors['width'][0] }} </div>
                      </div>
                    </div>
                    <div class="form-group row" :class="{'has-danger': errors['depth'] }">
                      <label for="depth" class="col-md-3">Depth</label>
                      <div class="col-md-8">
                        <input type="text" class="form-control" :class="{'form-control-danger': errors['depth'] }" id="depth" v-model="variation.depth">
                        <div class="form-control-feedback" v-if="errors['depth']">{{ errors['depth'][0] }} </div>
                      </div>
                    </div>
                    </div>
                  </div>
                  <br>
                  <div class="row justify-content-between">
                    <div class="col-md-2">
                      <button class="btn btn-default btn-default-custom btn-block" type="button" data-dismiss="modal">Cancel</button>
                    </div>
                    <div class="col-md-2">
                      <button class="btn btn-custom-v1 btn-block" type="button" @click.prevent="saveVariation">Save</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end variation table -->
</div>
</template>
<script>
    import DatePicker from './DatePicker'
    import TimePicker from './TimePicker'

    export default {
      components: {
        'date-picker': DatePicker,
        'time-picker': TimePicker,
      },
        data () {
          return {
            cost_price: '',
            selling_price: '',
            special_price: '',
            available_date: '',
            special_active_date: '',
            special_active_time: '',
            special_end_date: '',
            special_end_time: '',
            pre_order: 0,
            attributes: [],
            selected_variations: [],
            delete_variations: [],
            variation: '',
            store_variations: [],
            option_set: '',
            loading: false,
            fileData: new FormData(),
            errors: [],
          }
        },
        props: [
          'data', 'updatevariationsurl', 'updatevariationurl', 'product', 'variations', 'loader'
        ],
        methods: {
          selectAll (attributes) { 
              $('#variationTable').find('input[type=checkbox]').prop('checked', $("#select_all").prop('checked'));
              if($("#select_all").prop('checked')) {
                  for (var i = 0; i < attributes.length; i++) {
                    this.selected_variations.push(attributes[i])
                    this.delete_variations.push(attributes[i].id)
                  }
              } else {
                  this.selected_variations = []
                  this.delete_variations = []
              }
              
          },
          select (attribute,e) {
              if ($("#"+e.currentTarget.id).prop('checked')) {  
                  this.selected_variations.push(attribute)
                  this.delete_variations.push(attribute.id)
              } else { 
                  this.selected_variations.splice(this.selected_variations.indexOf(attribute),1)
                  this.delete_variations.splice(this.selected_variations.indexOf(attribute.id),1)
              }  
          },
          saveVariations () {
            this.errors = [];
            if(this.selected_variations != '') {
              for (var i = 0; i < this.selected_variations.length; i++) {
                this.selected_variations[i].cost_price = this.cost_price
                this.selected_variations[i].selling_price = this.selling_price
                this.selected_variations[i].special_price = this.special_price
                this.selected_variations[i].special_active_date = this.special_active_date
                this.selected_variations[i].special_active_time = this.special_active_time
                this.selected_variations[i].special_end_date = this.special_end_date
                this.selected_variations[i].special_end_time = this.special_end_time
                this.selected_variations[i].pre_order = this.pre_order
                this.selected_variations[i].available_date = this.available_date
              }
              axios.patch(this.updatevariationsurl, {
                variation: this.selected_variations,
              }).then((response) => { 
                this.attributes = response.data.data
                $('#variationTable').find('input[type=checkbox]').prop('checked', false);
                this.special_active_date = this.min_quantity = this.cost_price = this.selling_price = this.available_date =
                this.special_active_time = this.special_end_date = this.special_end_time = this.pre_order = ''
                this.notify('Variations updated successfully.', 'success')
              }).catch((error) => {
                this.errors = error.response.data;
                this.notify('Something went wrong.', 'danger')
              })
            } else {
              this.alert('Please choose at least one variation to edit.')
            }
            
          },
          saveVariation () {
            this.errors = [];
            if(this.variation != '') {
              this.fileData.append('variation',JSON.stringify(this.variation))
              axios.post(this.updatevariationurl, this.fileData).then((response) => { 
                this.attributes = response.data.data
                $('#variationTable').find('input[type=checkbox]').prop('checked', false);
                this.quantity = this.min_quantity = this.price = this.compare_price = this.available_date = ''
                $('#editVariation').modal('hide');
                this.notify('Variation updated successfully.', 'success')
              }).catch((error) => {
                this.errors = error.response.data;
              })
            } 
          },
          edit (attribute) {
            this.variation = attribute
            $("#editVariation").modal('show')
          },
          destroy () { 
              if(this.delete_variations != '') {
                  swal({
                      title: 'Are you sure?',
                      text: "It will be deleted permanently!",
                      type: 'warning',
                      showCancelButton: true,
                      showConfirmButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Yes, delete it!',
                      allowOutsideClick: false              
                  }).then((result) => {
                    if (result.value) {
                      axios.delete('/merchant/product/variations/'+this.product+'/'+this.delete_variations+'/delete').then((response) => {
                        this.attributes = response.data.data
                        $('#variationTable').find('input[type=checkbox]').prop('checked', false);
                        this.quantity = this.min_quantity = this.price = this.compare_price = this.available_date = ''
                        swal('Deleted!', 'Variations deleted successfully!', 'success');    
                      }).catch((error) => {  
                          swal('Oops...', 'Something went wrong!', 'error');
                      });
                    }; 
                  });
              } else {
                  this.alert('Please choose at least one variation to delete.')
              }
             
          },
          destroyVariation (attribute) { 
            swal({
                title: 'Are you sure?',
                text: "It will be deleted permanently!",
                type: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false              
            }).then((result) => {
              if (result.value) {
                axios.delete('/merchant/product/variation/'+this.product+'/'+attribute.id+'/delete').then((response) => {
                  this.attributes = response.data.data
                  $('#variationTable').find('input[type=checkbox]').prop('checked', false);
                  this.quantity = this.min_quantity = this.price = this.compare_price = this.available_date = ''
                  swal('Deleted!', 'Variation deleted successfully!', 'success');    
                }).catch((error) => {  
                    swal('Oops...', 'Something went wrong!', 'error');
                });
              }; 
            });
          },
          alert (message) {
            swal({
                text: message,
                type: 'warning',
            })
          },
          notify (message, type) {
            $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: message,
                position: 'top-right',
                timeout: 5000,
                type: type
            }).show();
          },
          generate () { 
            this.loading = true
            axios.post('/merchant/product/variations/'+this.product, {
              option_set: this.option_set
            }).then((response) => { 
              this.loading = false
              this.attributes = response.data.data
            }).catch((error) => {  
              this.loading = false
              this.notify('Something went wrong.', 'danger')
            });
          },
          fileChange (e) {
            this.fileData.append('image', e.target.files[0])
          },
          clearImage (attribute_id) {
            axios.delete('/merchant/product/variation/image/'+this.product+'/'+attribute_id).then((response) => {
              this.attributes = response.data.data
              $('#variationTable').find('input[type=checkbox]').prop('checked', false);
              this.quantity = this.min_quantity = this.price = this.compare_price = this.available_date = ''
            });
          },
          onAvailableDateChange (date) {
            this.available_date = date
          },
          onActiveDateChange (date) {
            this.special_active_date = date
          },
          onEndDateChange (date) {
            this.special_end_date = date
          },
          onActiveTimeChange (time) {
            this.special_active_time = time
          },
          onEndTimeChange (time) {
            this.special_end_time = time
          },
          onVariationActiveDateChange (date) {
            this.variation.special_active_date = date
          },
          onVariationEndDateChange (date) {
            this.variation.special_end_date = date
          },
          onVariationAvailableDateChange (date) {
            this.variation.available_date = date
          },
          onVariationActiveTimeChange (time) {
            this.variation.special_active_time = time
          },
          onVariationEndTimeChange (time) {
            this.variation.special_end_time = time
          },
        },
        mounted() {
          this.attributes = this.data
          this.store_variations = this.variations
      
        },
    }

</script>