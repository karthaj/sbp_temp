<template>
	
<button type="button" class="btn btn-default"><i class="aapl-calendar-full"></i> {{ range }}</button>

</template>

<script>
	
import bus from '../bus'

export default {
	data () {
		return {
			range_from: moment().subtract(7, 'days'),
			range_to: moment().subtract(1, 'days')
		}
	},
	computed: {
		range () {
			return this.range_from.format('MMM D, YYYY') + ' - ' + this.range_to.format('MMM D, YYYY');
		}
	},
	methods: {
		getPeriod (startDate, endDate) {

			var diff = endDate.diff(startDate, 'days');
	
			if(diff === 0) {
				return 'hour';
			} else if(diff < 51) {
				return 'day';
			} else if(diff > 51) {
				return 'month';
			}

		}
	},
	mounted () {

		var vm = this;

		$(this.$el).daterangepicker({
			startDate: vm.range_from.format('YYYY-MM-DD'),
			endDate: vm.range_to.format('YYYY-MM-DD'),
			alwaysShowCalendars: true,
			autoApply: false,
			locale: {
		      format: 'YYYY-MM-DD'
		    },
			ranges: {
	           'Today': [moment(), moment()],
	           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	           'Last 7 days': [moment().subtract(7, 'days'), moment().subtract(1, 'days')],
	           'Last 30 days': [moment().subtract(30, 'days'), moment().subtract(1, 'days')],
	           'Last 90 days': [moment().subtract(90, 'days'), moment().subtract(1, 'days')],
	           'Last month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
	           'Last year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
	           'Week to date': [moment().startOf('isoWeek'), moment()],
	           'Month to date': [moment().startOf('month'), moment()],
	           'Quarter to date': [moment().startOf('quarter'), moment()],
	           'Year to date': [moment().startOf('year'), moment()]
	        }
		}).on('apply.daterangepicker', function(ev, picker) {

			vm.range_from = picker.startDate;
			vm.range_to = picker.endDate;

			var range = picker.startDate.format('YYYY-MM-DD') + ',' + picker.endDate.format('YYYY-MM-DD');
			var period = vm.getPeriod(picker.startDate, picker.endDate);

			bus.$emit('daterange.apply', {
				range: range,
				period: period
			});

		});
	}
}

</script>