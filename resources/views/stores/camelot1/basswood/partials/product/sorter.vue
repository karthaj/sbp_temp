<template>
	
	<div class="toolbar-short-area clearfix">
         <div class="toolbar-shorter d-md-flex align-items-center">
            <label>Sort By:</label>
             <select id="sort" class="wide">
                <option value="newest">newly listed</option>
            	<option value="pricedesc">Price highest first</option>
            	<option value="priceasc">Price lowest first</option>
            	<option value="alphaasc">A to Z</option>
            	<option value="alphadesc">Z to A</option>
             </select>
         </div>
     </div>

</template>

<script>
	import bus from '../../assets/js/bus.js'

	export default {
		data () {
			return {
				sortBy: 'newest'
			}
		},
		methods: {
			sort () {
				bus.$emit('list-sorted', this.sortBy);
			}
		},
		mounted () {
			var vm = this;

			$('select').niceSelect()
	    	.on('change', function (e) {
	    		vm.sortBy = e.target.value;
	    		vm.sort();
	    	});
		}
	}

</script>