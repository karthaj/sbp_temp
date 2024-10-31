<template>
  <div class="card-block">
      <div class="row">
        <div class="col">
          <div data-pages="card" class="card card-default">
            <div class="card-header">
                <div class="form-group row align-items-end">
                  <label for="invoice_no" class="control-label col-md-2">Invoice No</label>
                  <div class="col-md-4">
                    <input type="text" id="invoice_no" class="form-control" v-model="invoice_no">
                  </div>
                  <div class="col-md-3">
                    <button type="button" class="btn btn-info btn-block" :disabled="invoice_no === '' || loading" @click="getOrder">Search</button>
                  </div>
                </div>
            </div>
            <div  class="card-block pt-20">
              <h5 v-if="records.length">Invoice #{{ invoice_no }}</h5>
              <p v-if="error" class="text-center"><i class="fa fa-warning"></i> Invoice not found.</p>
                <div class="table-responsive" v-if="records.length">
                    <table class="table table-hover">
                        <thead> 
                          <tr>
                            <th style="width:1%">
                              <div  class="checkbox check-success">
                                  <input type="checkbox" value="select_all" id="select_all"  @change="toggleSelectAll" :checked="records.length === selected.length">
                                  <label for="select_all" class="no-padding no-margin"></label>
                              </div>
                            </th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Quantity</th>
                            <th>Qty to return</th>
                          </tr>
                        </thead>
                        <tbody>
                            <tr v-for="record, index in records" :key="record.id">
                              <td class="v-align-middle">
                                <div  class="checkbox check-success text-center">
                                    <input type="checkbox" :value="record" :id="'checkbox'+record.id" v-model="selected">
                                    <label :for="'checkbox'+record.id" class="no-padding no-margin"></label>
                                </div>
                              </td>
                              <td class="v-align-middle">{{ record.product_name }}</td>
                              <td class="v-align-middle">{{ record.sku }}</td>
                              <td class="v-align-middle">{{ record.quantity }}</td>
                              <td class="v-align-middle">
                                <div class="form-group">
                                  <select class="form-control" v-model="record.qty_to_return">
                                    <option :value="index" v-for="index in record.quantity">{{ index }}</option>
                                  </select>
                                </div>
                              </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="container-fluid container-fixed-lg footer action-wrapper">
          <div class="small no-margin pull-right sm-pull-reset text-center">
            <a href="/merchant/orders/returns" class="btn btn-default btn-default-custom mr-2">Cancel</a>
            <button class="btn btn-custom-v1" type="button" :disabled="selected.length == 0 || loading" @click="save">Save</button>
          </div>
      </div>
  </div>

</template>

<script>
    import queryString from 'query-string'

    export default {
        props: ['endpoint', 'add-return-endpoint'],
        data () {
            return {
                invoice_no: '',
                selected: [],
                records: [],
                loading: false,
                error: false
            }
        },
        computed: {
          
        },
        methods: {
          getOrder() {
            this.loading = true;
            this.error = false;
            axios.get(`${this.endpoint}?${this.getQueryParameters()}`).then((response) => { 

                if(response.data.data.length) {
                  this.records = response.data.data;
                } else {
                  this.error = true;
                }
                
                this.loading = false;
                
            })
          },
          getQueryParameters () {
              return queryString.stringify({
                  invoice_no: this.invoice_no,
              })
          }, 
          toggleSelectAll () {
            if (this.selected.length > 0) {
              this.selected = []
              return
            }
            
            this.records.forEach((record) => {
              this.selected.push(record);
            })
          },
          save () {
            axios.post(this.addReturnEndpoint, {
              invoice_no: this.invoice_no,
              selected: this.selected
            }).then((response) => { 
                this.notification('Return created successfully.', 'success');
                this.records = [];
                this.selected = [];
                this.error = false;
                this.loading = false;
                this.invoice_no = ''
            }).catch((error) => {
                this.notification('Something went wrong.', 'danger');
            })
          },
          notification(message, type) {
            $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: message,
                position: 'top-right',
                timeout: 5000,
                type: type
            }).show();
          }
        },
        mounted() {
          
        },
    }

</script>
