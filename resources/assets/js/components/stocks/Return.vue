<template>
  <div class="card-block">
      <div class="row">
        <div class="col">
          <div data-pages="card" class="card card-default">
            <div class="card-header separator">
              <a href="/merchant/stock/return/create" class="btn btn-action-add btn-xs">Create Return Stock</a>
              <div class="pull-right  ml-4">
                <div class="col-xs-12">
                  <input type="text" class="form-control pull-right" placeholder="Search" v-model="quickSearchQuery">
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
                    <table class="table table-hover">
                        <thead>
                            <tr>
                              <th class="sorting" @click="sortBy('id')">id
                                <i v-if="sort.key === 'id'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                              </th>
                              <th class="sorting" @click="sortBy('store')">store
                                <i v-if="sort.key === 'store'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                              </th>
                              <th class="sorting" @click="sortBy('type')">type
                                <i v-if="sort.key === 'type'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                              </th>
                              <th class="sorting" @click="sortBy('status')">status
                                <i v-if="sort.key === 'status'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                              </th>
                              <th class="sorting" @click="sortBy('created_at')">date
                                <i v-if="sort.key === 'created_at'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                              </th>
                              <th>action</th>
                            </tr>  
                        </thead>
                        <tbody>
                          <tr v-for="record, index in filteredRecords" :key="record.id"> 
                            <td class="v-align-middle">
                              <a href="#" v-if="record.status === 0" @click.prevent="update(record.id)">
                                {{ record.id }}
                              </a>
                              <span v-else>{{ record.id }}</span>
                            </td>
                            <td class="v-align-middle">{{ record.store }}</td>
                            <td class="v-align-middle">{{ record.type }}</td>
                            <td class="v-align-middle">
                              <span  v-if="record.status === 1" class="label label-success">complete</span>
                              <span v-else-if="record.status === 2" class="label label-danger">rejected</span>
                              <span v-else-if="record.status === 0" class="label label-warning">pending</span>
                            </td>
                            <td class="v-align-middle">{{ record.created_at }}</td>
                            <td>
                              <a href="#" @click.prevent="getTransfer(record.id)"><i class="fa fa-eye"></i></a>
                            </td>
                          </tr>
                        </tbody>
                    </table>
                    <pagination :pagination="meta.pagination" for="stocks.returns" class="mt-4"></pagination>
                </div>
                <p class="text-center" v-else>No records found</p>
            </div>
          </div>
        </div>
      </div>

      <transferDetail/>
      <updateTransfer/>
    </div>
</template>

<script>
    import queryString from 'query-string'
    import Pagination from '../pagination/Pagination.vue'
    import eventHub from '../../bus.js'
    import transferDetail from './partials/TransferDetail.vue'
    import updateTransfer from './partials/UpdateTransfer.vue'

    export default {
        props: ['endpoint'],
        components: {
          Pagination,
          transferDetail,
          updateTransfer
        },
        data () {
            return {
                records: [],
                sort: {
                    key: 'id',
                    order: 'desc'
                },
                limit : 10,
                quickSearchQuery : '',
                meta: {},
                page: 1
            }
        },
        computed: {
            filteredRecords () {
                let data = this.records
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

            }
        },
        methods: {
            getRecords (page = 1) { 
              this.page = page;
                return axios.get(`${this.endpoint}?${this.getQueryParameters()}`).then((response) => { 
                    this.records = response.data.data
                    this.meta = response.data.meta
                })
            },
            getQueryParameters () {
                return queryString.stringify({
                    limit: this.limit,
                    page: this.page,
                    ...this.search
                })
            }, 
            sortBy (column) {
                this.sort.key = column
                this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc'
            },
            getTransfer (transfer) {
                eventHub.$emit('transfer', transfer);
            },
            numberFormat(number) {
              var parts = number.toString().split(".");
              parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              return parts.join(".");
            },
            update (transfer) {
              eventHub.$emit('transfer.update', transfer);
            }
        },
        mounted() {
            this.getRecords()

            eventHub.$on('stocks.returns', this.getRecords)
            eventHub.$on('transfer.updated', this.getRecords)
        },
    }

</script>
