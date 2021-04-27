
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

const $ = require('jquery');
window.jQuery = window.$ = require('jquery');

require('./bootstrap');

const np = require('number-precision');
window.NP = np;

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



require('jquery-ui');

window.moment = require('moment');

require('../vendors/select2/select2.full.fixinput');
require('select2/dist/js/i18n/zh-CN');

require('../vendors/pc-bootstrap4-datetimepicker/src/js/bootstrap-datetimepicker');
require('../vendors/distpicker/dist/distpicker.common');


require('../vendors/formstone/js/formstone.js');


require('./global');

require('./admin');