<template>

	<div class="b-product_grid_action">
     	<a href="javascript:void(0);" data-toggle="modal" data-target="#b-qucik_view" :data-src="endpoint">
         	<i data-toggle="tooltip" data-placement="left" title="" class="aapl-zoom-in icons" data-original-title="Quick View"></i>
       	</a>
   	</div>

</template>

<script>

	export default {
		props: ['endpoint']
	}

</script>