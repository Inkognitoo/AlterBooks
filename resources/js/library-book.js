"use strict";

import axios from 'axios';

(function () {

    let request = axios.create({
        headers: {'Authorization': 'Bearer ' + document.getElementsByName('api_token')[0].content}
    });

    let libraryButton = document.getElementById('libraryButton');

    if (libraryButton !== null) {
        libraryButton.onclick = function () {
            let book_id = this.dataset.bookId;
            let type = this.dataset.type;
            let url = `/api/v1/library/book/id${book_id}`;

            disableButton(libraryButton);
            switch (type) {
                case 'add':
                    addBookToLibrary(url)
                        .then(function (response) {
                            setLibraryButtonToDelete(libraryButton);
                            enableButton(libraryButton);
                        })
                        .catch(function (error) {
                            console.log(error);
                            enableButton(libraryButton);
                        });
                    break;
                case 'delete':
                    deleteBookFromLibrary(url)
                        .then(function (response) {
                            setLibraryButtonToAdd(libraryButton);
                            enableButton(libraryButton);
                        })
                        .catch(function (error) {
                            console.log(error);
                            enableButton(libraryButton);
                        });
                    break;
                default:
                    enableButton(libraryButton);
            }
        };
    }

    /**
     * Api запрос для добавления книгу в библиотеку
     *
     * @param {string} url адрес api запроса
     * @returns {Promise<any>}
     */
    function addBookToLibrary(url) {
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
     * Api запрос для удаления книги из библиотеки
     *
     * @param {string} url адрес api запроса
     * @returns {Promise<any>}
     */
    function deleteBookFromLibrary(url) {
        return new Promise(function (resolve, reject) {
            request.delete(url)
                .then(function (response) {
                    if (response.data.success) {
                        resolve(response.data);
                    } else {
                        reject(response.data);
                    }
                })
                .catch(function (error) {
                    reject(error);
                });
        });
    }

    /**
     * Устанавливаем кнопку в состояние удаления книги
     *
     * @param {object} button Объект кнопки добавления/удаления книги в/из библиотеки
     */
    function setLibraryButtonToDelete(button) {
        button.innerText = button.dataset.deleteText;
        button.setAttribute('data-type', 'delete')
    }

    /**
     * Устанавливаем кнопку в состояние добавления книги
     *
     * @param {object} button Объект кнопки добавления/удаления книги в/из библиотеки
     */
    function setLibraryButtonToAdd(button) {
        button.innerText = button.dataset.addText;
        button.setAttribute('data-type', 'add')
    }

    /**
     * Выключаем кнопку
     *
     * @param {object} button Объект кнопки добавления/удаления книги в/из библиотеки
     */
    function disableButton(button) {
        button.disabled = true;
    }

    /**
     * Включаем кнопку
     *
     * @param {object} button Объект кнопки добавления/удаления книги в/из библиотеки
     */
    function enableButton(button) {
        button.disabled = false;
    }

})();