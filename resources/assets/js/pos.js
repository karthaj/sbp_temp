window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Vue = require('vue');

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} 

import store from './store/pos'

Vue.component('products', require('./components/pos/Product.vue'))
Vue.component('search', require('./components/pos/Search.vue'))
Vue.component('category-modal', require('./components/pos/CategoryModal.vue'))

const app = new Vue({
    el: '#app',
    store
});