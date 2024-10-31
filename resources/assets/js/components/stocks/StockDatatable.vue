<template>
    <div class="card-block">
        <div class="row">
          <div class="col">
            <div data-pages="card" class="card card-default" id="card-basic">
              <ul class="nav nav-tabs nav-tabs-simple" role="tablist" data-init-reponsive-tabs="dropdownfx">
                <li class="nav-item">
                  <a class="active" data-toggle="tab" role="tab" data-target="#stocksTab" href="#">Stocks</a>
                </li>
                <li class="nav-item">
                  <a href="#" data-toggle="tab" role="tab" data-target="#transfersTab">Stock History</a>
                </li>
              </ul>
              <div  class="card-block pt-20">
                <div class="tab-content">
                  <div class="tab-pane active" id="stocksTab">
                    <div class="form-group row align-items-center" v-if="channels.length > 1">
                      <label>select store</label>
                      <div class="col-md-3 col-sm-4">
                        <select class="form-control" v-model="channel" @change="getRecords">
                          <option value="">Master</option>
                          <option :value="channel.id" v-for="channel in channels">{{ channel.name }}</option>
                        </select>
                      </div>
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
                    <div class="table-responsive" v-if="filteredRecords.length">
                      <table id="stocksTable" class="table table-hover">
                          <thead>
                              <tr>
                                  <th style="width:10%">Image</th>
                                  <th>Product</th>
                                  <th>SKU</th>
                                  <th>Quantity</th>
                                  <th>Action</th>
                              </tr>  
                          </thead>
                          <tbody>
                            <tr v-for="record, index in filteredRecords "> 
                                  <td class="v-align-middle">
                                    <img :src="record.image" :alt="record.name" class="img-fluid">
                                  </td>
                                  <td class="v-align-middle">
                                    {{ record.name }}
                                    <p v-if="record.variation">
                                      {{ record.variation }}
                                    </p>
                                  </td>
                                  <td class="v-align-middle"> {{ record.sku }} </td>
                                  <td class="v-align-middle"> {{ record.qty }} </td>
                                  <td class="v-align-middle">
                                    <a :href="record.url">View</a>
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                      <pagination :pagination="stockMeta.pagination" for="stocks" class="mt-4"></pagination>
                    </div>
                    <p class="text-center" v-else>No records found</p>
                  </div>
                  <div class="tab-pane " id="transfersTab">
                    <div class="pull-right  ml-4">
                      <div class="col-xs-12">
                        <input type="text" class="form-control pull-right" placeholder="Search" v-model="s">
                      </div>
                    </div>
                    <div class="pull-right ml-4">
                      <div class="col-xs-12">
                        <select class="pull-right form-control"  v-model="limit" @change="getTransfers">
                          <option value="">View all</option>
                          <option value="10">View 10</option>
                          <option value="20">View 20</option>
                          <option value="30">View 30</option>
                          <option value="50">View 50</option>
                          <option value="100">View 100</option>
                        </select>
                      </div>
                    </div>
                    <div class="table-responsive" v-if="filteredStockTransfers.length">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th style="width:10%">Image</th>
                            <th>Product</th>
                            <th>Store</th>
                            <th>Employee</th>
                            <th>Reason</th>
                            <th>Quantity</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="transfer, index in filteredStockTransfers"> 
                            <td class="v-align-middle">
                              <img :src="transfer.image" :alt="transfer.name" class="img-fluid">
                            </td>
                            <td class="v-align-middle">
                              {{ transfer.name }}
                              <p v-if="transfer.variation">{{ transfer.variation }}</p>
                            </td>
                            <td>{{ transfer.entity }}</td>
                            <td>{{ transfer.user }}</td>
                            <td>{{ transfer.remark }}</td>
                            <td>
                              <span class="badge badge-inverse" v-if="transfer.sign > 0">
                                <i class="pg-plus" ></i>{{ transfer.qty }}
                              </span>
                              <span class="badge badge-important" v-else>
                                <i class="pg-minus" ></i>{{ transfer.qty }}
                              </span>
                            </td>
                            <td>{{ transfer.created_at }}</td>
                          </tr>
                        </tbody>  
                      </table>
                      <pagination :pagination="transferMeta.pagination" for="stocktransfers" class="mt-4"></pagination>
                    </div>
                    <p class="text-center" v-else>No records found</p>
                  </div>
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
        props: ['endpoint', 'endpointstocktransfer', 'data', 'productimage', 'defaultimage', 'stores'],
        components: {
          Pagination
        },
        data () {
            return {
                records: [],
                transfers: [],
                sort: {
                    key: 'product',
                    order: 'asc'
                },
                limit : 10,
                q : '',
                s : '',
                qty: 0,
                selected: [],
                channels: [],
                channel: '',
                stockMeta: {},
                transferMeta: {},
                page: 1
            }
        },
        computed: {
            filteredRecords () {
                let data = this.records
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
            filteredStockTransfers () {
                let data = this.transfers
                 data = data.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.s.toLowerCase()) > -1
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
        },
        methods: {
            getRecords (page) { 
              return axios.get(`${this.endpoint}?${this.getStockQueryParameters(page)}`).then((response) => { 
                  this.records = response.data.data
                  this.stockMeta = response.data.meta
              })
            },
            getTransfers (page) { 
              return axios.get(`${this.endpointstocktransfer}?${this.getStockTransferQueryParameters(page)}`).then((response) => { 
                  this.transfers = response.data.data
                  this.transferMeta = response.data.meta
              })
            },
            getStockQueryParameters (page) {
                return queryString.stringify({
                    limit: this.limit,
                    page: page,
                    ...this.search,
                    channel: this.channel,
                    q: this.q
                })
            }, 
            getStockTransferQueryParameters (page) {
                return queryString.stringify({
                    limit: this.limit,
                    page: page,
                    ...this.search
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
            loadStocksForOnlineStore() {
              var store = this.channels.find((channel) => {
                            return channel.online_sales;
                          })

              this.channel = store.id;
            } 
        },
        mounted() {
            this.getTransfers()
            this.channels = this.stores

            if(this.channels.length === 1) {
              this.loadStocksForOnlineStore();
            }
            
            this.getRecords();
            eventHub.$on('stocks.switched-page', this.getRecords)
            eventHub.$on('stocktransfers.switched-page', this.getTransfers)
        },
    }

</script>
