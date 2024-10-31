<template>
  <div data-pages="card" class="card card-default card-custom">
    <div class="card-header">
      <div class="card-title"><p class="ui-subheader">Sales Channel</p>
      </div>
      <div class="card-controls">
        <ul>
          <li><a data-toggle="collapse" class="card-collapse" href="javascript:;"><i class="pg-arrow_maximize"></i></a>
          </li>
        </ul>
      </div>
    </div>
    <div class="card-block">
      <div class="row align-items-center mb-3">
        <div class="col-sm-6">
            <p>Available Quantity</p>
        </div>
        <div class="col-sm-2">
            
        </div>
        <div class="col-sm-4">
           <input type="text" id="master_qty" name="master_qty" value="0" class="form-control autonumeric" data-v-min="0" data-v-max="9999" @v-on:change.prevent="initStock()">
        </div>
      </div>
      <template v-if="stores.length">
        <div class="row align-items-end mb-3 stores" v-for="store in stores">
          <div class="col-sm-8">
              <p>{{ store.name }}</p>
          </div>
          <div class="col-sm-4">
             <input type="text" id="qty" :name="'qty['+store.id+'][qty]'" value="0" class="form-control autonumeric" data-v-min="0" data-v-max="9999">
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
    export default {
        props: ['data'],
        data () {
            return {
                stores: [],
                master_qty: 0,
                assigned: 0
            }
        },
        methods: {
          initStock () {
            console.log('called')
            $('.autonumeric').autoNumeric('update', {vMax: master_qty});
            $(".stores").each(function(index){
              var el = $(this).find('input');
              this.assigned += parseInt(el.val())
              console.log('assigned = '+this.assigned);
              el.change(function () {
                if(this.assigned > 0) {
                  var assignable = master_qty - assigned;
                  console.log('if = '+assignable)
                  $('.autonumeric').autoNumeric('update', {vMax: assignable}); 
                } else {
                  var assignable = master_qty - el.val();
                  console.log('else = '+assignable)
                  $('.autonumeric').autoNumeric('update', {vMax: assignable});  
                }
                
              })
            })
          }
        },
        mounted() {
          this.stores = this.data
        },
    }

</script>
