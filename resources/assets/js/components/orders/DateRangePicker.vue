<template>
	
	<div class="form-group">
		<label v-if="showLabel" for="daterange">date</label>
		<div class="input-prepend input-group">
	      <span class="add-on input-group-addon"><i
			class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
	      <input type="text" id="daterange" class="form-control" autocomplete="off" placeholder="Date" :value="dateRange">
	    </div>
	</div>
	
</template>

<script>

	export default {
		props: {
			showRanges: {
	            type: Boolean,
	            default: false
	        },
	        minDate: {
	            default: false
	        },
	        opens: {
	            default: 'right'
	        },
	        maxDate: {
	            default: false
	        },
	        autoApply: {
	            default: false
	        },
	        showLabel: {
	        	default: true
	        }
		},
		data: function () {
	        return {
	            start: '',
	            end: ''
	        };
	    },
	    computed: {
	        dateRange: function () {
	        	if(!this.start || !this.end) {
	        		return ''; 
	        	}
	            var start = moment(this.start);
	            var end = moment(this.end);
	            var today = moment();
	            if (
	                start.format('LL') === end.format('LL') &&
	                today.format('LL') === start.format('LL')
	            ) {
	                return 'Today';
	            } else if (start.format('MM-DD-YYYY') === end.format('MM-DD-YYYY')) {
	                return start.format('LL');
	            }

	            return start.format('LL') + ' - ' + end.format('LL');
	        }
	    },
		mounted () {
			var vm = this;
	        this.$nextTick(function () {
	            var options = {
	                opens: this.opens,
	                autoApply: this.autoApply
	            };

	            if (this.minDate) {
	                options.minDate = this.minDate;
	            }
	            if (this.maxDate) {
	                options.maxDate = this.maxDate;
	            }

	            if (this.showRanges) {
	                options.ranges = {
	                    Today: [moment(), moment()],
	                    Yesterday: [
	                        moment().subtract(1, 'days'),
	                        moment().subtract(1, 'days')
	                    ],
	                    'Last 7 Days': [
	                        moment().subtract(6, 'days'),
	                        moment()
	                    ],
	                    'Last 30 Days': [
	                        moment().subtract(30, 'days'),
	                        moment()
	                    ],
	                    'This Month': [
	                        moment().startOf('month'),
	                        moment().endOf('month')
	                    ],
	                    'Last Month': [
	                        moment().subtract(1, 'month').startOf('month'),
	                        moment().subtract(1, 'month').endOf('month')
	                    ]
	                };
	            }

	            window.$(this.$el)
                .daterangepicker(options)
                .on('apply.daterangepicker', function (e, picker) {
                    vm.$emit('apply', picker.startDate.format('YYYY-MM-DD H:m:s'), picker.endDate.format('YYYY-MM-DD H:m:s'));
                    vm.start = picker.startDate;
                    vm.end = picker.endDate;
                });
	        });

		}
	}

</script>