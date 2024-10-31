<template>
  <div class="card card-transparent">
    <div class="card-header row">
      <div class="col-lg-10 col-md-9 col-sm-8">
          <h1 class="section-title">Orders</h1>
      </div>
    </div>
    <div class="card-block">
      <div class="card card-default mb-0">
        <div class="card-header">
          <span class="card-title">filters</span>
        </div>
        <div class="card-block">
          <form action="#" @submit.prevent="filter">
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="order_no">order no</label>
                  <input type="text" class="form-control" id="order_no" v-model="filters.order_no" autocomplete="off">
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="source">source</label>
                  <select id="source" class="form-control" v-model="filters.source">
                    <option value="all">all</option>
                    <option value="online">online</option>
                    <option value="pos">pos</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="status">status</label>
                  <select id="status" class="form-control" v-model="filters.status">
                    <option value="all">all</option>
                    <option v-for="state in states" :key="state.id" :value="state.slug">{{ state.name }}</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-3">
                <div class="form-group">
                  <label for="payment_method">payment method</label>
                  <select id="payment_method" class="form-control" v-model="filters.payment_method">
                    <option value="all">all</option>
                    <option v-for="payment in payments" :key="payment.id" :value="payment.alias">{{ payment.name }}</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label for="payment_status">payment status</label>
                  <select id="payment_status" class="form-control" v-model="filters.payment_status">
                    <option value="all">all</option>
                    <option value="received">received</option>
                    <option value="pending">pending</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <date-range-picker
                    @apply="onDateChange"
                    :show-ranges="true"
                >
                </date-range-picker>
              </div>
            </div>
            <div class="form-group">
              <button class="btn button" type="submit">Filter</button>
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
                <div class="card-title">Order summary 
                </div>
                <div class="pull-right ml-4">
                  <div class="col-xs-12">
                    <select class="pull-right form-control"  v-model="limit" @change="filter">
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
                      <table id="shippingClassTable" class="table table-hover">
                          <thead>
                              <tr>
                                  <th class="sorting"  @click="sortBy('id')">
                                    id
                                    <i v-if="sort.key === 'id'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting"  @click="sortBy('customer')">
                                    customer
                                    <i v-if="sort.key === 'customer'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting"  @click="sortBy('total')">
                                    total
                                    <i v-if="sort.key === 'total'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting"  @click="sortBy('payment')">
                                    payment <br>mode
                                    <i v-if="sort.key === 'payment'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting"  @click="sortBy('status')">
                                    order <br>status
                                    <i v-if="sort.key === 'status'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting"  @click="sortBy('payment_status')">
                                    payment <br>status
                                    <i v-if="sort.key === 'payment_status'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting"  @click="sortBy('order_source')">
                                    source
                                    <i v-if="sort.key === 'order_source'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                                  <th class="sorting"  @click="sortBy('date')">
                                    date
                                    <i v-if="sort.key === 'date'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                                  </th>
                              </tr>  
                          </thead>
                          <tbody>
                            <template v-if="filteredRecords != 0"> 
                              <tr v-for="order in filteredRecords ">
                                <td class="v-align-middle">
                                  <b><u><a :href="'/merchant/orders/'+order.reference+'/view'">{{ order.order_id }}</a></u></b>
                                </td>
                                <td class="v-align-middle">
                                  <span :class="{'font-weight-bold': order.alias === 'pending'}">{{ order.customer }}</span>
                                </td>
                                <td class="v-align-middle">
                                  <span :class="{'font-weight-bold': order.alias === 'pending'}">{{ order.total }}</span>
                                </td>
                                <td class="v-align-middle">
                                  <span :class="{'font-weight-bold': order.alias === 'pending'}">{{ order.payment }}</span>
                                </td>
                                <td class="v-align-middle">
                                  <span class="fa fa-dot-circle-o mr-2" :style="{'color': order.color}"></span>
                                  <span>{{ order.status }}</span>
                                </td>
                                <td class="v-align-middle">
                                  <span v-if="order.payment_status" class="fa fa-circle" style="color: yellowgreen;" title="paid"></span>
                                  <span v-else="order.payment_status" class="fa fa-circle-o-notch" style="color: orange;" title="pending"></span>
                                </td>
                                <td class="v-align-middle">
                                  <span :class="{'aapl-desktop': order.order_source === 'online', 'aapl-store': order.order_source === 'pos'}" :title="order.order_source"></span>
                                </td>
                                <td class="v-align-middle">
                                  <span :class="{'font-weight-bold': order.alias === 'pending'}">{{ order.date  }}</span>
                                </td>
                              </tr>
                            </template>
                          </tbody>
                      </table>
                      <pagination :pagination="response.pagination" for="orders" class="mt-4"></pagination>
                  </div>
                  <p class="text-center" v-else>No orders placed.</p>
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
    import DateRangePicker from './DateRangePicker.vue'
    import eventHub from '../../bus.js'

    export default {
        props: ['states', 'payments'],
        components: {
          Pagination,
          'date-range-picker': DateRangePicker
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
                filters: {
                    order_no: this.$route.query.order_no ? this.$route.query.order_no : '',
                    source: this.$route.query.source ? this.$route.query.source : 'all',
                    status: this.$route.query.status ? this.$route.query.status : 'all',
                    payment_method: this.$route.query.payment_method ? this.$route.query.payment_method : 'all',
                    payment_status: this.$route.query.payment_status ? this.$route.query.payment_status : 'all',
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
        watch: {
          '$route.query': {
            handler (query) {
              this.getRecords(query.page)
            },
            deep: true
          }
        },
        methods: {
            filter () {
              this.$router.replace({
                query: {
                  page: this.$route.query.page ? this.$route.query.page : 1,
                  limit: this.limit,
                  ...this.filters
                }
              })
            },
            getRecords (page = this.$route.query.page) {
              this.page = page; 
              return axios.get(`/merchant/orders/datatable?${this.getQueryParameters()}`).then((response) => { 
                  this.response = response.data.data
              })
            },
            getQueryParameters () {
              return queryString.stringify({
                  limit: this.limit,
                  page: this.page,
                  ...this.filters
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
            onDateChange (startDate, endDate) {
              this.filters.date = startDate+'|'+endDate;
            }
        },
        mounted() {
            this.getRecords(this.$route.query.page ? this.$route.query.page : 1)

            eventHub.$on('orders.switched-page', (page) => {
              this.$router.replace({
                query: {
                  page: page,
                  ...this.filters
                }
              })
            })
        },
    }

</script>
