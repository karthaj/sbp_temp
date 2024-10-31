<template>
	
<div class="container">
   <div class="row">
	    <div class="col-md-12">
	        <div class="single-product-review-and-description-area mt-60 mb-85">
	            <hr>
	            <div class="dec-and-review-menu text-center">
	                  <h5><b>DESCRIPTION</b></h5>   
	            </div>
	            <hr v-if="description"> 
	            <div v-if="description" class="single-product-description mt-20" v-html="description"></div>
	        </div>
	    </div>
 	</div>
</div>

</template>

<script>
	
export default {
	props: {
		description: {
			type: String
		}
	}
}

</script>