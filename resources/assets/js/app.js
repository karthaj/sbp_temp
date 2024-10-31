
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('get-started', require('./components/GetStarted.vue'));
Vue.component('step-basic', require('./components/Basics.vue'));
Vue.component('step-address', require('./components/Address.vue'));
Vue.component('step-password', require('./components/Password.vue'));

const app = new Vue({
    el: '#app'
});


