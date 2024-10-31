<template>

    <div class="table-responsive">
      <div v-if="!loading && bills.length" class="dataTables_wrapper no-footer">
        <table class="table table-hover table-condensed dataTable no-footer" id="condensedTable" role="grid">
          <thead>
            <tr>
              <th style="width:20%">#no</th>
              <th style="width:20%">amount</th>
              <th style="width:20%">date</th>
              <th style="width:20%">state</th>
              <th style="width:10%"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="bill in bills" :key="bill.id">
              <td class="v-align-middle semi-bold">
                <a v-if="bill.status == 0" :href="'/merchant/admin/checkout/'+bill.reference">{{ bill.id }}</a>
                <template v-else>{{ bill.id }}</template>
              </td>
              <td class="v-align-middle semi-bold">{{ bill.amount }}</td>
              <td class="v-align-middle semi-bold">{{ bill.date }}</td>
              <td class="v-align-middle semi-bold">
                <span v-if="bill.status === 1" class="badge badge-success">Paid</span>
                <span v-else-if="bill.status === 0" class="badge badge-important">Pending</span>
              </td>
              <td class="v-align-middle semi-bold">
                <a :href="'/merchant/store/bills/download/'+bill.reference" target="_blank" data-placement="top" data-toggle="tooltip" data-original-title="download"><i class="aapl-download2"></i></a>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination v-if="bills.length > 10" :pagination="pagination" for="bills" class="mt-4"></pagination>
      </div>

      <p v-else-if="!loading && bills.length === 0" class="text-center my-5">You don't have any bills yet.</p>

      <div v-else-if="loading" class="progress-circle-indeterminate"></div>
    </div> 

</template>

<script>
    import queryString from 'query-string'
    import eventHub from '../../bus.js'
    import Pagination from '../pagination/Pagination.vue'

    export default {
        components: {
          Pagination,
        },
        data () {
            return {
              bills: [],
              pagination: {},
              page: 1,
              loading: true
            }
        },
        methods: {
          getRecords (page = 1) { 
            this.page = page;
            return axios.get('/merchant/store/bills?'+this.getQueryParameters()).then((response) => { 
                this.bills = response.data.bills.data;
                this.pagination = response.data.bills.meta.pagination;
                this.loading = false;
            })
          },
          getQueryParameters () {
              return queryString.stringify({
                  limit: this.limit,
                  page: this.page
              })
          }
        },
        updated () {
          $('[data-toggle="tooltip"]').tooltip();
        },
        mounted() {
          this.getRecords();
          eventHub.$on('bills.switched-page', this.getRecords);

        },
    }

</script>
