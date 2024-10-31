<template>
	
	<div class="form-group input-group colorpicker-component" :data-color="dataColor">
        <input type="text" class="form-control color-picker">
        <span class="input-group-addon">
          <i></i>
        </span>
    </div>

</template>

<script>
	
	import bus from '../../../bus'

	export default {
        props: {

            color: {
                type: String,
                default: '#000000'
            }
            
        },
        data () {
            return {
                dataColor: this.color
            }
        },
        mounted() {
        	var vm = this;

        	window.$(this.$el).colorpicker()
            .on('colorpickerChange', function (e) {
            	vm.$emit('apply', e.color.toHexString());
                if(e.color === false) {
                    $(".colorpicker-component").colorpicker('setValue', '#000000');
                } 
            });
			
        }
    }

</script>