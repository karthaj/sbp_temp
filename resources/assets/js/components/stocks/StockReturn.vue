<template>
  <div class="card-block">
    <div class="row">
       <div class="col">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div class="card card-default">
                        <div class="card-block">
                          <div class="row justify-content-center">
                            <div class="col-md-4">
                              <div class="form-group row">
                                <label for="type" class="col-md-2 col-form-label">Type</label>
                                <div class="col-md-10">
                                  <select id="type" class="form-control" v-model="transfer_type">
                                    <option value="damage">Damage</option>
                                    <option value="stolen">Stolen</option>
                                    <option value="return">Return</option>
                                  </select>
                                </div>
                                <label v-if="errors['transfer_type']" id="store-error" class="col-md-12 error" for="from">{{ errors['transfer_type'][0] }}</label>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group row">
                                <label for="store" class="col-md-2 col-form-label">Store</label>
                                <div class="col-md-7">
                                  <select id="store" class="form-control" v-model="transfer_store">
                                    <option :value="store.id" v-for="store in stores">{{ store.name }}</option>
                                  </select>
                                </div>
                                <div class="col-md-3">
                                  <button type="button" class="btn btn-primary" @click="apply($event)">Apply</button>
                                </div>
                                <label v-if="errors['transfer_store']" id="store-error" class="col-md-12 error" for="store">{{ errors['transfer_store'][0] }}</label>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <p v-if="disable_search">Please select a type and a store to continue.</p>
                              <div class="form-group">
                                <input type="text" id="search" class="form-control input-lg typeahead sample-typehead" placeholder="Search" autocomplete="off" :disabled="disable_search"> 
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>    
              </div>
          </div>
        </div>
    </div>

    <div class="row">
       <div class="col">
          <div class="row">
              <div class="col-lg-12">
                 <div class="card card-transparent">
                    <div class="card-block no-padding">
                      <div class="card card-default">
                        <div class="card-block">
                          <div class="row align-items-end">
                            <div class="col-0">
                              <div class="form-group">
                                <label for="sku">SKU</label>
                                <input type="text" id="sku" class="form-control" v-model="product.sku" disabled>
                              </div>
                            </div>
                            <div class="col-md-1">
                              <div class="form-group">
                                <label for="stock">stock</label>
                                <input type="text" id="stock" class="form-control" v-model="product.stock" disabled>
                              </div>
                            </div>
                            <div class="col-md-1">
                              <div class="form-group">
                                <label for="qty">qty</label>
                                <input type="text" id="qty" class="form-control" v-model="product.qty" v-on:keypress="isNumber($event)">
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="form-group">
                                <button type="button" class="btn btn-action-add btn-block" @click.prevent="add(product)"
                                :disabled="disabled">Add</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>    
              </div>
          </div>
        </div>
    </div>

    <div class="row">
      <div class="col">
        <div data-pages="card" class="card card-default">
          <div  class="card-block pt-20">
              <div class="table-responsive">
                  <table class="table table-hover">
                      <thead>
                          <tr>
                            <th>item #</th>
                            <th>image</th>
                            <th>sku</th>
                            <th>name</th>
                            <th>qty</th>
                            <th style="width:1%"></th>
                          </tr>  
                      </thead>
                      <tbody>
                        <tr v-for="stock, index in stocks">
                          <td>{{ index + 1 }}</td>
                          <td><img :src="stock.image" :alt="stock.name" class="img-fluid" width="30"></td>
                          <td>{{ stock.sku }}</td>
                          <td>{{ stock.name }}</td>
                          <td>{{ stock.qty }}</td>
                          <td>
                            <a href="#" @click.prevent="removeProduct(stock)"><i class="fa fa-trash"></i></a>
                          </td>
                        </tr>
                      </tbody>
                  </table>
              </div>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="remarks">Remarks</label>
      <textarea id="remarks" class="form-control" cols="30" rows="10" v-model="remarks"></textarea>
    </div>
    <div class="form-group">
      <button class="btn btn-action-add" type="button" :disabled="stocks.length === 0"
                              @click.prevent="transfer()">Transfer</button>
    </div>
  </div>
</template>

<script>
    import queryString from 'query-string'

    export default {
        props: ['stores', 'searchendpoint', 'endpoint', 'transfer-endpoint'],
        data () {
            return {
              stocks: [],
              transfer_store: '',
              transfer_type: 'return',
              remarks: '',
              product: {
                stock_id: '',
                sku: '',
                name: '',
                stock: 0,
                qty: '',
              },
              disabled: true,
              disable_search: true,
              errors: []
            }
        },
        watch: {
          'product.qty': function (value) {
            
            if(this.product.qty > this.product.stock) {
              this.product.qty = this.product.stock
            } else if (this.product.qty === '' || this.product.qty === 0) {
              this.disabled = true;
            } else {
              this.disabled = false;
            }

          },
          'product.stock': function (value) {
            if (this.product.stock === 0) {
              this.disabled = true;
            }
          }
        },
        methods: {
          send (stock_id) {
            axios.post(this.endpoint, {
              stock_id: stock_id,
              store: this.transfer_store
            }).then((response) => { 
              this.getProduct(response.data.stock)
            }).catch((error) => {
              console.log(error.response)
            })
          },
          getProduct(product) {
            this.product.stock_id = product.stock_id
            this.product.image = product.image
            this.product.sku = product.sku
            this.product.name = product.name
            this.product.stock = product.stock
          },
          add(product) {
            var existing = this.stocks.find((p) => {
              return p.stock_id === product.stock_id
            })

            if(existing) {
              return;
            }

            var item = {
              stock_id: product.stock_id,
              sku: product.sku,
              image: product.image,
              name: product.name,
              stock: product.stock,
              qty: product.qty,
            };
            this.stocks.push(item);
            this.stocks.reverse();

          },
          removeProduct(product) {
            this.stocks = this.stocks.filter((p) => {
              return p.stock_id !== product.stock_id;
            });
          },
          isNumber: function(evt) {
              evt = (evt) ? evt : window.event;
              var charCode = (evt.which) ? evt.which : evt.keyCode;
              if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();;
              } else {
                return true;
              }
          },
          transfer () {
            axios.post(this.transferEndpoint, {
              transfer_store: this.transfer_store,
              transfer_type: this.transfer_type,
              remarks: this.remarks,
              stocks: this.stocks
            }).then((response) => { 
              this.refreshTransfer();
              $('.page-content-wrapper').pgNotification({
                  style: 'simple',
                  message: 'Success.',
                  position: 'top-right',
                  timeout: 5000,
                  type: "success"
              }).show();
            }).catch((error) => {
              this.errors = error.response.data;
            })
          },
          refreshTransfer () {
            this.stocks = [];
            this.remarks = '';
            this.product.stock_id = '';
            this.product.sku = '';
            this.product.name = '';
            this.product.stock = 0;
            this.product.qty = '';
            this.product.quantity = '';
          },
          apply (event) {
            event.target.disabled = true;
            $("#type").attr('disabled', 'disabled');
            $("#store").attr('disabled', 'disabled');
            this.disable_search = false;
          }
        },
        mounted() {
          this.transfer_store = this.stores[0].id;
          var vm = this;
          var engine = new Bloodhound({
              remote: {
                  url: this.searchendpoint + '?q=%QUERY%',
                  wildcard: '%QUERY%'
              },
              datumTokenizer: Bloodhound.tokenizers.whitespace('q'),
              queryTokenizer: Bloodhound.tokenizers.whitespace
          });
               
          var products = $("#search").typeahead({
              hint: true,
              highlight: true,
              minLength: 1
            }, 
            {
              source: engine,
              name: 'products',
              displayKey: 'name',
              limit: 20,
              templates: {
               empty: [
                    '<div class="empty-message">',
                      'product not found',
                    '</div>'
                  ].join('\n'),
              }
            }).bind('typeahead:select', function(ev, suggestion) {
              vm.send(suggestion.id)
              products.typeahead('val', '');
            }); 
        },
    }

</script>
