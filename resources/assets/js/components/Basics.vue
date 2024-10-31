<template>
    
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-10">
                <div class="card card-register">
                    <div class="card-body">
                        <div class="question_title">
                            <h3>Register for your ShopBox account</h3>
                            <p>Please provide a few <strong>details</strong> to help us <strong>get to know you better</strong>.</p>
                        </div>

                        <form autocomplete="off" @submit.prevent="store">
                            <div class="step">
                                <div class="row justify-content-md-center">
                                    <div class="col-md-6">
                                        <div class="form-group row"> 
                                            <div class="col-md-12">
                                                <input id="first_name" type="text" v-model.trim="$v.form.first_name.$model" class="form-control" placeholder="First Name" :class="{ 'is-invalid': $v.form.first_name.$error }">
                                                <span v-if="!$v.form.first_name.required" class="invalid-feedback">
                                                    <strong>First Name is required</strong>
                                                </span>
                                                <span v-if="!$v.form.first_name.maxLength" class="invalid-feedback">
                                                    <strong>First Name may not be greater than {{$v.form.first_name.$params.maxLength.max}} characters</strong>
                                                </span>
                                            </div>
                                        </div> 
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                 <input id="last_name" type="text" v-model.trim="$v.form.last_name.$model" class="form-control" placeholder="Last Name" :class="{ 'is-invalid': $v.form.last_name.$error }">
                                                 <span v-if="!$v.form.last_name.required" class="invalid-feedback">
                                                    <strong>Last Name is required</strong>
                                                </span>
                                                <span v-if="!$v.form.last_name.maxLength" class="invalid-feedback">
                                                    <strong>Last Name may not be greater than {{$v.form.last_name.$params.maxLength.max}} characters</strong>
                                                </span>
                                            </div>
                                        </div> 
                                        <div class="form-group row"> 
                                           <div class="input-group col-md-12">
                                               <div v-if="call_codes.length" class="input-group-append">
                                                   <select v-model.trim="$v.call_prefix.$model" id="call_prefix" class="form-control" @change="getPhoneNumber" :class="{ 'is-invalid': $v.call_prefix.$error }">
                                                       <option v-for="code in call_codes" :key="code.id" :value="code.call_prefix">+ {{ code.call_prefix }}</option>       
                                                    </select>
                                                    <span v-if="!$v.call_prefix.required" class="invalid-feedback">
                                                        <strong>Field is required</strong>
                                                    </span>
                                                </div> 
                                               <input id="phone" type="text" v-model.trim="$v.phone.$model" class="form-control" placeholder="Phone Number" @input="getPhoneNumber" :class="{ 'is-invalid': $v.phone.$error }">
                                                <span v-if="!$v.phone.required" class="invalid-feedback">
                                                    <strong>Phone number is required</strong>
                                                </span>
                                                <span v-else-if="!$v.phone.numeric" class="invalid-feedback">
                                                    <strong>Please enter a valid phone number</strong>
                                                </span>
                                                <span v-else-if="!$v.phone.maxLength" class="invalid-feedback">
                                                    <strong>Phone number may not be greater than {{$v.phone.$params.maxLength.max}}</strong>
                                                </span>
                                                <span v-else-if="!$v.phone.minLength" class="invalid-feedback">
                                                    <strong>Phone number must be at least {{$v.phone.$params.minLength.min}}</strong>
                                                </span>
                                                <span v-else-if="!$v.phone.isUnique" class="invalid-feedback">
                                                    <strong>Phone number already registered</strong>
                                                </span>
                                           </div>
                                        </div> 
                                        <div class="form-group row"> 
                                           <div class="col-md-12">
                                               <input id="email" type="email" v-model.trim="$v.form.email.$model" class="form-control" placeholder="Email Address" :class="{ 'is-invalid': $v.form.email.$error }">
                                                <span v-if="!$v.form.email.required" class="invalid-feedback">
                                                    <strong>Email address is required</strong>
                                                </span>
                                                <span v-else-if="!$v.form.email.email" class="invalid-feedback">
                                                    <strong>Please enter a valid email address</strong>
                                                </span>
                                                <span v-else-if="!$v.form.email.maxLength" class="invalid-feedback">
                                                    <strong>Email address may not be greater than {{$v.form.email.$params.maxLength.max}} characters</strong>
                                                </span>
                                                <span v-else-if="!$v.form.email.isUnique" class="invalid-feedback">
                                                    <strong>Email address already registered.</strong>
                                                </span>
                                                <span v-else-if="!$v.form.email.minLength" class="invalid-feedback">
                                                    <strong>Email must be at least {{$v.form.email.$params.minLength.min}} characters.</strong>
                                                </span>
                                           </div>
                                        </div>                                    
                                    </div>
                                </div>
                            </div>

                            <div id="bottom-wizard">
                                <button type="submit" class="btn btn-dark forward">continue</button>
                            </div>
                        </form>
                        
                        <div class="text-center">
                            <small>By providing your email and creating an account, you are agreeing to our <a href="//shopbox.lk/terms.php" target="_blank">terms of service.</a></small>
                        </div>  
                    </div>
                </div>
            </div> 
        </div>
    </div>

</template>

<script>
    
    import bus from '../bus'
    import { required, maxLength, minLength, numeric, email } from 'vuelidate/lib/validators'
    import queryString from 'query-string'

    export default {
        props: ['codes'],
        data () {
            return {
               
                form: {
                    first_name: '',
                    last_name: '',
                    phone: '',
                    email: ''
                },
                call_prefix: 94,
                phone: '',
                first_name: '',

            }
        },
        validations: {
            form: {
              first_name: {
                required,
                maxLength: maxLength(255)
              },
              last_name: {
                required,
                maxLength: maxLength(255)
              },
              email: {
                required,
                email,
                maxLength: maxLength(255),
                minLength: minLength(4),
                isUnique(value) {

                    if (value === '') return true
                        
                    if(this.$v.form.email.email && this.$v.form.email.maxLength && this.$v.form.email.minLength) {
                        return new Promise((resolve, reject) => {
                        
                            if(value.length > 3) {
                                axios.get('/check-availability?'+this.getQueryParameters())
                                .then(function (response) {
                                    resolve(response.data.status)
                                })
                                .catch(function (error) {
                                    return reject(error);
                                });
                            }
                        })
                    }
                    return true;
                }  
              }
            },
            phone: {
                required,
                numeric,
                minLength: minLength(8),
                maxLength: maxLength(15),
                isUnique(value) {

                    if (value === '') return true

                    if(this.$v.phone.numeric && this.$v.phone.maxLength && this.$v.phone.minLength) {
                        return new Promise((resolve, reject) => {
                        
                            if(value.length >= 8) {
                                axios.get('/check-availability?'+queryString.stringify({phone: this.form.phone}))
                                .then(function (response) {
                                    resolve(response.data.status)
                                })
                                .catch(function (error) {
                                    return reject(error);
                                });
                            }
                        })
                    }
                    return true;
                } 
            },
            call_prefix: {
                required
            }
        },
        computed: {
            call_codes () {
                  return _.orderBy(_.uniqBy(_.filter(this.codes, function(c) { return c.call_prefix > 0; }), 'call_prefix'), ['call_prefix'], ['asc']);
            }
        },
        methods: {
            store () {
             
              this.$v.$touch()

              if (!this.$v.$invalid) {
                bus.$emit('register.step', 'address');
                bus.$emit('basics.data', this.form);
              } 

            }, 
            getPhoneNumber () {
                this.form.phone = '+' + this.call_prefix + this.phone;
            },
            getQueryParameters () {
                return queryString.stringify({
                    email: this.form.email
                })
            }, 
        },
        mounted() {
            this.getPhoneNumber();
        }
    }
</script>
