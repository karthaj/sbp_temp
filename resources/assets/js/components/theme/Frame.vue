<template>
	
<div class="preview-content">
	<iframe id="tpframe" class="template-frame preview-iframe--desktop" scrolling="yes" sandbox="allow-same-origin allow-forms allow-popups allow-scripts allow-modals" tabindex="-1" :src="src" @load="onLoad">
	</iframe>
</div>

</template>

<script>

import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
	props: {
		src: {
			type: String,
			required: true
		}
	},
	components: {
        Loading
    },
	data () {
		return {
			isLoading: true
		}
	},
	methods: {
		onLoad  () {
			 $("#tpframe").on('load', function(){
			      $(this).contents().find('a').on("click", function(e){
			          var url = $(this).attr('href');
			          $(this).attr('href', url+'?preview=true');
			         
			          window.location.href = window.location.pathname + '?page='+$(this).prop('pathname');
			      });
			  });
		},
	},
	updated () {
		console.log('as;asdd')
	}

}

</script>