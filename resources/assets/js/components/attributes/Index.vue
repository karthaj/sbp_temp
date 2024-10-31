<template>
  <div class="tab-content card-block">
    <div class="tab-pane active" id="tabVariations">
      <div class="row">
        <div class="col">
          <div data-pages="card" class="card card-default">
              <div class="card-header separator">
                <div class="card-title">
                  <button v-if="response.allow.deletion" class="btn btn-action-delete btn-xs" @click.prevent="destroy(selected)"><i class="aapl-trash2"></i></button>
                  <a v-if="response.allow.create" :href="optionstoreuri" class="btn btn-action-add btn-xs ml-2">Add variant</a>
                </div>
                <div class="pull-right  ml-4">
                  <div class="col-xs-12">
                    <input type="text" class="form-control pull-right" placeholder="Search" v-model="q" v-on:keyup.enter="getRecords">
                  </div>
                </div>
                <div class="pull-right ml-4">
                  <div class="col-xs-12">
                    <select class="pull-right form-control"  v-model="limit" @change="getRecords">
                      <option value="">View all</option>
                      <option value="10">View 10</option>
                      <option value="20">View 20</option>
                      <option value="30">View 30</option>
                      <option value="50">View 50</option>
                      <option value="100">View 100</option>
                    </select>
                  </div>
                </div>
              </div>
              <div  class="card-block pt-20">
                  <div class="table-responsive" v-if="filteredRecords.length">
                      <table id="attributeTable" class="table table-hover">
                          <thead>
                              <tr>
                                  <th style="width:1%" v-if="canSelectItems && response.allow.deletion">
                                    <div  class="checkbox check-success">
                                        <input type="checkbox" value="select_all" id="select_all" @change="selectAll" :checked="filteredRecords.length === selected.length">
                                        <label for="select_all" class="no-padding no-margin"></label>
                                    </div>
                                  </th>
                                  <th style="width:25%" class="sorting" @click="sortBy('name')">
                                      Option Name
                                      <i v-if="sort.key === 'name'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th style="width:25%" class="sorting" @click="sortBy('public_name')">
                                      Display Name
                                      <i v-if="sort.key === 'public_name'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th style="width:25%" class="sorting" @click="sortBy('group_type')">
                                      Option Type
                                      <i v-if="sort.key === 'group_type'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th style="width:24%" v-if="response.allow.deletion || response.allow.update">Action</th>
                              </tr>  
                          </thead>
                          <tbody>
                            <template v-if="filteredRecords != 0">
                                <tr v-for="record in filteredRecords "> 
                                    <td v-if="record.name !== 'main' && canSelectItems && response.allow.deletion" class="v-align-middle">
                                      <div  class="checkbox check-success text-center">
                                          <input type="checkbox" :value="record.id" :id="'checkbox'+record.id"  v-model="selected">
                                          <label :for="'checkbox'+record.id" class="no-padding no-margin"></label>
                                      </div>
                                    </td>
                                    <td class="v-align-middle">{{ record.name }}</td>
                                    <td class="v-align-middle">{{ record.public_name }}</td>
                                    <td class="v-align-middle">{{ record.group_type }}</td>
                                    <td class="v-align-middle" v-if="response.allow.deletion || response.allow.update">
                                      <div class="dropdown">
                                        <a v-if="response.allow.update" :href="'/merchant/attributes/'+record.id+'/edit'">Edit</a>
                                        <a  v-if="response.allow.deletion" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                          <i class="aapl-chevron-down-circle ml-2"></i>
                                        </a>
                                        <div v-if="response.allow.deletion" class="dropdown-menu dropdown-menu-right" role="menu">
                                          <a href="#" class="dropdown-item" @click.prevent="deleteItem(record.id)">Delete</a>
                                        </div>
                                      </div>
                                    </td>
                                </tr>
                            </template>
                          </tbody>
                      </table>
                      <pagination :pagination="response.pagination" for="variations" class="mt-4"></pagination>
                  </div>
                  <p class="text-center" v-else>No records found</p>
              </div>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="tabVariationSets">
      <div class="row">
        <div class="col">
          <div data-pages="card" class="card card-default">
              <div class="card-header separator">
                <div class="card-title">
                  <button v-if="option_set.allow.deletion" class="btn btn-action-delete btn-xs" @click.prevent="destroySet(selected_sets)"><i class="aapl-trash2"></i></button>
                  <a v-if="option_set.allow.create" :href="optionsetstoreuri" class="btn btn-action-add btn-xs ml-2">Add variant set</a>
                </div>
                <div class="pull-right  ml-4">
                  <div class="col-xs-12">
                    <input type="text" class="form-control pull-right" placeholder="Search" v-model="q" v-on:keyup.enter="getOptionSets">
                  </div>
                </div>
                <div class="pull-right ml-4">
                  <div class="col-xs-12">
                    <select class="pull-right form-control"  v-model="limit" @change="getRecords">
                      <option value="">View all</option>
                      <option value="10">View 10</option>
                      <option value="20">View 20</option>
                      <option value="30">View 30</option>
                      <option value="50">View 50</option>
                      <option value="100">View 100</option>
                    </select>
                  </div>
                </div>
              </div>
              <div  class="card-block pt-20">
                  <div class="table-responsive" v-if="filteredOptionSets.length">
                      <table id="optionSetsTable" class="table table-hover">
                          <thead>
                              <tr>
                                  <th style="width:1%" v-if="canSelectOptionSetItems && option_set.allow.deletion">
                                    <div  class="checkbox check-success">
                                        <input type="checkbox" value="select_set_all" id="select_set_all" @change="toggleSelectAll" :checked="filteredOptionSets.length === selected_sets.length">
                                        <label for="select_set_all" class="no-padding no-margin"></label>
                                    </div>
                                  </th>
                                  <th class="sorting"  @click="sortBy('name')">variation set name
                                    <i :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th style="width:24%" v-if="option_set.allow.deletion || option_set.allow.update">Action</th>
                              </tr>  
                          </thead>
                          <tbody>
                            <template v-if="filteredRecords != 0">
                                <tr v-for="record in filteredOptionSets "> 
                                    <td class="v-align-middle" v-if="canSelectOptionSetItems && option_set.allow.deletion">
                                      <div  class="checkbox check-success text-center">
                                          <input type="checkbox" :value="record.id" :id="'checkbox'+record.id" v-model="selected_sets">
                                          <label :for="'checkbox'+record.id" class="no-padding no-margin"></label>
                                      </div>
                                    </td>
                                    <td class="v-align-middle">{{ record.name }}</td>
                                    <td class="v-align-middle" v-if="option_set.allow.deletion || option_set.allow.update">
                                      <div class="dropdown">
                                        <a :href="'/merchant/attributes/variation-sets/'+record.id+'/edit'">Edit</a>
                                        <a  data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                          <i class="aapl-chevron-down-circle ml-2"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                                          <a href="#" class="dropdown-item" @click.prevent="deleteSetItem(record.id)">Delete</a>
                                        </div>
                                      </div>
                                    </td>
                                </tr>
                            </template>
                          </tbody>
                      </table>
                      <pagination :pagination="option_set.pagination" for="variation_sets" class="mt-4"></pagination>
                  </div>
                  <p class="text-center" v-else>No records found</p>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    import queryString from 'query-string'
    import Pagination from '../pagination/Pagination.vue'
    import eventHub from '../../bus.js'

    export default {
        props: ['endpoint', 'endpointoptionsets', 'updateuri', 'optionstoreuri', 'optionsetstoreuri'],
        components: {
          Pagination,
        },
        data () {
            return {
                response: {
                    displayable: [],
                    records: [],
                    allow: {},
                    pagination: {}
                },
                option_set: {
                    displayable: [],
                    records: [],
                    allow: {},
                    pagination: {}
                },
                sort: {
                    key: 'name',
                    order: 'asc'
                },
                limit : 10,
                q : '',
                rowData: [],
                selected: [],
                selected_sets: [],
                page: 1
            }
        },
        computed: {
            filteredRecords () {
                let data = this.response.records
                if (this.sort.key) {
                    data = _.orderBy(data, (i) => {
                        let value = i[this.sort.key]
                        if(!isNaN(parseFloat(value))) {
                            return parseFloat(value)
                        }
                        return String(i[this.sort.key]).toLowerCase()
                    }, this.sort.order)
                }
                return data 

            },
            filteredOptionSets () {
                let data = this.option_set.records
                if (this.sort.key) {
                    data = _.orderBy(data, (i) => {
                        let value = i[this.sort.key]
                        if(!isNaN(parseFloat(value))) {
                            return parseFloat(value)
                        }
                        return String(i[this.sort.key]).toLowerCase()
                    }, this.sort.order)
                }
                return data 

            },
            canSelectItems () {
              return this.filteredRecords.length <= 500
            },
            canSelectOptionSetItems () {
              return this.filteredOptionSets.length <= 500
            }
        },
        methods: {
            getRecords (page = 1) { 
              this.page = page;
              return axios.get(`${this.endpoint}?${this.getQueryParameters()}`).then((response) => { 
                  this.response = response.data.data
              })
            },
            getOptionSets (page = 1) {
              this.page = page; 
              return axios.get(`${this.endpointoptionsets}?${this.getQueryParameters()}`).then((response) => { 
                  this.option_set = response.data.data
              })
            },
            getQueryParameters () {
                return queryString.stringify({
                    limit: this.limit,
                    page: this.page,
                    q: this.q
                })
            }, 
            sortBy (column) {
                this.sort.key = column
                this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc'
            },
            selectAll () { 
              if (this.selected.length > 0) {
                this.selected = []
                return
              }
              this.selected = _.map(this.filteredRecords, 'id')
                
            },
            toggleSelectAll () { 
              if (this.selected_sets.length > 0) {
                this.selected_sets = []
                return
              }
              this.selected_sets = _.map(this.filteredOptionSets, 'id')
            },
            deleteItem (item) {
              this.selected.push(item);
              this.destroy(this.selected);
            },
            destroy (selected) { 
                if(selected.length) {
                     this.deleteVariation(`${this.endpoint}/${selected}`)
                } else {
                    this.alert('Please choose at least one attribute to delete.')
                }
               
            },
            deleteSetItem (item) {
              this.selected_sets.push(item);
              this.destroySet(this.selected_sets);
            },
            destroySet (selected_sets) { 
                if(selected_sets.length) {
                    this.deleteSet(`${this.endpointoptionsets}/${selected_sets}`)
                } else {
                    this.alert('Please choose at least one option set to delete.')
                }
               
            },
            deleteVariation (endpoint) {
              if(this.response.allow.deletion) {
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
                    axios.delete(endpoint).then((response) => {
                        swal('Deleted!', 'Attributes deleted successfully!', 'success');
                        this.getRecords().then(() => {
                          this.editing.id = []
                          $('#attributeTable').find('input[type=checkbox]').prop('checked', false);
                        })   
                    }).catch((error) => {  
                        swal('Oops...', 'Something went wrong!', 'error');
                    });
                  }; 
                }); 
              }
            },
            deleteSet (endpoint) {
              if(this.option_set.allow.deletion) {
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
                    axios.delete(endpoint).then((response) => {
                        swal('Deleted!', 'Option sets deleted successfully!', 'success');
                        this.getOptionSets().then(() => {
                          this.selected_sets = []
                        })   
                    }).catch((error) => {  
                        swal('Oops...', 'Something went wrong!', 'error');
                    });
                  }; 
                }); 
              }
            },
            alert (message) {
              swal({
                  text: message,
                  type: 'warning',
              })
            }
        },
        mounted() {
            this.getRecords()
            this.getOptionSets()

            eventHub.$on('variations.switched-page', this.getRecords)
            eventHub.$on('variation_sets.switched-page', this.getOptionSets)

            if(localStorage.success) {

              localStorage.removeItem('success');
              
              $('.page-content-wrapper').pgNotification({
                  style: 'simple',
                  message: "Attribute updated successfully!",
                  position: 'top-right',
                  timeout: 5000,
                  type: "success"
              }).show();

            }

        },
    }

</script>
