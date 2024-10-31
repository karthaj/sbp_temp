<template>
	
<div class="m-0 row card-block pb-0">
    <div class="col-lg-7 mx-auto sm-no-padding">
      	<div class="row">
          	<div class="col-lg-12">
             	<div class="card card-transparent">
	                <div class="card-block no-padding">
	                  <div class="card card-default">
	                    <div class="card-block">
	                    	<div v-if="step_verify" class="card-block">
		                    	<ul class="list-group list-group-flush">
								  	<li class="list-group-item d-flex justify-content-between align-items-center">
								  		{{ domain }}
								  		<a href="#" @click.prevent="edit">Edit</a>
								  	</li>
								</ul>
		                    </div>
	                    	<form v-else action="#" @submit.prevent="submit">
	                    		<div class="form-group" :class="{'has-danger': errors['domain']}">
		                         	<label for="domain">Domain</label>
			                        <input type="text" class="form-control" :class="{'form-control-danger': errors['domain']}" v-model="domain" placeholder="e.g. demo.com">
			                        <div v-if="errors['domain']" class="form-control-feedback">{{ errors['domain'][0] }}</div>
		                      	</div>
		                      	<div class="form-group mt-4 text-right">
			                        <button type="submit" class="btn btn-action-add" :disabled="!domain">Next</button>
		                      	</div>      
	                    	</form>
	                    </div>
	                  </div>
	                </div>
              	</div>    
          	</div>
      	</div>
    </div>

    <div v-if="step_verify" class="col-lg-7 mx-auto sm-no-padding">
      <div class="row">
          <div class="col-lg-12">
             <div class="card card-transparent">
                <div class="card-block no-padding">
                  <div class="card card-default">
                    <div class="card-block">
                    	<div v-if="loading" class="progress-circle-indeterminate"></div>
                    	<template v-else-if="response">
                    		<div v-if="response.status" class="d-flex justify-content-between align-items-center">
                    			<h6 class="font-weight-bold text-uppercase">Domain verified</h6>
                    			<button type="button" class="btn btn-action-add" @click.prevent="store" :disabled="disabled">Add</button>
                    		</div>
                    		<template v-else>
                    			<h6 class="font-weight-bold text-uppercase">Domain verification failed</h6>
	                    		<p class="font-weight-bold text-uppercase">a record</p>
	                    		<p>Current ip: {{ response.dns_ip }}</p>
	                    		<p>Required ip: {{ response.required_ip }}</p>
	                    		<div class="form-group mt-4 text-right">
									<button type="button" class="btn btn-action-add" @click.prevent="verify" :disabled="disabled">Verify domain</button>
								</div>  
                    		</template>
                    	</template>
                    	<div v-else-if="error" class="alert alert-danger">Error occured when communicating with the server.</div>
                    	<div v-else-if="success" class="alert alert-success">
                    		<p>Domain added successfully.</p>
                    		<p>{{ text }}</p>
                    	</div>
                    	<template v-else>
                    		<h6 class="font-weight-bold">Instructions for connecting your domain</h6>
							<p>1. Log in to your account control panel from your domain provider (If no control panel is available, please contact your domain provider with the instructions below).</p>
							<p>2. Select the option to change/edit DNS settings for the domain you wish to connect to your ShopBox store.</p>
							<p>3. Add an A Record (Address Record) and set the name as @ and set the value as 149.28.141.168 for the A record. Make sure that there are no duplicate or conflicting A-records as this would cause resolving issues (Always make note of records before deleting).</p>
							<p>4. If  TTL field is available, set the TTL to 300 seconds (or the smallest value allowed).</p>
							<p>5. Save the A Record</p>
							<p>Note: Any changes may take 24-48 hours to resolve.</p>   

							<div class="form-group mt-4 text-right">
								<button type="button" class="btn btn-action-add" @click.prevent="verify" :disabled="disabled">Verify domain</button>
							</div>   
                  		</template>
                    </div>
                  </div>
                </div>
              </div>    
          </div>
      </div>
    </div>

</div>

</template>

<script>
	
export default {

	data () {
		return {
			loading: false,
			disabled: false,
			step_verify: false,
			success: false,
			domain: '',
			errors: [] ,
			error: '',
			response: '' ,
			text: 'Generating ssl certificate...'
		}
	},
	methods: {
		submit () {
			axios.post('/merchant/settings/domain/connect', {
				domain: this.domain
			}).then((response) => {
                this.step_verify = true;
                this.errors = [];
            }).catch((error) => {  
            	this.errors = error.response.data;
            });
		},
		edit () {
			
			this.errors = [];
			this.response = '';
			this.error = '';
			this.loading = false;
			this.step_verify = false;
			this.success = false;

		},
		verify () {

			this.loading = true;
			this.errors = [];
			this.disabled = true;
			axios.get(`/merchant/settings/domain/verify?domain=${this.domain}`).then((response) => {
				this.loading = false;
				this.disabled = false;
				this.error = '';
				this.success = false;
				this.response = response.data; 
            }).catch((error) => { 
            	this.loading = false; 
            	this.success = false;
            	this.response = '';
            	this.error = error.response.data;
            });
			
		},
		store () {
			this.disabled = true;

			axios.post('/merchant/settings/domain/add', {
				domain: this.domain
			}).then((response) => {
				this.success = true;
				this.loading = false;
				this.disabled = false;
				this.error = '';
				this.response = ''; 
				this.text = 'Generating ssl certificate...';
				this.generateSSL();
            }).catch((error) => { 
            	this.disabled = false;
            	this.loading = false; 
            	this.success = false;
            	this.response = '';
            	this.text = '';
            	this.error = error.response.data;
            });
		},
		generateSSL () {
			axios.post('/merchant/settings/domain/ssl/generate', {
				domain: this.domain
			}).then((response) => {
				this.success = true;
				this.loading = false;
				this.disabled = false;
				this.error = '';
				this.response = ''; 
				this.text = 'Visit store at '+response.data.url;
            }).catch((error) => { 
            	this.disabled = false;
            	this.loading = false; 
            	this.success = false;
            	this.response = '';
            	this.text = '';
            	this.error = error.response.data;
            });
		}
	}

}

</script>