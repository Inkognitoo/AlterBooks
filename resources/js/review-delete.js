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
        let id = parseInt(this.dataset.reviewId);

        deleteApiReview(id, book_id)
            .then(function (response) {
                addRestoring(book_id);
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    /**
     * Api запрос для мягкого удаления рецензии
     *
     * @param {int} id идентификатор рецензии
     * @param {int} book_id идентификатор книги
     * @returns {Promise<any>}
     */
    function deleteApiReview(id, book_id) {
        console.log(book_id);
        let url = `/api/v1/book/${book_id}/review/id${id}/delete`;

        return new Promise(function (resolve, reject) {
            request.delete(url)
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
    function addRestoring(book_id) {
        let review_self = document.getElementById('review-self');
        let review_text = document.getElementById('review-text');
        let review_shield = document.getElementById('review-shield');

        review_self.setAttribute('data-status', 'delete');
        review_text.setAttribute('data-status', 'close');
        review_text.style.maxHeight = '6em';
        review_text.style.marginBottom = '20px';
        review_text.style.overflow = 'hidden';

        let review_text_more = document.getElementById('review-text-more');
        review_text_more.style.top = 'calc(15em - 2px)';
        review_text_more.style.bottom = 'auto';
        review_text_more.innerHTML = 'читать далее';

        let review_text_block = document.getElementById('review-text-block');
        review_text_block.style.display = 'block';

        review_shield.style.height = (review_text.offsetHeight + 25.5) + 'px';

        let restoreButton = document.getElementById('review-restore');
        restoreButton.dataset.bookId = book_id;
    }
})();
