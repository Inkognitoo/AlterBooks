"use strict";

import axios from 'axios';

(function registration() {
    let checkbox = document.getElementById('registration-checkbox');
    let button = document.getElementById('registration-button');

    if (checkbox !== null) {
        checkbox.addEventListener('click', function (c) {
            button.disabled = !checkbox.checked;
        });
    }
})();

(function () {
    let password = document.getElementById('registration-password');
    let password_confirmation = document.getElementById('registration-password_confirmation');

    if ((password !== null) && (password_confirmation !== null)) {
        password_confirmation.oninput = function () {
            password_confirmation.parentNode.setAttribute('data-status', '');
            if (password_confirmation.value !== '') {
                if (password_confirmation.value !== password.value) {
                    password_confirmation.parentNode.setAttribute('data-status', 'error');
                } else {
                    password_confirmation.parentNode.setAttribute('data-status', 'correct');
                }
            }
        };

        password.oninput = function () {
            password_confirmation.parentNode.setAttribute('data-status', '');
            if (password_confirmation.value !== '') {
                if (password_confirmation.value !== password.value) {
                    password_confirmation.parentNode.setAttribute('data-status', 'error');
                } else {
                    password_confirmation.parentNode.setAttribute('data-status', 'correct');
                }
            }
        };
    }


    // api-запросы на корректность обязательных данных

    let registration_elements = document.getElementsByClassName('registration-element');
    Array.prototype.forEach.call(registration_elements, function(registration_element){
        let name = registration_element.dataset.name;

        if (name !== undefined) {
            let input = registration_element.childNodes[3];
            input.onblur = function () {
                if (input.value !== '') {
                    validate(name, registration_element, input.value);
                }
            }
        }
    });


    /**
     * Обрабатываем  валидацию непустого поля
     *
     */
    function validate(elementName, element, elementValue) {
        let request = axios.create({
            headers: {'Authorization': 'Bearer ' + document.getElementsByName('api_token')[0].content}
        });

        validateApiRagistration(elementName, elementValue)
            .then(function (response) {
                showValidate(element, response.data);
            })
            .catch(function (error) {
                console.log(error);
            });

        /**
         * Api запрос для валидации поля
         *
         * @returns {Promise<any>}
         */
        function validateApiRagistration(elementName, elementValue) {
            let url = `/api/v1/registration/validate`;

            return new Promise(function (resolve, reject) {
                request.post(url, {
                    name: elementName,
                    value: elementValue
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
         * Выводим результат валидации
         */
        function showValidate(element, status) {
            console.log(element.dataset.name, status);
        }
    }
})();