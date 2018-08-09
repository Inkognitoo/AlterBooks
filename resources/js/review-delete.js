"use strict";

import axios from 'axios';

(function () {

    let request = axios.create({
        headers: {'Authorization': 'Bearer ' + document.getElementsByName('api_token')[0].content}
    });

    let deleteButton = document.getElementById('review-delete');

    if (deleteButton !== null) {
        deleteButton.onclick = deleteReview;
    }

    /**
     * Обрабатываем нажатие на кнопку удаления
     *
     */
    function deleteReview() {
        let book_id = parseInt(this.dataset.bookId);
        let review_id = parseInt(this.dataset.reviewId);

        deleteApiReview(book_id, review_id)
            .then(function (response) {
                addRestoring();
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    /**
     * Api запрос для мягкого удаления рецензии
     *
     * @param {int} book_id идентификатор книги
     * @param {int} review_id идентификатор рецензии
     * @returns {Promise<any>}
     */
    function deleteApiReview(book_id, review_id) {
        let url = `/book/${book_id}/review/id${review_id}/delete`;

        return new Promise(function (resolve, reject) {
            request.post(url)
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
     * Показываем меню для восстановления рецензии
     */
    function addRestoring() {
        let deleteButton = document.getElementById('review-delete');
        let review_text = document.getElementById('review-text');
        console.log(review_text);
        deleteButton.addEventListener('click', function (b) {
            console.log(review_text.offsetHeight);
            document.getElementsByClassName('review-title__shield')[0].style.height = (review_text.offsetHeight + 25.5) + 'px';
        });
    }
})();
