<template>
	
	<div class="container-fluid mb-3">
		<div class="row b-shop_head align-items-center justify-content-end"> 
		  <div class="col-xl-1 col-lg-1 col-mb-2 col-sm-4 col-xs-12">
			Sort By:
		  </div>
          <div class="col-xl-3 col-lg-3 col-mb-3 col-sm-4 col-xs-12"> 
            <select id="sort" v-model="sortBy" @change="sort">
            	<option value="newest">newly listed</option>
            	<option value="pricedesc">Price highest first</option>
            	<option value="priceasc">Price lowest first</option>
            	<option value="alphaasc">A to Z</option>
            	<option value="alphadesc">Z to A</option>
            </select>
          </div> 
        </div>
	</div>

</template>

<script>
	import bus from '../assets/bus.js'

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
		}
	}

</script>