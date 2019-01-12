"use strict";

import axios from "axios";

(function () {
    let form_element = document.getElementsByClassName('book-edit');
    if (form_element !== null) {
        let input = document.getElementsByClassName('edit-block-element__content_input');
        Array.prototype.forEach.call(input, function(field){
            let size = field.nextSibling.nextSibling;
            if (size) {
                size.innerHTML = field.value.length + ' / ' + field.getAttribute('maxlength');
                field.oninput = function () {
                    size.innerHTML = field.value.length + ' / ' + field.getAttribute('maxlength');
                };
            }
        });
    }

    /**
     *  Изменение информации о книге
     */
    let request = axios.create({
        headers: {'Authorization': 'Bearer ' + document.getElementsByName('api_token')[0].content}
    });


    let userEditInfoForm = document.getElementById('book-edit');
    if (userEditInfoForm) {
        let userEditButton = document.getElementById('book-edit-button');
        userEditButton.addEventListener('click', function (e) {
            editBook();
        });
    }


    /**
     * Обрабатываем процесс изменения информации о книге
     *
     */
    function editBook() {
        let data = {};
        data.title = document.getElementById('change_title').value;
        // data.text = document.getElementById('change_text').value;
        data.status = document.getElementById('change_status').value;
        data.description = document.getElementById('change_description').value;

        data.genres = [];
        Array.prototype.forEach.call(document.getElementsByName('genres'), function (genre) {
            if (genre.checked) {
                data.genres.push(genre.value);
            }
        });

        validateApiBook(data)
            .then(function (response) {
                showBookCorrect();
            })
            .catch(function (response) {
                showBookErrors(response.errors);
            });
    }

    /**
     * Api запрос для изменения информации о книге
     *
     * @param {object} data данные формы
     * @returns {Promise<any>}
     */
    function validateApiBook(data) {
        let bookId = document.getElementById('book-edit').dataset.bookId;

        let url = `/api/v1/book/${bookId}/edit`;

        return new Promise(function (resolve, reject) {
            request.put(url, {
                title: data.title,
                status: data.status,
                description: data.description,
                genres: data.genres
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
     *  Выводим ошибки при их наличии
     *
     *  @param {object} errors
     */
    function showBookErrors(errors) {
        Array.prototype.forEach.call(errors, function (error) {
            document.getElementById('edit-error-' + error.name).innerText = error.message;
            document.getElementById('change_' + error.name).classList.add('edit-block-element__content_error-field');
            document.getElementsByClassName('edit-block-status_correct')[0].style.display = 'none';
        });
    }

    /**
     *  Выводим сообщение об успешном редактирования данных
     */
    function showBookCorrect() {
        document.getElementsByClassName('edit-block-status_correct')[0].style.display = 'flex';
    }
})();