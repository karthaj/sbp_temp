<template>
    <div class="card-block">
        <div data-pages="card" class="card card-default">
          <div class="card-header separator">
            <div class="card-title">
              <button v-if="response.allow.deletion" class="btn btn-action-delete btn-xs" @click.prevent="destroy(selected)"><i class="aapl-trash2"></i></button>
              <a :href="shippingZoneRoute" class="btn btn-action-add btn-xs">Add shipping zone</a>
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
                              <th class="sorting" @click="sortBy('name')">
                                name
                                <i v-if="sort.key === 'zone_name'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                              </th>
                              <th class="sorting" @click="sortBy('zone_type')">
                                type
                                <i v-if="sort.key === 'zone_type'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                              </th>
                              <th class="sorting" @click="sortBy('display_name')">
                                option
                                <i v-if="sort.key === 'display_name'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                              </th>
                              <th class="sorting" @click="sortBy('status')">
                                status
                                <i v-if="sort.key === 'status'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                              </th>
                              <th style="width:15%" v-if="response.allow.update || response.allow.create">Action</th>
                          </tr>  
                      </thead>
                      <tbody>
                          <tr v-for="record in filteredRecords "> 
                              <td class="v-align-middle" v-if="canSelectItems && response.allow.deletion">
                                <div  class="checkbox check-success text-center">
                                    <input type="checkbox" :value="record.id" :id="'checkbox'+record.id" v-model="selected">
                                    <label :for="'checkbox'+record.id" class="no-padding no-margin"></label>
                                </div>
                              </td>
                              <td class="v-align-middle" >{{ record.zone_name }}</td>
                              <td class="v-align-middle" >{{ record.zone_type }}</td>
                              <td class="v-align-middle" >{{ record.display_name }}</td>
                              <td class="v-align-middle" v-if="record.status">
                                <a href="#" @click.prevent="updateStatus(record.id, 0)"><i class="aapl-checkmark-circle"></i></a>
                              </td>
                              <td class="v-align-middle" v-else>
                                <a href="#" @click.prevent="updateStatus(record.id, 1)"><i class="aapl-cross-circle"></i></a>
                              </td>
                              <td class="v-align-middle" v-if="response.allow.deletion || response.allow.update">
                                <div class="dropdown">
                                  <a v-if="response.allow.update" :href="'/merchant/store/shipping/'+record.alias">Edit</a>
                                  <a data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                    <i class="aapl-chevron-down-circle ml-2"></i>
                                  </a>
                                  <div class="dropdown-menu dropdown-menu-right" role="menu">
                                    <a :href="'/merchant/store/shipping/zone/configure/'+record.alias" class="dropdown-item">Configure</a>
                                    <a v-if="response.allow.deletion" href="#" class="dropdown-item" @click.prevent="deleteItem(record.id)">Delete</a>
                                  </div>
                                </div>
                              </td>
                          </tr>
                      </tbody>
                  </table>
                  <pagination :pagination="response.pagination" for="shipping_zones" class="mt-4"></pagination>
              </div>
              <p class="text-center" v-else>No records found</p>
          </div>
        </div>
    </div>
</template>

<script>
    import queryString from 'query-string'
    import Pagination from '../pagination/Pagination.vue'
    import eventHub from '../../bus.js'

    export default {
        props: ['endpoint','shippingZoneRoute','shippingClassRoute'],
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
                sort: {
                    key: 'zone_name',
                    order: 'asc'
                },
                limit : 10,
                quickSearchQuery : '',
                selected: [],
                status: '',
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
                    page: this.page
                })
            }, 
            toggleSelectAll () { 
              if (this.selected.length > 0) {
                this.selected = []
                return
              }
              this.selected = _.map(this.filteredRecords, 'id')
            },
            deleteItem (item) {
              this.selected.push(item);
              this.destroy(this.selected);
            },
            destroy (selected) { 
                if(selected.length) {
                     this.deleteZone(`${this.endpoint}/${selected}`)
                } else {
                    this.alert('Please choose at least one shipping zone to delete.')
                }
               
            },
            deleteZone (endpoint) {
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
                        swal('Deleted!', 'Shipping zones deleted successfully!', 'success');
                        this.getRecords().then(() => {
                          this.selected = []
                          $('#zoneTable').find('input[type=checkbox]').prop('checked', false);
                        })   
                    }).catch((error) => {  
                        swal('Oops...', 'Something went wrong!', 'error');
                    });
                  }; 
                }); 
              }
            },
            updateStatus (id, status) {
              if(this.response.allow.update) {
                axios.patch(`${this.endpoint}/${id}`, {
                  status: status
                }).then(() => {
                    this.getRecords().then(() => {
                        this.status = ''
                        $('#zoneTable').find('input[type=checkbox]').prop('checked', false);
                    })
                }).catch((error) => {
                    $('.page-content-wrapper').pgNotification({
                        style: 'simple',
                        message: error,
                        position: 'top-right',
                        timeout: 5000,
                        type: "danger"
                    }).show();
                })
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

            eventHub.$on('shipping_zones.switched-page', this.getRecords)
        },
    }

</script>
