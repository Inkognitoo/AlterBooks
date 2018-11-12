"use strict";

import axios from "axios";

(function () {
    let review_create_button = document.getElementsByClassName('review-new-create__button');
    Array.prototype.forEach.call(review_create_button, function(button){
        let create = button.parentNode;
        let form = create.nextSibling.nextSibling;

        button.addEventListener('click', function (b) {
            create.setAttribute('data-status', 'close');
            form.setAttribute('data-status', 'open');

            let header_length = 0;
            let header = document.getElementById('create-header');
            let output_header = document.getElementById('review-new-form__remain');
            header_length = header.value.length;
            output_header.innerHTML = String(67 - header_length);
            header.oninput = function () {
                header_length = this.value.length;
                output_header.innerHTML = String(67 - header_length);
                this.dataset.hasError = 'false';
            };
        })
    });
})();


(function () {
    let request = axios.create({
        headers: {'Authorization': 'Bearer ' + document.getElementsByName('api_token')[0].content}
    });

    let reviewCreateButton = document.getElementById('review-create-button');

    if (reviewCreateButton !== null) {
        let createRatingGroup = document.getElementsByName('create-rating');
        Array.prototype.forEach.call(createRatingGroup, function(createRatingElement){
            createRatingElement.onclick = function() {
                document.getElementById('create-rating').dataset.hasError = 'false';
            };
        });

        let createContent = document.getElementById('create-content');
        createContent.oninput = function() {
            this.dataset.hasError = 'false';
        };
        reviewCreateButton.onclick = saveCreateReview;
    }

    /**
     * Обрабатываем нажатие на кнопку сохранения
     *
     */
    function saveCreateReview() {
        let bookId = parseInt(this.dataset.bookId);

        let createRatingGroup = document.getElementsByName('create-rating');
        let createRating = 0;
        Array.prototype.forEach.call(createRatingGroup, function(createRatingElement) {
            createRating = parseInt(createRatingElement.checked ? createRatingElement.value: createRating);
        });

        let createHeader = document.getElementById('create-header');
        let createContent = document.getElementById('create-content');

        let createReview = {
            rating: createRating,
            header: createHeader.value,
            text: createContent.value
        };

        createApiReview(bookId, createReview)
            .then(function (response) {
                showReview(bookId, createReview);
            })
            .catch(function (response) {
                showErrors(response.errors);
            });
    }

    /**
     * Api запрос для создания рецензии
     *
     * @param {int} bookId идентификатор книги
     * @param {object} createReview содержание рецензии
     * @returns {Promise<any>}
     */
    function createApiReview(bookId, createReview) {
        let url = `/api/v1/book/${bookId}/review/create`;

        return new Promise(function (resolve, reject) {
            request.post(url, {
                rating: createReview.rating,
                header: createReview.header,
                text: createReview.text
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
     * Показываем созданную рецензию
     *
     * @param {int} bookId идентификатор книги
     * @param {object} createReview содержание рецензии
     */
    function showReview(bookId, createReview) {
        let showRating = document.getElementsByClassName('review-rating__star_show');
        Array.prototype.forEach.call(showRating, function(showRatingElement){
            if (showRatingElement.dataset.number <= createReview.rating) {
                showRatingElement.classList.add('review-rating__star_active');
            } else {
                showRatingElement.classList.remove('review-rating__star_active');
            }
        });

        showRating = document.getElementsByClassName('review-new-stars__element_edit');
        Array.prototype.forEach.call(showRating, function(showRatingElement){
            showRatingElement.checked = Number(showRatingElement.value) === createReview.rating;
            console.log(showRatingElement.checked, createReview.rating);
        });

        document.getElementById('rating-header').innerHTML = createReview.rating.toFixed(1);
        document.getElementById('review-rating').dataset.rating = createReview.rating;
        document.getElementById('review-header-content').innerHTML = createReview.header;
        document.getElementById('review-text-content').innerHTML = createReview.text;

        document.getElementById('review-create').dataset.status = 'close';
        document.getElementById('review-self').dataset.status = 'open';

        document.getElementById('review-delete').dataset.bookId = String(bookId);
        document.getElementById('review-edit-save').dataset.bookId = String(bookId);
    }

    /**
     * Показываем ошибки, возникшие при создании рецензии
     */
    function showErrors(errors) {
        console.log(errors);

        let element = null;
        let elementError = null;

        Array.prototype.forEach.call(errors, function(error){
            switch (error.name) {
                case 'rating':
                    element = document.getElementById('create-rating');
                    break;
                case 'header':
                    element = document.getElementById('create-header');
                    break;
                case 'text':
                    element = document.getElementById('create-content');
                    break;
            }

            elementError = element;
            while ((elementError.nodeType !== 1) || (!elementError.classList.contains('review-new-form__error'))) {
                elementError = elementError.nextSibling;
            }

            element.dataset.hasError = 'true';
            elementError.innerHTML = error.message;
        });
    }

})();

