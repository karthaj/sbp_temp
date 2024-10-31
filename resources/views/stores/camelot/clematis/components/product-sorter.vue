<template>
	
<div class="toolbox elemento_stick">
	<div class="container">
        <ul class="clearfix">
            <li>
                <div class="sort_select">
                    <select id="sort" v-model="sortBy" @change.prevent="sort">
                        <option value="newest">Newly listed</option>
		            	<option value="pricedesc">Price highest first</option>
		            	<option value="priceasc">Price lowest first</option>
		            	<option value="alphaasc">A to Z</option>
		            	<option value="alphadesc">Z to A</option>
                    </select>
                </div>
            </li>
        </ul>
    </div>
</div>

</template>

<script>
	import bus from '../assets/js/bus.js'

	export default {
		data () {
			return {
				sortBy: 'newest'
			}
		},
		methods: {
			sort () {
				bus.$emit('list.sort', this.sortBy);
			}
		}
	}

</script>