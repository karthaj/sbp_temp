<template>
  <div class="card card-transparent">
    <div class="card-header row">
      <div class="col-lg-10 col-md-9 col-sm-8">
          <h1 class="section-title">Products</h1>
      </div>
    </div>
    <div class="card-block">
      <div class="card card-default mb-0">
        <div class="card-block">
          <form action="#" @submit.prevent="getRecords()">
            <div class="d-flex justify-content-center">
              <div class="col-sm-2">
                <div class="form-group">
                  <select class="form-control" v-model="search.column">
                      <option value="">Select a filter</option>
                      <option value="active">Status</option>
                      <option value="blocked">Blocked</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-3">
                <select class="form-control" v-model="search.value">
                    <option value="">Select a value</option>
                    <option v-if="search.column && search.column === 'active'"value="1">Active</option>
                    <option v-if="search.column && search.column === 'active'"value="0">Inactive</option>
                    <option v-if="search.column && search.column === 'blocked'"value="1">True</option>
                    <option v-if="search.column && search.column === 'blocked'"value="0">False</option>
                </select>
              </div>
              <div class="col-sm-2">
                <button class="btn button btn-block" type="submit">Filter</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="card-block">
      <div class="row">
        <div class="col">
          <div data-pages="card" class="card card-default" id="card-basic">
            <div class="card-header separator">
              <div class="card-title">
                <button v-if="response.allow.deletion" class="btn btn-action-delete btn-xs" @click.prevent="destroy(selected)"><i class="aapl-trash2"></i></button>
                <a v-if="response.allow.create" :href="route" class="btn btn-action-add btn-xs">Add Product</a>
              </div>
              <div class="pull-right  ml-4">
                <div class="col-xs-12">
                  <input type="text" class="form-control pull-right" placeholder="Search" v-model="q" v-on:keyup.enter="getRecords">
                </div>
              </div>
              <div class="pull-right ml-4">
                <div class="col-xs-12">
                  <select class="pull-right form-control"  v-model="limit" @change="getRecords">
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
                    <table id="productTable" class="table table-hover tb100">
                        <thead>
                            <tr>
                                <th style="width:1%" v-if="canSelectItems && response.allow.deletion">
                                  <div  class="checkbox check-success">
                                      <input type="checkbox" value="select_all" id="select_all" @change="toggleSelectAll" :checked="filteredRecords.length === selected.length">
                                      <label for="select_all" class="no-padding no-margin"></label>
                                  </div>
                                </th>
                                <th style="width:10%">
                                  Image
                                </th>
                                <th>
                                  Type
                                </th>
                                <th class="sorting" @click="sortBy('name')">
                                    Name
                                    <i v-if="sort.key === 'name'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                </th>
                                <th class="sorting" @click="sortBy('price')">
                                    Price
                                    <i v-if="sort.key === 'price'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                </th>
                                 <th class="sorting" @click="sortBy('status')">
                                    Status
                                    <i v-if="sort.key === 'status'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                </th>
                                <th>Action</th>
                            </tr>  
                        </thead>
                        <tbody>
                          <template v-if="filteredRecords != 0">
                              <tr v-for="product in filteredRecords "> 
                                  <td class="v-align-middle" v-if="canSelectItems && response.allow.deletion">
                                    <div  class="checkbox check-success text-center">
                                        <input type="checkbox" :value="product.id" :id="'checkbox'+product.id" v-model="selected">
                                        <label :for="'checkbox'+product.id" class="no-padding no-margin"></label>
                                    </div>
                                  </td>
                                  <td class="v-align-middle">
                                    <img :src="product.image" :alt="product.name" class="img-fluid">
                                  </td>
                                  <td class="v-align-middle">
                                    <i class="aapl-tag" v-if="product.type === 'standard'"></i>
                                    <i class="aapl-tags" v-if="product.type === 'variant'"></i>
                                    <i class="aapl-ticket" v-if="product.type === 'virtual'"></i>
                                  </td>
                                  <td class="v-align-middle">{{ product.name }}</td>
                                  <td class="v-align-middle">{{ product.price }}</td>
                                  <td class="v-align-middle" v-if="product.active === 1">
                                    <a href="#" @click.prevent="updateStatus(product.id, 2)"><i class="aapl-checkmark-circle"></i></a>
                                  </td>
                                  <td class="v-align-middle" v-else-if="product.active === 0 || product.active === 2">
                                    <a href="#" @click.prevent="updateStatus(product.id, 1)"><i class="aapl-cross-circle"></i></a>
                                  </td>
                                  <td class="v-align-middle">
                                    <div class="dropdown">
                                      <a :href="product.edit">Edit</a>
                                      <a  data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                        <i class="aapl-chevron-down-circle ml-2"></i>
                                      </a>
                                      <div class="dropdown-menu dropdown-menu-right" role="menu">
                                        <a :href="product.view" class="dropdown-item" target="_blank">View</a>
                                        <a v-if="response.allow.deletion" href="#" class="dropdown-item" @click.prevent="deleteItem(product.id)">Delete</a>
                                        <a :href="product.duplicate" class="dropdown-item">Duplicate</a>
                                      </div>
                                    </div>
                                  </td>
                              </tr>
                          </template>
                        </tbody>
                    </table>
                    <pagination :pagination="response.pagination" for="products" class="mt-4"></pagination>
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
        props: ['endpoint', 'route'],
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
                    key: 'id',
                    order: 'desc'
                },
                limit : 10,
                q : '',
                search: {
                    value: '',
                    operator: 'equals',
                    column: ''
                },
                selected: [],
                status: '',
                page: 1,
                value: []
            }
        },
        computed: {
            filteredRecords () {
                let data = this.response.records
                // data = data.filter((row) => {
                //     return Object.keys(row).some((key) => {
                //         return String(row[key]).toLowerCase().indexOf(this.quickSearchQuery.toLowerCase()) > -1
                //     })
                // })
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
        watch: {
          '$route.query': {
            handler (query) {
              this.getRecords(query.page)
            },
            deep: true
          }
        },
        methods: {
            getRecords (page = this.$route.query.page) { 
              this.page = page;
              return axios.get(`${this.endpoint}?${this.getQueryParameters()}`).then((response) => { 
                  this.response = response.data.data
              })
            },
            getQueryParameters () {
                return queryString.stringify({
                    limit: this.limit,
                    page: this.page,
                    ...this.search,
                    q: this.q
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
                  this.deleteProduct(`${this.endpoint}/${selected}`)
              } else {
                  this.alert('Please choose at least one product to delete.')
              }   
            },
            deleteProduct (endpoint) {
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
                        swal('Deleted!', 'Products deleted successfully!', 'success');
                        this.getRecords().then(() => {
                          $('#productTable').find('input[type=checkbox]').prop('checked', false);
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
                        $('#productTable').find('input[type=checkbox]').prop('checked', false);
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
          this.getRecords(this.$route.query.page ? this.$route.query.page : 1)

          eventHub.$on('products.switched-page', (page) => {
            this.$router.replace({
              query: {
                page: page
              }
            })
          })
        },
    }

</script>
