<template>
    <div class="card-block">
        <div class="row">
          <div class="col">
            <div data-pages="card" class="card card-default">
              <div class="card-header separator">
                <div class="card-title">
                  <button v-if="response.allow.deletion" class="btn btn-action-delete btn-xs" @click.prevent="destroy(selected)"><i class="aapl-trash2"></i></button>
                  <a v-if="response.allow.create && accounts !== accountsLimit" :href="route" class="btn btn-action-add btn-xs">Add User</a>
                  <small class="ml-2">{{ accounts }} of {{ accountsLimit }} created.</small>
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
                      <table id="userTable" class="table table-hover">
                          <thead>
                              <tr>
                                  <th style="width:1%" v-if="canSelectItems && response.allow.deletion">
                                    <div  class="checkbox check-success">
                                        <input type="checkbox" value="select_all" id="select_all" @change="toggleSelectAll" :checked="filteredRecords.length === selected.length">
                                        <label for="select_all" class="no-padding no-margin"></label>
                                    </div>
                                  </th>
                                  
                                  <th class="sorting" @click="sortBy('email')">
                                    email
                                    <i v-if="sort.key === 'email'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting" @click="sortBy('first_name')">
                                    first name
                                    <i v-if="sort.key === 'first_name'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting" @click="sortBy('last_name')">
                                    last name
                                    <i v-if="sort.key === 'last_name'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting" @click="sortBy('active')">
                                    status
                                    <i v-if="sort.key === 'active'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th style="width:15%" v-if="response.allow.deletion || response.allow.update">Action</th>
                              </tr>  
                          </thead>
                          <tbody>
                            <template v-if="filteredRecords != 0">
                                <tr v-for="record in filteredRecords "> 
                                    <td v-if="canSelectItems && response.allow.deletion" class="v-align-middle">
                                      <div  class="checkbox check-info text-center">
                                          <input type="checkbox" :value="record.id" :id="'checkbox'+record.id"  v-model="selected">
                                          <label :for="'checkbox'+record.id" class="no-padding no-margin"></label>
                                      </div>
                                    </td>
                                    <td class="v-align-middle" >{{ record.email }}</td>
                                    <td class="v-align-middle" >{{ record.first_name }}</td>
                                    <td class="v-align-middle" >{{ record.last_name }}</td>
                                    <td class="v-align-middle" v-if="record.active">
                                      <a href="#" @click.prevent="updateStatus(record.id, 0)"><i class="aapl-checkmark-circle"></i></a>
                                    </td>
                                    <td class="v-align-middle" v-else>
                                      <a href="#" @click.prevent="updateStatus(record.id, 1)"><i class="aapl-cross-circle"></i></a>
                                    </td>
                                    <td class="v-align-middle" v-if="response.allow.deletion || response.allow.update">
                                      <div class="dropdown">
                                        <a v-if="response.allow.update" :href="'/merchant/account/users/'+record.id+'/edit'">Edit</a>
                                        <a  v-if="response.allow.deletion" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                          <i class="aapl-chevron-down-circle ml-2"></i>
                                        </a>
                                        <div v-if="response.allow.deletion" class="dropdown-menu" role="menu" style="position: relative;">
                                          <a href="#" class="dropdown-item" @click.prevent="deleteItem(record.id)">Delete</a>
                                        </div>
                                      </div>
                                    </td>
                                </tr>
                            </template>
                          </tbody>
                      </table>
                      <pagination :pagination="response.pagination" for="users" class="mt-4"></pagination>
                  </div>
                  <p class="text-center" v-else>No records found.</p>
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
        props: ['endpoint','route','accountsLimit','accounts'],
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
                    key: 'email',
                    order: 'asc'
                },
                limit : 10,
                quickSearchQuery : '',
                search: {
                    value: '',
                    operator: 'equals',
                    column: ''
                },
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
            sortBy (column) {
                this.sort.key = column
                this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc'
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
                     this.deleteUser(`${this.endpoint}/${selected}`)
                } else {
                    this.alert('Please choose at least one user to delete.')
                }
               
            },
            deleteUser (endpoint) {
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
                        swal('Deleted!', 'User deleted successfully!', 'success');
                        this.getRecords().then(() => {
                          this.selected = []
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
                    this.getRecords()
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

            eventHub.$on('users.switched-page', this.getRecords)
        },
    }

</script>
