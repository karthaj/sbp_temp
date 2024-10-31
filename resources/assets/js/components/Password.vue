<template>
    
    <div v-if="!loading" class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="question_title">
                            <h3>Almost there</h3>
                            <p>Create your <strong>password</strong> below</p>
                        </div>

                        <form @submit.prevent="submit">
                            <div class="step">
                                <div class="row justify-content-md-center">
                                    
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="password">Password:</label>
                                            <input type="password" class="form-control" v-model="$v.password.$model" :class="{ 'is-invalid': $v.password.$error }">
                                            <span v-if="!$v.password.required" class="invalid-feedback">
                                                <strong>Password is required.</strong>
                                            </span>
                                            <span v-if="!$v.password.minLength" class="invalid-feedback">
                                                <strong>Password must be at least {{$v.password.$params.minLength.min}} characters</strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label for="rpwd">Repeat Password:</label>
                                            <input type="password" v-model="$v.confirm_password.$model" class="form-control" :class="{ 'is-invalid': $v.confirm_password.$error }">
                                            <span v-if="!$v.confirm_password.sameAs" class="invalid-feedback">
                                                <strong>Password confirmation does not match.</strong>
                                            </span>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div id="bottom-wizard">
                                <button type="submit" class="btn btn-dark forward">Continue</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div> 
        </div>
    </div>
    <div v-else-if="loading" class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <div class="question_title">
                            <h3>Please Wait</h3>
                            <p>Creating your <strong>account</strong> on platform</p>
                        </div>

                        <div class="col-md-0 text-center">
                            <img class="text-center img-fluid" src="../../../../public/assets/img/comp-2.gif" width="300">
                        </div>
                        <!--<div class="progress">
                          <div class="progress-bar" role="progressbar" :style="{'width': value+'%'}" :aria-valuenow="value" aria-valuemin="0" aria-valuemax="100">{{ label }}</div>
                        </div>-->
                        <!-- <progress-bar size="medium" :val="value" :text="label" bar-color="#000000"/> -->
                    </div>
                </div>
            </div>
        </div> 
    </div>

</template>

<script>
    
    import bus from '../bus'
    import ProgressBar from 'vue-simple-progress'
    import { required, minLength, sameAs } from 'vuelidate/lib/validators'

    export default {
        props: ['formData'],
        components: {
            ProgressBar
        },
        data () {
            return {
                form: {},
                password: '',
                confirm_password: '',
                loading: false,
                timeout: '',
                value: 0,
                label: 'Creating user account' 
                
            }
        },
        validations: {
            password: {
                required,
                minLength: minLength(6)
            },
            confirm_password: {
                sameAs: sameAs('password')
            }
        },
        // watch: {
        //     // whenever question changes, this function will run
        //     value: function (value) {
              
        //       if(value === 100) {

        //         this.timeout = clearInterval(this.timeout);
        //         this.store();
        //       }

        //     }
        // },
        methods: {
            submit () {

                this.$v.$touch()
                if (!this.$v.$invalid) {
                   this.loading = true;
                   this.form['password'] = this.password;
                   this.store();
                   // this.timeout = setInterval(this.progress, 2000);
                } 
                
            }, 
            mergeData () {

                this.form['email'] = this.formData.email;
                this.form['first_name'] = this.formData.first_name;
                this.form['last_name'] = this.formData.last_name;
                this.form['phone'] = this.formData.phone;
                this.form['store_name'] = this.formData.store_name;
                this.form['domain'] = this.formData.store_domain;
                this.form['address1'] = this.formData.address1;
                this.form['address2'] = this.formData.address2;
                this.form['country'] = this.formData.country;
                this.form['state'] = this.formData.state;
                this.form['city'] = this.formData.city;
                this.form['postal_code'] = this.formData.postal_code;
            
            },
            // progress () {

            //     this.value += 10;

            //     if(this.value == 10)  {
            //         this.label = 'Creating store';
            //     } else if (this.value == 20) {
            //         this.label = 'Creating store loation'
            //     } else if (this.value == 30) {
            //         this.label = 'Creating default tax class'
            //     } else if (this.value == 40) {
            //         this.label = 'Creating default shipping class'
            //     } else if (this.value == 50) {
            //         this.label = 'Creating default page'
            //     } else if (this.value == 60) {
            //         this.label = 'Creating default categories'
            //     } else if (this.value == 70) {
            //         this.label = 'Creating default variant set'
            //     } else if (this.value == 80) {
            //         this.label = 'Setting up default payment methods'
            //     } else if (this.value == 90) {
            //         this.label = 'Installing default theme'
            //     } else if (this.value == 100) {
            //         this.label = 'Finalizing setup'
            //     }
                
            // },
            store () {
                axios.post('/merchant/register', this.form)
                .then(function (response) {
                    window.location.href = response.data.redirect_to;
                    // setTimeout(function(){ 
                    //     window.location.href = response.data.redirect_to;
                    // }, 2000);
                })
                .catch(function (error) {
                    return;
                });
            }
        },
        mounted() {
            this.mergeData();
        }
    }
</script>
