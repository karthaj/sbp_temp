<template>
    
  <div class="modal fade slide-up" id="modalWeightOrder" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5>Ship by Weight / Order Total <span class="semi-bold">Settings</span></h5>
        </div>
        <form id="formWeightOrder" action="#" method="post" autocomplete="off" @submit.prevent="update">
          <div class="modal-body">
            <div class="form-group" :class="{'has-error': errors['email'] }">
              <label for="email">shipper email</label>
              <span class="ml-2" data-animation="false" data-placement="right" data-toggle="tooltip" data-original-title="This would allow the system to automatically send an email to the shipper when the 'notified shipper' status is set in order management.">
                <i class="fa fa-question-circle"></i>
              </span>
              <input type="email" id="email" class="form-control" :class="{'form-control-danger': errors['email'] }" v-model="form.email">
              <div v-if="errors['email']" class="form-control-feedback">{{ errors['email'][0] }}</div>
            </div>

            <div class="form-group" :class="{'has-error': errors['display_name'] }">
              <label for="display_name">Display Name</label>
              <span class="ml-2" data-animation="false" data-placement="right" data-toggle="tooltip" data-original-title="This is the name your customers will see for each shipping method on check out. Please enter your name for this shipping method E.g. 'Express shipping' or 'Standard shipping (5-7 days)'">
                <i class="fa fa-question-circle"></i>
              </span>
              <input type="text" id="display_name" class="form-control" :class="{'form-control-danger': errors['display_name'] }" v-model="form.display_name">
              <div v-if="errors['display_name']" class="form-control-feedback">{{ errors['display_name'][0] }}</div>
            </div>

            <div class="form-group" :class="{'has-error': errors['charge_shipping'] }">
              <label for="charge_shipping">Charge Shipping</label>
              <select name="charge_shipping" id="charge_shipping" class="form-control" :class="{'form-control-danger': errors['charge_shipping'] }" v-model="form.charge_shipping">
                <option value="1">By weight</option>
                <option value="0">By order total</option>
              </select>
              <div v-if="errors['charge_shipping']" class="form-control-feedback">{{ errors['charge_shipping'][0] }}</div>
            </div>

            <hr>

            <strong>Ranges</strong>
            <div class="row">
              <div class="col-sm-3">
                <label>From</label>
              </div>
              <div class="col-sm-3">
                <label>To</label>
              </div>
              <div class="col-sm-3">
                <label>Cost</label>
              </div>
            </div>
            <div v-for="range, index in form.ranges" :key="index" class="row align-items-end row-item">
              <div class="col-sm-3 form-group" :class="{'has-error': errors['ranges.'+index+'.from'] }">
                <div class="input-group transparent">
                  <span v-if="form.charge_shipping == 0" class="input-group-addon">{{ currency }}</span>
                  <input type="text" class="form-control" :class="{'form-control-danger': errors['ranges.'+index+'.from'] }" v-model="range.from">
                  <span v-if="form.charge_shipping == 1" class="input-group-addon">{{ weightUnit }}</span>
                </div>
                <div v-if="errors['ranges.'+index+'.from']" class="form-control-feedback">{{ errors['ranges.'+index+'.from'][0] }}</div>
              </div>
              <div class="col-sm-3 form-group" :class="{'has-error': errors['ranges.'+index+'.to'] }">
                <div class="input-group transparent">
                  <span v-if="form.charge_shipping == 0" class="input-group-addon">{{ currency }}</span>
                  <input type="text" class="form-control" :class="{'form-control-danger': errors['ranges.'+index+'.to'] }" v-model="range.to">
                  <span v-if="form.charge_shipping == 1" class="input-group-addon">{{ weightUnit }}</span>
                </div>
                <div v-if="errors['ranges.'+index+'.to']" class="form-control-feedback">{{ errors['ranges.'+index+'.to'][0] }}</div>
              </div>
              <div class="col-sm-3 form-group" :class="{'has-error': errors['ranges.'+index+'.cost'] }">
                <div class="input-group transparent">
                  <span class="input-group-addon">{{ currency }}</span>
                  <input type="text" class="form-control" :class="{'form-control-danger': errors['ranges.'+index+'.cost'] }" v-model="range.cost">
                </div>
                <div v-if="errors['ranges.'+index+'.cost']" class="form-control-feedback">{{ errors['ranges.'+index+'.cost'][0] }}</div>
              </div>
              <div class="col-sm-3 form-group action-group">
                <button class="btn btn-action-add" type="button" @click.prevent="addRange(range.to)"><i class="aapl-plus"></i></button>
                <button v-if="form.ranges.length > 1" class="btn btn-action-delete" type="button" @click.prevent="removeRange(index)"><i class="aapl-trash2"></i></button>
              </div>
            </div>
        
          </div>
          <div class="modal-footer">
            <div class="form-group">
              <button type="button" class="btn btn-action-cancel"  data-dismiss="modal" :disabled="disabled">Cancel</button>
              <button type="submit" class="btn btn-action-save" :disabled="disabled">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

</template>

<script>

    import eventHub from '../../bus.js'

    export default {
        props: ['endpoint', 'currency', 'weight-unit', 'decimal', 'data'],
        data () {
           return {
              form: {
                email: '',
                display_name: 'Ship by Weight',
                charge_shipping: 1,
                ranges: []
              },
              errors: [],
              disabled: false,
           }
        },
        watch: {
          'form.charge_shipping': function(value) {
            
            var decimal = this.decimal;

            if(parseInt(value)) {
        
              decimal = 3;

            }

            this.refreshRanges(decimal);
        
          }
        },
        methods: {
          addRange(value) {
  
            var value = accounting.unformat(value);
            var range = {
              'from': accounting.formatNumber(value, 3, ','),
              'to': accounting.formatNumber((value + 1), 3, ','),
              'cost': accounting.formatNumber(10, this.decimal, ',')
            }

            this.form.ranges.push(range);
          },
          removeRange(index) {

            this.form.ranges.splice(index, 1);

          },
          refreshRanges(decimal) {

            this.form.ranges.forEach((range) => {

              range.from = accounting.formatNumber(range.from, decimal, ',');
              range.to = accounting.formatNumber(range.to, decimal, ',');

            });

          },
          update() {

            this.disabled = true;

            axios.patch(this.endpoint, this.form).then((response) => { 
              this.errors = [];
              this.disabled = false;
              $("#modalWeightOrder").modal('hide');
              $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: 'Ship by weight / order total saved.',
                position: 'top-right',
                timeout: 5000,
                type: "success"
             }).show();
            }).catch((error) => {
                this.errors = error.response.data;
                this.disabled = false;
            })
          }
        },
        mounted() {

          this.form.email = this.data.data.email;
          this.form.display_name = this.data.data.display_name;
          this.form.charge_shipping = this.data.data.charge_by;

          if(this.data.data.ranges.length) {

            this.form.ranges = this.data.data.ranges;

          } else {

            var range = {
              'from': accounting.formatNumber(0, 3, ','),
              'to': accounting.formatNumber(1, 3, ','),
              'cost': accounting.formatNumber(10, this.decimal, ',')
            }

            this.form.ranges.push(range);

          }
  
        },
    }

</script>
