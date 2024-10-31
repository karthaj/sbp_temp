<template>

	<div class="input-group input-group bootstrap-timepicker timepicker">
	    <input type="text" class="form-control" :value="time" :placeholder="placeholder">
	    <div class="input-group-addon"><i class="pg-clock"></i></div>
	</div>

</template>

<script>

	export default {
		props: {
			placeholder: String,
			value: String
		},
		data () {
			return {
				time: ''
			}
		},
		mounted () {
			var vm = this;
     		vm.time = this.value;
            $(this.$el)
            .timepicker()
            .on('show.timepicker', function (e) {
            	var widget = $('.bootstrap-timepicker-widget');
		        widget.find('.glyphicon-chevron-up').removeClass().addClass('pg-arrow_maximize');
		        widget.find('.glyphicon-chevron-down').removeClass().addClass('pg-arrow_minimize');
                
            })
            .on('changeTime.timepicker', function (e) {
            	vm.$emit('applyTime', e.time.value);
            	vm.time = e.time.value;
            });
      
		}
	}

</script>