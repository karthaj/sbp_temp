<template>
	
<div class="modal fade slide-up" id="transferDetail" tabindex="-1" role="dialog" aria-hidden="false">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content-wrapper">
	    <div class="modal-content">
	      <div class="modal-header clearfix text-left">
	        <button class="btn btn-info btn-xs no-print" type="button"><i class="pg-printer"></i> print</button>
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
	        </button>
	        <h5><span class="semi-bold">Transfer</span>
	        </h5>
	      </div>
	      <div class="modal-body" v-if="loading">

	        <div class="row">
	          <div class="col">
	            <p class="font-weight-bold">Reference: {{ transfer.reference }}</p>
	          </div>
	          <div class="col">
	            <p class="font-weight-bold">Date: {{ transfer.created_at }}</p>
	          </div>
	        </div>

	        <div class="row">
	          <div class="col">
	            <p>From:</p>
	            <p>{{ transfer.from.store }}</p>
	            <p>{{ transfer.from.address }}</p>
	            <p v-if="transfer.from.phone">{{ transfer.from.phone }}</p>
	          </div>
	          <div class="col">
	            <p>To:</p>
	            <p>{{ transfer.to.store }}</p>
	            <p>{{ transfer.to.address }}</p>
	            <p v-if="transfer.to.phone">{{ transfer.to.phone }}</p>
	          </div>
	        </div>

	        <hr>
	    
	        <div class="table-responsive mb-2">
	          <table class="table">
	            <thead>
	              <tr>
	                <th>no</th>
	                <th width="10%">image</th>
	                <th>description</th>
	                <th>quantity</th>
	                <th>retail price</th>
	              </tr>
	            </thead>
	            <tbody>
	              <tr v-for="item, index in transfer.stocks">
	                <td>{{ index + 1 }}</td>
	                <td>
	                  <img :src="item.image" :alt="item.name" class="img-fluid">
	                </td>
	                <td>{{ item.name }}</td>
	                <td>{{ item.qty }}</td>
	                <td>{{ item.price }}</td>
	              </tr>
	            </tbody>
	            <tfoot>
	              <tr>
	                <td colspan="3" class="font-weight-bold text-right">Total Items</td>
	                <td class="font-weight-bold">{{ total }}</td>
	              </tr>
	            </tfoot>
	          </table>
	        </div>

	        <div class="row justify-content-between">
	          <div class="col-4">
	            <p>Created by:</p>
	            <p>&nbsp;</p>
	            <p>&nbsp;</p>
	            <hr>
	            <p>Stamp & Signature</p>
	          </div>
	          <div class="col-4">
	            <p>Received by:</p>
	            <p>&nbsp;</p>
	            <p>&nbsp;</p>
	            <hr>
	            <p>Stamp & Signature</p>
	          </div>
	        </div>

	      </div>  
	    </div>
	  </div>
	</div>
</div>

</template>

<script>
	import eventHub from '../../../bus.js'

	export default {
		 data () {
		 	return {
		 		transfer: {},
		 		loading: false
		 	}
		 },
		 computed: {
		 	total () {
              var total = 0;
              this.transfer.stocks.forEach((item) => {
                total += item.qty;
              });

              return total; 
  
            }
		 },
		 methods: {
		 	getRecord (transfer) {
		 		this.loading = false;
		 		axios.get('/merchant/store/transfers/'+transfer).then((response) => { 
                    this.transfer = response.data.data;
                    this.loading = true;
                    $("#transferDetail").modal('show');
                })
		 	}
		 },
		 mounted() {
            eventHub.$on('transfer', this.getRecord)
         },
	}

</script>