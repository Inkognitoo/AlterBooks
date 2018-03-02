"use strict";

import axios from 'axios';

(function () {

    let request = axios.create({
        headers: {'Authorization': 'Bearer ' + document.getElementsByName('api_token')[0].content}
    });

    let estimateButtons = document.getElementsByName('estimateButton');

    if (estimateButtons !== null) {
        for (let i = 0; i < estimateButtons.length; i++) {
            estimateButtons[i].onclick = estimate;
        }
    }

    /**
     * Обрабатываем нажатие на кнопку изменения/добавления оценки
     *
     */
    function estimate() {
        let book_id = parseInt(this.dataset.bookId);
        let review_id = parseInt(this.dataset.reviewId);
        let type = this.dataset.type;

        switch (type) {
            case 'positive':
                positiveEstimate(book_id, review_id)
                    .then(function (response) {
                        updateEstimateView((response.data || {}).estimate || 0, type, review_id)
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                break;
            case 'negative':
                negativeEstimate(book_id, review_id)
                    .then(function (response) {
                        updateEstimateView((response.data || {}).estimate || 0, type, review_id)
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                break;
            default:
        }

    }

    /**
     * Api запрос для позитивной оценки рецензии
     *
     * @param {int} book_id идентификатор книги
     * @param {int} review_id идентификатор рецензии
     * @returns {Promise<any>}
     */
    function positiveEstimate(book_id, review_id) {
        let url = `/api/v1/book/id${book_id}/review/id${review_id}/estimate/plus`;

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
     * Api запрос для негативной оценки рецензии
     *
     * @param {int} book_id идентификатор книги
     * @param {int} review_id идентификатор рецензии
     * @returns {Promise<any>}
     */
    function negativeEstimate(book_id, review_id) {
        let url = `/api/v1/book/id${book_id}/review/id${review_id}/estimate/minus`;

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
     * Прячем или показываем кнопки, изменяем значение общей оценки
     *
     * @param {int} estimate Значение новой оценки
     * @param {string} action Произведённое действие
     * @param {int} review_id Идентификатор рецензии
     */
    function updateEstimateView(estimate, action, review_id) {
        let positiveEstimateButton = document.querySelector(`[data-review-id="${review_id}"][data-type="positive"]`);
        let negativeEstimateButton = document.querySelector(`[data-review-id="${review_id}"][data-type="negative"]`);
        let counter = document.querySelector(`[data-review-id="${review_id}"][data-type="counter"]`);

        switch (estimate) {
            case -1:
                disableEstimateButton(negativeEstimateButton);

                updateEstimateCounter(counter, action);
                break;
            case 1:
                disableEstimateButton(positiveEstimateButton);

                updateEstimateCounter(counter, action);
                break;
            case 0:
                enableEstimateButton(positiveEstimateButton);
                enableEstimateButton(negativeEstimateButton);

                updateEstimateCounter(counter, action);
                break;
            default:
        }
    }

    /**
     * Прячем кнопку от пользователя
     *
     * @param {object} button Объект кнопки оценки рецензии
     */
    function disableEstimateButton(button) {
        button.style.opacity = "0.15";
        button.style.cursor = "auto";
    }
    /**
     * Показываем кнопку пользователю
     *
     * @param {object} button Объект кнопки оценки рецензии
     */
    function enableEstimateButton(button) {
        button.style.opacity = "0.4";
        button.style.cursor = "pointer";
    }

    /**
     * Изменяем значение общей оценки
     *
     * @param {object} counter Объект отображения общей оценки
     * @param {string} action Произведённое действие
     */
    function updateEstimateCounter(counter, action) {
        let count = parseInt(counter.innerHTML);
        if (action === 'positive') {
            count++;
        } else {
            count--;
        }

        counter.innerHTML = count;
    }

})();
