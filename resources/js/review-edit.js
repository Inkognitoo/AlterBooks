"use strict";

import axios from 'axios';

(function () {
    let request = axios.create({
        headers: {'Authorization': 'Bearer ' + document.getElementsByName('api_token')[0].content}
    });

    let review_edit_button = document.getElementById('review-edit');
    let review_self = document.getElementById('review-self');
    let review_edit = document.getElementById('review-edit-module');

    let review_field_header = document.getElementById('er-header');
    if (review_field_header !== null) {
        let header_output = document.getElementById('review-edit-form__remain');
        let header_length = review_field_header.value.length;
        header_output.innerText = String(67 - header_length);

        review_field_header.oninput = function () {
            header_length = review_field_header.value.length;
            header_output.innerText = String(67 - header_length);
        };
    }
    if (review_edit_button !== null) {
        review_edit_button.addEventListener('click', function (r) {
            review_self.setAttribute('data-status', 'close');
            review_edit.setAttribute('data-status', 'open');

            let review_header = document.getElementById('review-header-content');
            let review_text = document.getElementById('review-text-content');
            let review_field_text = document.getElementById('er-content');

            review_field_header.value = review_header.innerText;
            review_field_text.value = review_text.innerText;

            let saveButton = document.getElementById('review-edit-save');
            if (saveButton !== null) {
                saveButton.onclick = saveReview;
            }

            /**
             * Обрабатываем нажатие на кнопку сохранения
             *
             */
            function saveReview() {
                let book_id = parseInt(this.dataset.bookId);
                let id = parseInt(this.dataset.reviewId);

                let rating = 0;
                let rating_field = document.getElementsByName('rating');
                Array.prototype.forEach.call(rating_field, function(rating_element){
                    rating = parseInt(rating_element.checked ? rating_element.value: rating);

                });

                restoreApiReview(book_id, id, rating)
                    .then(function (response) {
                        showReview(rating);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }

            /**
             * Api запрос для изменения рецензии
             *
             * @param {int} id идентификатор рецензии
             * @param {int} book_id идентификатор книги
             * @param {int} rating рейтинг рецензии
             * @returns {Promise<any>}
             */
            function restoreApiReview(book_id, id, rating) {
                let url = `/api/v1/${book_id}/review/${id}/edit`;

                let header = review_field_header.value;
                let text = review_field_text.value;

                return new Promise(function (resolve, reject) {
                    request.put(url, {
                        rating: rating,
                        header: header,
                        text: text
                    })
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
             * Показываем отредактированную рецензию
             */
            function showReview(rating) {
                review_self.setAttribute('data-status', 'open');
                review_edit.setAttribute('data-status', 'close');

                review_header.innerHTML = review_field_header.value;
                review_text.innerHTML = review_field_text.value;

                let review_rating = document.getElementsByClassName('review-rating__star');
                Array.prototype.forEach.call(review_rating, function(rating_element){
                    if (rating_element.dataset.number <= rating) {
                        rating_element.classList.add('review-rating__star_active');
                    } else {
                        rating_element.classList.remove('review-rating__star_active');
                    }
                });
                document.getElementById('rating-header').innerHTML = rating.toFixed(1);
                document.getElementsByClassName('review-rating')[0].dataset.rating = rating;
            }
        })
    }
})();