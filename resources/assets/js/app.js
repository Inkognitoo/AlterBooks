
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import axios from 'axios';

window.Vue = require('vue');
window.Moment = require('moment');
window.Axios = axios;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('book', require('./components/Book.vue'));
Vue.component('search-form', require('./components/SearchForm.vue'));
Vue.component('sort-panel', require('./components/SortPanel.vue'));
Vue.component('genres-panel', require('./components/GenresPanel.vue'));

axios.get('/api/v1/books')
    .then(function (response) {
        const app = new Vue({
            el: '#app',
            data: {
                books: response.data
            },
        });
    });
