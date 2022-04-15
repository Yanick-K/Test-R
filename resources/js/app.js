/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import { extend, ValidationProvider } from 'vee-validate';
import { email } from 'vee-validate/dist/rules';
import Vue from 'vue';
import VueTelInput from 'vue-tel-input';
import 'vue-tel-input/dist/vue-tel-input.css';

require('./bootstrap');
window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.use(VueTelInput, {
    invalidMsg : 'Le téléphone n\'est pas valide',
    inputOptions : {
        placeholder : ''
    },
    autoDefaultCountry : false,
    validCharactersOnly : true,
    defaultCountry: 'FR'
});
//Vue.use(axios);
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 extend('email', {
     ...email,
     message : 'L\'email n\'est pas valide'
 });

const app = new Vue({
    el: '#app',
    template : '<example-component></example-component>'
});
