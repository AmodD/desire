
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('create', require('./components/create.vue'));
Vue.component('generate', require('./components/generate.vue'));
Vue.component('demo', require('./components/demo.vue'));
Vue.component('nn', require('./components/nn.vue'));
Vue.component('relationship', require('./components/relationship.vue'));
Vue.component('traindata', require('./components/data.vue'));
Vue.component('simulator', require('./components/simulator.vue'));
Vue.component('transactions', require('./components/transactions.vue'));

const app = new Vue({
    el: '#app'
});
