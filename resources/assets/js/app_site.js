
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */
window.jQuery = window.$ = $ = require('jquery');

require('./bootstrap');

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));

// const app = new Vue({
//     el: '#app'
// });

window.moment = require('moment');

require('jquery-ui');

require('../vendors/distpicker');
require('../vendors/pc-bootstrap4-datetimepicker/src/js/bootstrap-datetimepicker');

require('select2/dist/js/select2.full');