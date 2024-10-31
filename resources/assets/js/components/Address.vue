<template>
    
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-10">
                <div class="card card-register">
                    <div class="card-body">
                        <div class="question_title">
                            <h3>Hi {{ form.first_name }} {{ form.last_name }},</h3>
                            <h4>Tell us about your business</h4>
                            <p>Provide a few <b>basic details</b> to help you set up <b>your store</b>.</p>     
                        </div>

                        <form autocomplete="off" @submit.prevent="store">
                            <div class="step">
                                <div class="row justify-content-md-center">
                                    <div class="col-md-6 ">        
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <input id="store_name" type="text" v-model="$v.form.store_name.$model" autofocus="autofocus" class="form-control" placeholder="Store Name" @input="generateDomain" :class="{ 'is-invalid': $v.form.store_name.$error }">
                                                <span v-if="!$v.form.store_name.required" class="invalid-feedback">
                                                    <strong>Store name is required</strong>
                                                </span>
                                                <span v-if="!$v.form.store_name.maxLength" class="invalid-feedback">
                                                    <strong>Store name may not be greater than {{$v.form.store_name.$params.maxLength.max}} characters</strong>
                                                </span>
                                            </div>
                                        </div> 
                                        <div class="form-group row"> 
                                            <div class="input-group col-md-12">
                                                <input type="text" id="domain" v-model="$v.form.store_domain.$model" class="form-control" placeholder="domain" :class="{ 'is-invalid': $v.form.store_domain.$error }"> 
                                                <div class="input-group-append">
                                                    <span class="input-group-text font-weight-bold">.myshopbox.lk</span>
                                                </div>

                                                <span v-if="!$v.form.store_domain.required" class="invalid-feedback">
                                                    <strong>Domain is required</strong>
                                                </span>
                                                <span v-if="!$v.form.store_domain.maxLength" class="invalid-feedback">
                                                    <strong>Domain may not be greater than {{$v.form.store_domain.$params.maxLength.max}} characters</strong>
                                                </span>
                                                <span v-if="!$v.form.store_domain.isUnique" class="invalid-feedback">
                                                    <strong>Domain is already registered</strong>
                                                </span>
                                                <span v-if="!$v.form.store_domain.minLength" class="invalid-feedback">
                                                    <strong>Domain must be at least {{$v.form.store_domain.$params.minLength.min}} characters</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <input id="address1" type="text" v-model="$v.form.address1.$model" class="form-control" placeholder="Address 1" :class="{ 'is-invalid': $v.form.address1.$error }">
                                                <span v-if="!$v.form.address1.required" class="invalid-feedback">
                                                    <strong>Address 1 is required</strong>
                                                </span>
                                                <span v-if="!$v.form.address1.maxLength" class="invalid-feedback">
                                                    <strong>Address 1 may not be greater than {{$v.form.address1.$params.maxLength.max}} characters.</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                 <input id="address2" type="text" v-model="$v.form.address2.$model" value="" class="form-control" placeholder="Address 2" :class="{ 'is-invalid': $v.form.address2.$error }">
                                                 <span v-if="!$v.form.address2.maxLength" class="invalid-feedback">
                                                    <strong>Address 2 may not be greater than {{$v.form.address2.$params.maxLength.max}} characters</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div v-if="countries.length" class="form-group row">
                                            <div :class="{'col-md-6': filteredStates.length, 'col-md-12': filteredStates.length === 0}">
                                               <select id="country" required="required" v-model="$v.form.country.$model" class="form-control" @change="getStates" :class="{ 'is-invalid': $v.form.country.$error }">
                                                   <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.name }}</option> 
                                               </select>
                                                <span v-if="!$v.form.country.required" class="invalid-feedback">
                                                    <strong>Country is required</strong>
                                                </span>
                                                <span v-if="!$v.form.country.numeric" class="invalid-feedback">
                                                    <strong>Country must be a number</strong>
                                                </span>
                                            </div>
                                            <div v-if="filteredStates.length" class="col-md-6">
                                                <select id="state" required="required" v-model="$v.form.state.$model" class="form-control" :class="{ 'is-invalid': $v.form.state.$error }">
                                                   <option v-for="state in filteredStates" :key="state.id" :value="state.id">{{ state.name }}</option> 
                                               </select>
                                                <span v-if="!$v.form.state.numeric" class="invalid-feedback">
                                                    <strong>State must be a number</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <input id="city" type="text" v-model="$v.form.city.$model" class="form-control" placeholder="City" :class="{ 'is-invalid': $v.form.city.$error }">
                                                <span v-if="!$v.form.city.required" class="invalid-feedback">
                                                    <strong>City is required</strong>
                                                </span>
                                                <span v-if="!$v.form.city.maxLength" class="invalid-feedback">
                                                    <strong>City may not be greater than {{$v.form.city.$params.maxLength.max}} characters</strong>
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                 <input id="postalcode" type="text" v-model="$v.form.postal_code.$model" class="form-control" placeholder="Postal Code" :class="{ 'is-invalid': $v.form.postal_code.$error }">
                                                <span v-if="!$v.form.postal_code.required" class="invalid-feedback">
                                                    <strong>Poastal Code is required.</strong>
                                                </span>
                                                <span v-if="!$v.form.postal_code.maxLength" class="invalid-feedback">
                                                    <strong>Postal Code may not be greater than {{$v.form.postal_code.$params.maxLength.max}} characters</strong>
                                                </span>
                                            </div>
                                        </div> 
                                    </div>  
                                </div>
                            </div>

                            <div id="bottom-wizard">
                                <button type="submit" class="btn btn-dark forward" :disabled="disabled">Continue</button>
                            </div>
                        </form>  
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
        props: ['countries', 'states', 'formData'],
        data () {
            return {
                form: {
                    store_name: '',
                    store_domain: '',
                    address1: '',
                    address2: '',
                    country: 197,
                    state: '',
                    city: '',
                    postal_code: ''
                },
                filteredStates: [],
                disabled: false
            }
        },
        validations: {
            form: {
              store_name: {
                required,
                maxLength: maxLength(255)
              },
              store_domain: {
                required,
                maxLength: maxLength(255),
                minLength: minLength(4),
                isUnique(value) {

                    if (value === '') return true
                        
                    if(this.$v.form.store_domain.maxLength && this.$v.form.store_domain.minLength) {
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
              },
              address1: {
                required,
                maxLength: maxLength(255)
              },
              address2: {
                maxLength: maxLength(255)
              },
              country: {
                required,
                numeric
              },
              state: {
                numeric
              },
              city: {
                required,
                maxLength: maxLength(255)
              },
              postal_code: {
                required,
                maxLength: maxLength(255)
              }
            }
        },
        methods: {
            store () {

                this.$v.$touch()
                if (!this.$v.$invalid) {
                    bus.$emit('register.step', 'password');
                    bus.$emit('address.data', this.form);
                } 

               
            },
            getStates () {
                this.filteredStates= this.states.filter((state) => {
                    return  state.country_id === this.form.country;
                });

                if(this.filteredStates.length) {
                    this.form.state = this.filteredStates[0].id;
                } else {
                    this.form.state = '';
                }
            },
            mergeData () {

                this.form['email'] = this.formData.email;
                this.form['first_name'] = this.formData.first_name;
                this.form['last_name'] = this.formData.last_name;
                this.form['phone'] = this.formData.phone;

            },
            generateDomain () {
                var domain = this.form.store_name.trim().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
                this.form.store_domain = domain.toLowerCase();
                
            },
            getQueryParameters () {
                return queryString.stringify({
                    domain: this.form.store_domain
                })
            }, 
        },
        mounted() {

            this.getStates();
            this.mergeData();
        }
    }
</script>
