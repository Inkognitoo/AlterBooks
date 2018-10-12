"use strict";

(function () {
    let button_close = document.getElementById('user-books-close');
    let button_open = document.getElementById('user-books-open');
    let user_books = document.getElementById('user-books');

    if ((button_close !== null) && (button_open !== null)) {
        button_close.addEventListener('click', function () {
            user_books.setAttribute('data-status', 'open');
        });

        button_open.addEventListener('click', function () {
            user_books.setAttribute('data-status', 'close');
        });
    }
})();
