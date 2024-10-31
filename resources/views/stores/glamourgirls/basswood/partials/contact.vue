<template>

<div class="row">
  <div class="col-lg-4 col-md-4" style="padding-top: 35px;">
      <div class="store-information">
          <div class="store-title">
              <h4 v-if="section.settings.title">{{ section.settings.title }}</h4>
              <div class="communication-info">

                  <div v-if="section.settings.address" class="single-communication">
                      <div class="communication-icon">
                          <i class="zmdi zmdi-pin"></i>
                      </div>
                      <div class="communication-text">{{ section.settings.address }}</div>
                  </div>

                  <div v-if="section.settings.phone" class="single-communication">
                      <div class="communication-icon">
                          <i class="zmdi zmdi-phone"></i>
                      </div>
                      <div class="communication-text">{{ section.settings.phone }}</div>
                  </div>

                  <div v-if="section.settings.email" class="single-communication">
                      <div class="communication-icon">
                          <i class="zmdi zmdi-email"></i>
                      </div>
                      <div class="communication-text">{{ section.settings.email }}</div>
                  </div>

              </div>
          </div>
      </div>
  </div>
  <div v-if="enableForm" class="col-lg-8 col-md-8">
    <div class="content-wrapper">
        <div class="page-content">
            <div class="contact-form">
                <div class="contact-form-title">
                    <h3>Contact us</h3>
                </div>
                <form action="#" method="post" autocomplete="off" @submit.prevent="send()">
                  <div class="row">
                      <div class="col-lg-6">
                          <div class="form-group contact-form-style mb-20">
                              <input type="text" id="name" class="form-control" :class="{'is-invalid': errors['name']}" placeholder="Name" v-model="form.name">
                              <div v-if="errors['name']" class="invalid-feedback">{{ errors['name'][0] }}</div>
                          </div>
                      </div>
                      <div class="col-lg-6">
                          <div class="form-group contact-form-style mb-20">
                              <input type="email" id="email" class="form-control" :class="{'is-invalid': errors['name']}"placeholder="Email Address" v-model="form.email">
                              <div v-if="errors['email']" class="invalid-feedback">{{ errors['email'][0] }}</div>
                          </div>
                      </div>
                      <div class="col-lg-12">
                          <div class="form-group contact-form-style mb-20">
                              <input type="text" id="subject" class="form-control" placeholder="Subject" v-model="form.subject">
                          </div>
                      </div>
                      <div class="col-lg-12">
                          <div class="form-group contact-form-style">
                              <textarea name="content" id="content" class="form-control" placeholder="Message" v-model="form.content"></textarea>
                              <button class="default-btn" type="submit" :disabled="disabled"><span>SEND MESSAGE</span></button>
                          </div>
                      </div>
                  </div>
                </form>
                <p v-if="message" class="form-messege">{{ message }}</p>
            </div>
        </div>
    </div>
  </div>
</div>

</template>

<script>
  
  import settings from '../assets/js/settings.js'

	export default {
		props: {
      route: {
        type: String,
        required: true
      },
      enableForm: {
        type: Number,
        required: true
      }
    },
    mixins: [settings],
		data () {
			return {
        schema: {
          "name": "Contact",
          "section": "contact", 
          "type": "contact",
          "settings": [
            {
              "type": "text",
              "id": "title",
              "label": "Title"
            },
            {
              "type": "text",
              "id": "address",
              "label": "Address"
            },
            {
              "type": "text",
              "id": "phone",
              "label": "Phone"
            },
            {
              "type": "text",
              "id": "email",
              "label": "Email"
            }
          ]
        },
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
    computed: {
      section: {
          set: function (settings) {
              return this.settings.sections.contact = settings;
          },
          get: function () {
              return this.settings.sections.contact; 
          }
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
		},
    mounted () {
      var vm = this;

      parent.postMessage(this.schema, '*');
          
      window.addEventListener('message', function (e) {
          if (e.data && e.data.sections && e.data.sections.contact) {
    
            vm.section = e.data.sections.contact;

          }
      })
    }
	}

</script>