<template>
    
    <step-basic v-if="step1" :codes="countries"></step-basic>

    <step-address v-else-if="step2" :countries="countries" :states="states" :form-data="basicsData"></step-address>
    
    <step-password v-else-if="step3" :form-data="addressData"></step-password>
    
</template>

<script>

    import bus from '../bus'
 
    export default {
        props: ['countries', 'states'],
        data () {
            return {
                step1: true,
                step2: false,
                step3: false,
                basicsData: '',
                addressData: ''
            }
        },
        methods: {
            stepAddress () {
                this.step1 = false;
                this.step2 = true;
                this.step3 = false;
            }, 
            stepPassword () {
                this.step1 = false;
                this.step2 = false;
                this.step3 = true;
            }
        },
        mounted() {
            
            bus.$on('register.step', (step) => {
                if(step === 'address') {
                    this.stepAddress();
                } else if(step === 'password') {
                    this.stepPassword();
                }

            })

            bus.$on('basics.data', (data) => {
                    
                this.basicsData = data;

            })

            bus.$on('address.data', (data) => {

                this.addressData = data;

            })

        }
    }
</script>
