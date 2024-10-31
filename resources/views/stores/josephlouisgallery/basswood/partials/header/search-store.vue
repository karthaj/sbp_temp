<template>
	
<div class="main-search-active">
	<div class="sidebar-search-icon">
	    <button class="search-close"><span class="zmdi zmdi-close"></span></button>
	</div>
	<div class="sidebar-search-input">
	    <form action="/search" method="get" role="search">
	        <div class="form-search">
	            <input id="search" class="input-text" name="q" placeholder="Search products" type="search" autocomplete="off">
	            <button>
	                <i class="zmdi zmdi-search"></i>
	            </button>
	        </div>
	    </form>
	</div>
</div>

</template>

<script>
	
export default {
	
}

</script>