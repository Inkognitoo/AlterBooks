
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

//получаем фильтры из url для запроса на сервер
let index = window.location.href.indexOf('?') === -1 ? window.location.href.length : window.location.href.indexOf('?');
let params = window.location.href.substring(index);

axios.get('/api/v1/books' + params)
    .then(function (response) {
        const app = new Vue({
            el: '#app',
            data: {
                books: response.data
            },
            methods: {
                /**
                 * Событие о том, что изменились активные жанры книги
                 * @param genres
                 */
                changeActiveGenres: function (genres) {
                    this.books.filtered.genres = genres;

                    this.setUrl([{name: 'genres', value: genres}]);
                    this.getBooks({genres: genres});
                },
                /**
                 * Событие о том, что изменилась текущая сортировка
                 * @param sort
                 */
                changeActiveSort: function (sort) {
                    this.setUrl([{name: 'sort', value: sort}]);
                    this.getBooks({sort: sort});
                },
                /**
                 * Устанавливаем фильтры запроса в url (это нужно для того, чтобы отфильтрованный запрос можно было
                 * передать про ссылке)
                 * @param params
                 */
                setUrl: function (params) {
                    let url = new URL(window.location.href);
                    let query_string = url.search;
                    let search_params = new URLSearchParams(query_string);

                    for (let i = 0; i < params.length; i++) {
                        if (Array.isArray(params[i].value)) {
                            search_params.delete(params[i].name + '[]');
                            for (let j = 0; j < params[i].value.length; j++) {
                                search_params.append(params[i].name + '[]', params[i].value[j]);
                            }
                        } else {
                            search_params.set(params[i].name, params[i].value);
                        }
                    }

                    url.search = search_params.toString();
                    let new_url = url.toString();

                    history.pushState(history.state, window.title, new_url);
                },
                /**
                 * Получаем с сервера отфильтрованный список книг
                 * @param params
                 */
                getBooks: function (params = {}) {
                    axios.get('/api/v1/books', {params: params})
                        .then((response) => {
                            this.books = response.data;
                        });
                }
            }
        });
    });
