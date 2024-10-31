<template>

	<div class="col-xl-6 col-lg-6 col-mb-6 col-sm-12 col-xs-12">
	   <div v-if="message" class="mt-4" id="mail-status"><p>{{ message }}</p></div>
       <div class="b-title b-title_line_right">
         <h2 class="text-uppercase">get in touch with us</h2>
       </div>
       <form action="#" method="post" autocomplete="off" @submit.prevent="send()">
       		<div class="clearfix row">
       			<div class="col-xl-6 col-lg-6 col-mb-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Your Name <i style="color: red;">*</i> 
						<span v-if="errors['name']" id="name-info" class="info">({{ errors['name'][0] }})</span>
                      </label>
                      <input type="text" id="name" v-model="form.name">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-mb-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Your Email <i style="color: red;">*</i>
						<span v-if="errors['email']" id="email-info" class="info">({{ errors['email'][0] }})</span>
                      </label>
                      <input type="email" id="email" v-model="form.email">
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-mb-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Subject</label>
                      <input type="text" id="subject" v-model="form.subject">
                    </div>
                </div>  
                <div class="col-xl-12 col-lg-12 col-mb-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>Your Message</label>
                      <textarea name="content" id="content" v-model="form.content"></textarea>
                    </div>
                </div>  
                <div class="col-xl-12 col-lg-12 col-mb-12 col-sm-12 col-xs-12">
                    <button type="submit" class="btn btn-bg text-white" :disabled="disabled">SEND A MESSAGE</button>
                </div>  
       		</div>
       	</form> 
    </div>

</template>

<script>

	export default {
		props: ['route'],
		data () {
			return {
				message: '',
				form: {
					name: '',
					email: '',
					subject: '',
					content: ''
				},
				errors: [],
				disabled: false
			}
		},
		methods: {
		    send () {
		   		this.disabled = true;
		        axios.post(this.route, this.form)
		        .then((response) => { 
		        	this.errors = [];
		        	this.form = {};
		           	this.message = response.data.status
		           	this.disabled = false;
		        }).catch((error) => {
		        	this.disabled = false;
		            this.errors = error.response.data;
		        })

		    }
		}
	}

</script>