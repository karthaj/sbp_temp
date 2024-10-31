<template>
	
<div class="text-center mt-5" 
>
  <a href="#" class="btn btn-action-delete" @click.prevent="reset">Reset to default</a>
</div>

</template>

<script>

import Vue from 'vue';	
import VuejsDialog from 'vuejs-dialog';
import 'vuejs-dialog/dist/vuejs-dialog.min.css'

Vue.use(VuejsDialog, {
  html: true,
  loader: true,
  okText: 'Proceed',
  cancelText: 'Cancel',
});

export default {

	props: {
		endpoint: {
			type: String,
			required: true
		}
	},
	methods: {
		reset() {
			var vm = this;

			this.$dialog
			  .confirm('Please confirm to continue')
			  .then(function(dialog) {
			    console.log('reset')
			    axios.post(vm.endpoint).then((response) => { 
			    	dialog.close();
		            window.location = response.data.redirect;
		        }).catch((error) => {
		            console.log(error.response)
		        })

			  })
			  .catch(function() {
			    //
			  });
		}
	}
}

</script>