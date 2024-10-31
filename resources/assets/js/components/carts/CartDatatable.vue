<template>
    <div class="card-block">
        <div class="row">
          <div class="col">
            <div data-pages="card" class="card card-default" id="card-basic">
              <div class="card-header separator">
                <div class="card-title">
                  <button v-if="response.allow.deletion" class="btn btn-action-delete btn-xs mr-2" @click.prevent="destroy(selected)"><i class="aapl-trash2"></i></button>
                  abandoned carts 
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
                      <table id="shippingClassTable" class="table table-hover tb100">
                          <thead>
                              <tr>
                                  <th style="width:1%" v-if="canSelectItems && response.allow.deletion">
                                    <div  class="checkbox check-success">
                                        <input type="checkbox" value="select_all" id="select_all" @change="toggleSelectAll" :checked="filteredRecords.length === selected.length">
                                        <label for="select_all" class="no-padding no-margin"></label>
                                    </div>
                                  </th>
                                  <th style="width:15%" class="sorting" @click="sortBy('date')">
                                    date
                                    <i v-if="sort.key === 'date'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting text-center" @click="sortBy('customer')">
                                    customer
                                    <i v-if="sort.key === 'customer'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting text-center" @click="sortBy('total')">
                                    total
                                    <i v-if="sort.key === 'total'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th>
                                    Stock Reserved
                                    <i v-if="sort.key === 'stock_reserved'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th style="width:15%" v-if="response.allow.deletion || response.allow.update">Action</th>
                              </tr>  
                          </thead>
                          <tbody>
                            <template v-if="filteredRecords != 0"> 
                              <tr v-for="cart in filteredRecords ">
                                <td class="v-align-middle" v-if="canSelectItems && response.allow.deletion">
                                  <div  class="checkbox check-success text-center">
                                      <input type="checkbox" :value="cart.id" :id="'checkbox'+cart.id" v-model="selected">
                                      <label :for="'checkbox'+cart.id" class="no-padding no-margin"></label>
                                  </div>
                                </td>
                                <td class="v-align-middle">{{ cart.date }}</td>
                                <td class="v-align-middle">{{ cart.customer }}</td>
                                <td class="v-align-middle text-right">{{ cart.total }}</td>
                                <td v-if="cart.stock_reserved" class="v-align-middle text-center"><i class="aapl-cart-full" style="color: #FF8C00"></i></td>
                                <td v-else class="v-align-middle text-center"><i class="aapl-cart-empty"></i></td>
                                <td class="v-align-middle" v-if="response.allow.deletion || response.allow.update">
                                  <div class="dropdown">
                                    <a v-if="response.allow.update" :href="cart.url">View</a>
                                    <a  v-if="response.allow.deletion" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                      <i class="aapl-chevron-down-circle ml-2"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                                      <a v-if="cart.stock_reserved" href="#" class="dropdown-item" @click.prevent="restock(cart.restock_url)">Restock</a>
                                      <a v-if="response.allow.deletion" href="#" class="dropdown-item" @click.prevent="deleteItem(cart.id)">Delete</a>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            </template>
                          </tbody>
                      </table>
                      <pagination :pagination="response.pagination" for="carts" class="mt-4"></pagination>
                  </div>
                  <p class="text-center" v-else>No carts found.</p>
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
        props: ['endpoint'],
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
                sort: {
                    key: 'date',
                    order: 'desc'
                },
                limit : 10,
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
              if (this.sort.key) {
                  data = _.orderBy(data, (i) => {
                      let value = i[this.sort.key]

                      if(this.sort.key === 'date') {
                        return String(i[this.sort.key]).toLowerCase()
                      }

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
            alert (message) {
              swal({
                  text: message,
                  type: 'warning',
              })
            },
            deleteItem (item) {
              this.selected.push(item);
              this.destroy(this.selected);
            },
            destroy (selected) { 
                if(selected.length) {
                     this.deleteCart(`${this.endpoint}/${selected}`)
                } else {
                    this.alert('Please choose at least one cart to delete.')
                }
               
            },
            deleteCart (endpoint) {
              if(this.response.allow.deletion) {
                swal({
                    title: 'Are you sure?',
                    text: "Deleted carts cannot be recovered. Do you still want to continue?",
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
                        swal('Deleted!', 'Cart deleted successfully!', 'success');
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
            restock (url) {
              axios.post(url).then((response) => {
                 swal('Restocked!', 'Stock restocked successfully!', 'success');
                 this.getRecords();
              }).catch((error) => {  
                  swal('Oops...', 'Something went wrong!', 'error');
              });
            }
        },
        mounted() {
            this.getRecords()

            eventHub.$on('carts.switched-page', this.getRecords)
        },
    }

</script>
