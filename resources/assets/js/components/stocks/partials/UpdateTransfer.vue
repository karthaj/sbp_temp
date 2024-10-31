<template>
	
<div class="modal fade slide-up" id="transferSummary" tabindex="-1" role="dialog" aria-hidden="false">
	<div class="modal-dialog">
	  <div class="modal-content-wrapper">
	    <div class="modal-content">
	      <div class="modal-header clearfix text-left">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
	        </button>
	        <h5><span class="semi-bold">Transfer Status</span>
	        </h5>
	      </div>
	      <div class="modal-body" v-if="loading">
			<div class="card border-dark mb-3">
			  <div class="card-header">Summary</div>
			  <div class="card-body">
			    <table class="table table-condensed table-striped table-borderless">
	                <tbody>
	                    <tr>
	                        <td>Reference No</td>
	                        <td>{{ transfer.reference }}</td>
	                    </tr>
	                    <tr>
	                        <td>From</td>
	                        <td>{{ transfer.from }}</td>
	                    </tr>
	                    <tr>
	                        <td>To</td>
	                        <td>{{ transfer.to }}</td>
	                    </tr>
	                    <tr>
	                        <td>Status</td>
	                        <td>
	                        	<strong v-if="transfer.status === 1">Complete</strong>
	                        	<strong v-else-if="transfer.status === 0">Pending</strong>
	                        	<strong v-else-if="transfer.status === 2">Rejected</strong>
	                        </td>
	                    </tr>
	                </tbody>
	            </table>
			  </div>
			</div>
			<div class="form-group" v-if="transfer.status === 0">
				<label for="action">Action</label>
				<select id="action" class="form-control" v-model="status">
					<option value="0">Select action</option>
					<option value="1">Accept & Transfer</option>
					<option value="2">Reject</option>
				</select>
			</div>
			<p v-if="transfer.remarks">Remarks</p>
			<p v-if="transfer.remarks">{{ transfer.remarks }}</p>
	      </div>
	      <div class="modal-footer" v-if="transfer.status === 0">
	      	<button type="button" class="btn btn-info" @click="update()" :disabled="status == 0">Update</button>
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
		 		loading: false,
		 		status: 0
		 	}
		 },
		 methods: {
		 	getRecord (transfer) {
		 		this.loading = false;
		 		axios.get('/merchant/store/transfers/'+transfer+'/status').then((response) => { 
                    this.transfer = response.data.data;
                    this.loading = true;
                    $("#transferSummary").modal('show');
                })
		 	},
		 	notify () {
		 		$('.page-content-wrapper').pgNotification({
                    style: 'simple',
                    message: 'Transfer updated Successfully.',
                    position: 'top-right',
                    timeout: 5000,
                    type: "success"
                }).show();
		 	},
		 	update() {
		 		axios.post('/merchant/store/transfers/'+this.transfer.reference+'/status', {
		 			status: this.status
		 		}).then((response) => { 
                    eventHub.$emit('transfer.updated');
                    $("#transferSummary").modal('hide');
                    this.notify();
                })
		 	}
		 },
		 mounted() {
            eventHub.$on('transfer.update', this.getRecord)
         },
	}

</script>