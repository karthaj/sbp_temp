<template>
	<div id="exportModal" class="modal fade slide-up" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog ">
          <div class="modal-content-wrapper">
            <div class="modal-content">
              <div class="modal-header clearfix text-left">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                </button>
                <h5><span class="semi-bold">Export Orders</span></h5>
              </div>
              <div class="modal-body">
              	<div class="form-group">
                	<label for="date_range">Date Range</label>
                	<select id="date_range" class="form-control" v-model="dateRange" @change="toggleRange">
                        <option value="">-- Choose an Order Date --</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="week">Last 7 Days</option>
                        <option value="month">Last 30 days</option>
                    </select>
                </div>
 				<!-- <div class="radio radio-info">
                  <input id="all" type="radio" value="all" name="exportby" v-model="exportby">
                  <label for="all">All orders</label>
                </div>
                <div class="radio radio-info">
                	<input id="date_range" type="radio" checked="checked" value="date_range" name="exportby" v-model="exportby">
                  	<label for="date_range">Date range</label>
                </div>
                <transition name="fade">
	                <date-range-picker 
	                	v-if="exportby === 'date_range'" 
	                    @apply="onDateChange"
	                    :show-ranges="true"
	                >
	                </date-range-picker>
	            </transition> -->
	            <div v-if="message" class="alert alert-info">{{ message }}</div>
	            <div class="form-group text-right">
	            	<button v-if="loading" class="btn btn-action-save btn-xs" type="button" disabled="disabled">
	            		<div class="progress-circle-indeterminate progress-circle-complete" style="width: 20px; height: 20px;">
                        </div>
                    </button>
                    <button v-else class="btn btn-action-save btn-xs" type="button" @click.prevent="submit" :disabled="!dateRange">Export</button>
	            </div>
              </div>
            </div>
          </div>
        </div>
  	</div>
</template>

<script>
	import DateRangePicker from './DateRangePicker.vue'

	export default {
		components: {
			DateRangePicker
		},
		data () {
			return {
				exportby: 'all',
				dateRange: '',
				date: '',
				loading: false,
				store: {},
				message: ''
			}
		},
		methods: {
			toggleRange () {
				if(this.dateRange === 'today') {
			      	this.date = moment().format('YYYY-MM-DD 0:0:0')+'|'+moment().format('YYYY-MM-DD 23:59:59');
		      	} else if(this.dateRange === 'yesterday') {
			      	this.date = moment().subtract(1, 'days').format('YYYY-MM-DD 0:0:0')+'|'+moment().subtract(1, 'days').format('YYYY-MM-DD 23:59:59');
		      	} else if(this.dateRange === 'week') {
			      	this.date = moment().subtract(6, 'days').format('YYYY-MM-DD 0:0:0')+'|'+moment().format('YYYY-MM-DD 23:59:59');
		      	} else if(this.dateRange === 'month') {
			      	this.date = moment().subtract(30, 'days').format('YYYY-MM-DD 0:0:0')+'|'+moment().format('YYYY-MM-DD 23:59:59');
		      	}
			},
			onDateChange (startDate, endDate) {
              this.date = startDate+'|'+endDate;
            },
            submit () {
            	this.loading = true;

            	axios.post('/merchant/orders/export', {
            		// exportby: this.exportby,
            		date: this.date	
            	}).then((response) => {
            		if(response.data && response.data.status === 'success') {
            			this.message = 'Your Orders export is currently being processed. Once the export is complete, your order file will be emailed to ' + this.store.storeEmail;
            			this.loading = false;
            			return;
            		}
                $('#exportModal').modal('hide');
            		this.message = 'Your Orders export generated successfully.';
            		window.location = `/merchant/orders/${this.store.storeId}/download/export`;
            	}).catch((error) => {
            		if(error.response && error.response.data.status === 'failed') {
            			this.message = 'No orders matched your search criteria. Please try again.';
            			this.loading = false;
            		}
            	})
            }
		},
		mounted () {
			let vm = this;

			$('#exportModal').on('hidden.bs.modal', function (e) {
			  vm.loading = false;
			  vm.exportby = 'all';
			  vm.date = '';
			  vm.message = '';
			  vm.dateRange = '';
			})

			this.store = JSON.parse(document.querySelector('script[data-serialized-id="store-details"]').innerHTML).store;
		}
	}
</script>