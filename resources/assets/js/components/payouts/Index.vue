<template>
<div class="card card-transparent">
    <div class="card-header row justify-content-between align-items-end">
      <div class="col-lg-6 col-md-6 col-sm-7">
          	<h1 class="section-title">Payouts</h1>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4">
      	<date-range-picker
            @apply="onDateChange"
            :show-ranges="true"
            :show-label="false"
        >
        </date-range-picker>
      </div>
    </div>
    <div class="card-block">
        <div class="row">
          <div class="col">
            <div data-pages="card" class="card card-default" id="card-basic">
              <div class="card-header separator">
                <div v-if="payouts.length" class="card-title">pending balance: <strong>{{ payouts[0].balance }}</strong></div>
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
                  <div class="table-responsive" v-if="payouts.length">
                      <table id="shippingClassTable" class="table table-hover">
                          <thead>
                              <tr>
                                  <th>Date</th>
                                  <th>Order Amount</th>
                                  <th width="15%">Remarks</th>
                                  <th>Transaction fee</th>
                                  <th>Debit</th>
                                  <th>Credit</th>
                                  <th>Balance</th>
                              </tr>  
                          </thead>
                          <tbody>
                              <tr v-for="payout in payouts">
                                <td class="v-align-middle">{{ payout.date }}</td>
                                <td class="v-align-middle">{{ payout.amount }}</td>
                                <td class="v-align-middle">{{ payout.remarks }}</td>
                                <td class="v-align-middle">{{ payout.transaction_fee }}</td>
                                <td class="v-align-middle">{{ payout.debit }}</td>
                                <td class="v-align-middle">{{ payout.credit }}</td>
                                <td class="v-align-middle">{{ payout.balance }}</td>
                              </tr>
                          </tbody>
                      </table>
                      <pagination :pagination="pagination" for="payouts" class="mt-4"></pagination>
                  </div>
                  <p class="text-center" v-else>No payouts found.</p>
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
    import DateRangePicker from '../orders/DateRangePicker.vue'
    import eventHub from '../../bus.js'

    export default {
        components: {
          Pagination,
          'date-range-picker': DateRangePicker
        },
        data () {
            return {
                payouts: [],
                pagination: {},
                limit : 10,
                filters: {
                    date: ''
                },
                page: 1
            }
        },
        methods: {
            getRecords (page = 1) {
              this.page = page; 
              return axios.get(`/merchant/payouts.json?${this.getQueryParameters()}`).then((response) => { 
                  this.payouts = response.data.data
                  this.pagination = response.data.meta.pagination
              })
            },
            getQueryParameters () {
                return queryString.stringify({
                    limit: this.limit,
                    page: this.page,
                    ...this.filters
                })
            }, 
            onDateChange (startDate, endDate) {
              this.filters.date = moment(startDate).format('YYYY-MM-DD')+'|'+moment(endDate).format('YYYY-MM-DD');
              this.getRecords()
            }
        },
        mounted() {
            this.getRecords()

            eventHub.$on('payouts.switched-page', this.getRecords)
        },
    }

</script>