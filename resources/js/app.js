require('./bootstrap');
import Vue from 'vue'
import VueRouter from 'vue-router'

import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(VueAxios, axios)

Vue.use(VueRouter);

window.Vue = require('vue');
window.VueAxios = require('vue-axios');

window.Axios = require('axios');
/**
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
//Vue.component('example-component', require('./components/ExampleComponent.vue').default);


Vue.component('home-component', require('./components/HomeComponent.vue').default);
Vue.component('loading', require('./components/shared/Loading.vue').default);

const app = new Vue({
    el: '#app'
});
