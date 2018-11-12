"use strict";

import axios from 'axios';

(function () {

    let request = axios.create({
        headers: {'Authorization': 'Bearer ' + document.getElementsByName('api_token')[0].content}
    });

    let restoreButton = document.getElementById('review-restore');

    if (restoreButton !== null) {
        restoreButton.onclick = restoreReview;
    }

    /**
     * Обрабатываем нажатие на кнопку восстановления
     *
     */
    function restoreReview() {
        let book_id = parseInt(this.dataset.bookId);

        restoreApiReview(book_id)
            .then(function (response) {
                showReview();
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    /**
     * Api запрос для восстановления рецензии
     *
     * @param {int} book_id идентификатор рецензии
     * @returns {Promise<any>}
     */
    function restoreApiReview(book_id) {
        let url = `/api/v1/book/${book_id}/review/restore`;

        return new Promise(function (resolve, reject) {
            request.put(url)
                .then(function (response) {
                    if (response.data.success) {
                        resolve(response.data);
                    } else {
                        reject(response.data)
                    }
                })
                .catch(function (error) {
                    reject(error);
                });
        });
    }

    /**
     * Показываем восстановленную рецензию
     */
    function showReview() {
        let review_self = document.getElementById('review-self');
        let review_text = document.getElementById('review-text');

        review_self.setAttribute('data-status', 'open');
        review_text.style.maxHeight = '16.5em';
    }
})();
