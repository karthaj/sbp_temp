<template>

	<div class="input-group date">
	    <input type="text" class="form-control" :placeholder="placeholder" :value="value">
	    <span class="input-group-addon">
	      <i class="fa fa-calendar"></i>
	    </span>
	</div>

</template>

<script>

	export default {
		props: {
			startDate: {
	            default: function () {
	                return new Date();
	            }
	        },
	        value: String,
	        format: {
	        	default: function () {
	        		return 'yyyy-mm-dd'
	        	}
	        },
	        toggleActive: {
	        	default: function () {
	        		return true
	        	}
	        },
	        placeholder: String
		},
		mounted () {
			var vm = this;
            var options = {
                startDate: this.startDate,
              	format: this.format,
              	autoclose: true,
              	toggleActive: true
            };

            $(this.$el)
            .datepicker(options)
            .on('changeDate', function (e) {
                vm.$emit('apply', e.format('yyyy-mm-dd'));
            })
		}
	}

</script>