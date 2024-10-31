<template>
  <div class="card-block">
      <div class="row">
        <div class="col">
          <div data-pages="card" class="card card-default" id="card-basic">
            <div class="card-header separator">
              <div class="card-title">
                <button v-if="response.allow.deletion" class="btn btn-action-delete btn-xs" @click.prevent="destroy(selected)"><i class="aapl-trash2"></i></button>
                <a  v-if="response.allow.create" href="javascript:;" class="btn btn-action-add btn-xs" data-toggle="modal" data-target="#addTaxClass">
                  Add a Tax Class
                </a> 
              </div>
              <div class="pull-right  ml-4">
                <div class="col-xs-12">
                  <input type="text" class="form-control pull-right" placeholder="Search" v-model="quickSearchQuery">
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
                    <table id="zoneTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width:1%" v-if="canSelectItems && response.allow.deletion">
                                  <div  class="checkbox check-success">
                                      <input type="checkbox" value="select_all" id="select_all" @change="toggleSelectAll" :checked="filteredRecords.length === selected.length">
                                      <label for="select_all" class="no-padding no-margin"></label>
                                  </div>
                                </th>
                                
                                <th>name</th>
                                <th style="width:15%" v-if="response.allow.deletion || response.allow.update">Action</th>
                            </tr>  
                        </thead>
                        <tbody>
                          <tr v-for="record in filteredRecords "> 
                              <td class="v-align-middle" v-if="canSelectItems && response.allow.deletion">
                                <div  class="checkbox check-success text-center">
                                    <input type="checkbox" :value="record.id" :id="'checkbox'+record.id"  v-model="selected">
                                    <label :for="'checkbox'+record.id" class="no-padding no-margin"></label>
                                </div>
                              </td>
                              <td class="v-align-middle" >{{ record.name }}</td>
                              <td class="v-align-middle" v-if="response.allow.deletion || response.allow.update">
                                <div class="dropdown">
                                  <a v-if="response.allow.update" href="#" @click.prevent="editRecord(record)">Edit</a>
                                  <a v-if="response.allow.deletion" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                    <i class="aapl-chevron-down-circle ml-2"></i>
                                  </a>
                                  <div v-if="response.allow.deletion" class="dropdown-menu" role="menu">
                                    <a href="#" class="dropdown-item" @click.prevent="deleteItem(record.id)">Delete</a>
                                  </div>
                                </div>
                              </td>
                          </tr>
                        </tbody>
                    </table>
                    <pagination :pagination="response.pagination" for="tax_classes" class="mt-4"></pagination>
                </div>
                <p class="text-center" v-else>No records found</p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade slide-up disable-scroll" id="addTaxClass" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog ">
          <div class="modal-content-wrapper">
            <div class="modal-content">
              <div class="modal-header clearfix text-left">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                </button>
                <h5>Create a <span class="semi-bold">Tax Class</span></h5>
              </div>
              <div class="modal-body">
                <form action="#" role="form" method="post" autocomplete="off" @submit.prevent="store">
                  <div class="form-group" :class="{ 'has-danger': error['tax_class'] }">
                    <label for="tax_class">Tax Class</label>
                    <input type="text" name="tax_class" id="tax_class" class="form-control" :class="{ 'form-control-danger': error['tax_class'] }" v-model="tax_class">
                    <div class="form-control-feedback" v-if="error['tax_class']">{{ error['tax_class'][0] }}</div>
                  </div>
                  <div class="form-group">
                    <input type="submit" value="Save" class="btn btn-action-save pull-right">
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
      </div>
      <div class="modal fade slide-up disable-scroll" id="editTaxClass" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog ">
          <div class="modal-content-wrapper">
            <div class="modal-content">
              <div class="modal-header clearfix text-left">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                </button>
                <h5>Edit <span class="semi-bold">Tax Class</span></h5>
              </div>
              <div class="modal-body">
                <form action="#" role="form" method="post" autocomplete="off" @submit.prevent="update">
                  <div class="form-group" :class="{ 'has-danger': error['tax_class'] }">
                    <label for="tax_class">Tax Class</label>
                    <input type="text" name="tax_class" id="tax_class" class="form-control" :class="{ 'form-control-danger': error['tax_class'] }" v-model="tax_class">
                    <div class="form-control-feedback" v-if="error['tax_class']">{{ error['tax_class'][0] }}</div>
                  </div>
                  <div class="form-group">
                    <input type="submit" value="Update" class="btn btn-custom-v1 pull-right">
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
      </div>
    </div>
</template>

<script>
    import queryString from 'query-string'
    import Pagination from '../pagination/Pagination.vue'
    import eventHub from '../../bus.js'

    export default {
        props: ['endpoint', 'storeuri'],
        components: {
          Pagination,
        },
        data () {
            return {
                response: {
                    records: [],
                    allow: {},
                    pagination: {}
                },
                limit : 10,
                quickSearchQuery : '',
                tax_class: '',
                id: '',
                error: [],
                selected: [],
                page: 1
            }
        },
        computed: {
          filteredRecords () {
              let data = this.response.records
              data = data.filter((row) => {
                  return Object.keys(row).some((key) => {
                      return String(row[key]).toLowerCase().indexOf(this.quickSearchQuery.toLowerCase()) > -1
                  })
              })
              return data 

          },
          canSelectItems () {
            return this.filteredRecords.length <= 500
          }
        },
        methods: {
            getRecords (page = 1) { 
              this.page = page;
              return axios.get(`${this.endpoint}?${this.getQueryParameters()}`).then((response) => { 
                  this.response = response.data.data
              })
            },
            getQueryParameters () {
                return queryString.stringify({
                    limit: this.limit,
                    ...this.search
                })
            }, 
            toggleSelectAll () { 
              if (this.selected.length > 0) {
                this.selected = []
                return
              }
              this.selected = _.map(this.filteredRecords, 'id')
            },
            editRecord (record) {
              this.tax_class = record.name
              this.id = record.id
              $("#editTaxClass").modal('show');
            },
            deleteItem (item) {
              this.selected.push(item);
              this.destroy(this.selected);
            },
            destroy (selected) { 
                if(selected.length) {
                     this.deleteTaxClass(`${this.endpoint}/${selected}`)
                } else {
                    this.alert('Please choose at least one tax class to delete.')
                }
               
            },
            deleteTaxClass (endpoint) {
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
                        swal('Deleted!', 'Tax classes deleted successfully!', 'success');
                        this.getRecords().then(() => {
                          this.selected = []
                          $('#taxClassTable').find('input[type=checkbox]').prop('checked', false);
                        })   
                    }).catch((error) => {  
                        swal('Oops...', 'Something went wrong!', 'error');
                    });
                  }; 
                }); 
              }
            },
            store () { 
             axios.post(this.storeuri, {
                tax_class: this.tax_class
              }).then(() => {
                  this.getRecords().then(() => {
                      this.tax_class = ''
                      this.error = []
                      $('#taxClassTable').find('input[type=checkbox]').prop('checked', false);
                      $("#addTaxClass").modal('hide');
                      this.notify('Tax class created successfully.','success')
                  })
              }).catch((error) => {
                  this.error = error.response.data
              })
            },
            update () { 
             if(this.response.allow.update) {
                axios.patch('/merchant/store/tax/tax-class/'+this.id, {
                  tax_class: this.tax_class,
                  id: this.id
                }).then(() => {
                    this.getRecords().then(() => {
                        this.tax_class = this.id = ''
                        this.error = []
                        $('#taxClassTable').find('input[type=checkbox]').prop('checked', false);
                        $("#editTaxClass").modal('hide');
                        this.notify('Tax class updated successfully.','success')
                    })
                }).catch((error) => {
                    this.error = error.response.data
                })
             }
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
            }
        },
        mounted() {
            this.getRecords()

            eventHub.$on('tax_classes.switched-page', this.getRecords)
        },
    }

</script>
