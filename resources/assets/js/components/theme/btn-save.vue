<template>
	
	<button type="button" class="btn btn-action-save" @click.prevent="submit">Save</button>

</template>

<script>
	import { mapGetters } from 'vuex'
	import frame from '../../frame'

	export default {
		props: ['endpoint'],
		computed: {
	        ...mapGetters({

	            settings: 'settings'

	        })
    	},
		methods: {
			submit () {
			
				axios.post(this.endpoint, {
					settings: this.settings
				}).then((response) => { 
		        	$(frame).attr('src', $(frame).attr('src'));
		        	$('.page-content-wrapper').pgNotification({
		              style: 'simple',
		              message: 'Changes saved successfully!',
		              position: 'top-right',
		              timeout: 5000,
		              type: "success"
			        }).show();
		        }).catch((error) => {
		            console.log(error.response)
		        })
			}
		}
	}
</script>