<template>
  <div class="card-block">
      <div class="row">
        <div class="col">
          <div data-pages="card" class="card card-default">
            <div class="card-header separator">
              <div class="card-title">
                <button type="button" class="btn btn-action-add btn-xs" @click.prevent="addStock(stocks)"
                :disabled="disabled">update stock</button>
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
                    <table class="table table-hover">
                        <thead>
                            <tr>
                              <th class="sorting" @click="sortBy('name')">Name
                                <i v-if="sort.key === 'name'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                              </th>
                              <th class="sorting" @click="sortBy('sku')">SKU
                                <i v-if="sort.key === 'sku'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                              </th>
                              <th class="sorting" @click="sortBy('qty')">Avl Qty
                                <i v-if="sort.key === 'qty'" :class="{ 'pg-arrow_up' : sort.order === 'asc', 'pg-arrow_down' : sort.order === 'desc' }"></i>
                              </th>
                              <th width="13%">Qty</th>
                              <th>Remarks</th>
                            </tr>  
                        </thead>
                        <tbody>
                          <tr v-for="record, index in filteredRecords"> 
                            <td class="v-align-middle">
                              <a href="#" @click.prevent="viewProduct(record)">{{ record.name }}</a>
                              <p v-if="record.variation">{{ record.variation }}</p>
                            </td>
                            <td class="v-align-middle">{{ record.sku }}</td>
                            <td class="v-align-middle">{{ record.qty }}
                              <i v-if="stocks[index].quantity > 0 || stocks[index].quantity < 0" class="aapl-arrow-right"> {{ record.qty + stocks[index].quantity }}</i>
                            </td>
                            <td class="v-align-middle">
                              <div class="form-group">
                                <input type="number" class="form-control" autocomplete="off" :min="0 - record.qty" v-model.number="stocks[index]['quantity']">
                              </div>
                            </td>
                            <td class="v-align-middle">
                              <div class="form-group">
                                <input type="text" class="form-control" autocomplete="off" v-model.trim="stocks[index]['remarks']">
                              </div>
                            </td>
                          </tr>
                        </tbody>
                    </table>
                    <pagination :pagination="meta.pagination" for="storestocks" class="mt-4"></pagination>
                </div>
                <p class="text-center" v-else>No records found</p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade slide-up" id="productModal" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog">
          <div class="modal-content-wrapper">
            <div class="modal-content">
              <div class="modal-header clearfix text-left">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                </button>
                <h5><span class="semi-bold">Product</span></h5>
              </div>
              <div class="modal-body">
                <div class="media">
                  <img  class="d-flex mr-3 img-fluid" width="250" :src="stock.image" :alt="stock.name">
                  <div class="media-body">
                    <h5 class="mt-0">{{ stock.name }}</h5>
                    <p v-html="stock.description"></p>
                  </div>
                </div>
              </div>  
            </div>
          </div>
        </div>
      </div>
      <div class="container-fluid container-fixed-lg footer action-wrapper">
        <div class="small no-margin pull-right sm-pull-reset text-center">
          <a :href="cancelendpoint" class="btn btn-default btn-action-cancel mr-2">Cancel</a>
          <button class="btn btn-action-save" type="button" :disabled="disabled" @click.prevent="addStock(stocks)">update stock</button>
        </div>
      </div>
    </div>
</template>

<script>
    import queryString from 'query-string'
    import Pagination from '../pagination/Pagination.vue'
    import eventHub from '../../bus.js'

    export default {
        props: ['endpoint', 'addstockurl', 'cancelendpoint'],
        components: {
          Pagination
        },
        data () {
            return {
                records: [],
                sort: {
                    key: 'name',
                    order: 'asc'
                },
                limit : 10,
                q : '',
                stocks: [],
                stock: {
                  name: '',
                  image: '',
                  description: ''
                },
                meta: {},
                page: 1,
                disabled: false
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
                this.getStock(data)
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
                    q: this.q
                })
            }, 
            sortBy (column) {
                this.sort.key = column
                this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc'
            },
            addStock (stocks) {

              if(!stocks.length) {
                return;
              }

              this.disabled = true;

              axios.post(this.addstockurl, {
                stocks: stocks
              }).then(() => {
                this.getRecords()
                $('.page-content-wrapper').pgNotification({
                    style: 'simple',
                    message: 'Stock Successfully updated.',
                    position: 'top-right',
                    timeout: 5000,
                    type: "success"
                }).show();
                this.disabled = false;
              }).catch((error) => {
                $('.page-content-wrapper').pgNotification({
                    style: 'simple',
                    message: 'Something went wrong!',
                    position: 'top-right',
                    timeout: 5000,
                    type: "danger"
                }).show();
                this.disabled = false;
              })
            },
            alert (message) {
              swal({
                  text: message,
                  type: 'warning',
              })
            },
            getStock (data) {

              this.stocks = []
             
              for (var i = 0; i < data.length; i++) {
                var stock = {stock_id:data[i].stock_id, quantity: 0, remarks:''};
                this.stocks.push(stock)
              }
            },
            viewProduct (record) {
              this.stock.name = record.name;
              this.stock.image = record.image;
              this.stock.description = record.description;
              $("#productModal").modal('show')
            }
        },
        mounted() {
            this.getRecords()
            
            eventHub.$on('storestocks.switched-page', this.getRecords)
        },
    }

</script>
