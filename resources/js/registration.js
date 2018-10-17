"use strict";

import axios from 'axios';

(function () {
    let registration_button = document.getElementById('registration-button');

    function registrationAbility(status_array) {

        let status = true;
        for (let i = 0; i < status_array.length; i++) {
            status = status && status_array[i]
        }

        registration_button.disabled = !status;
    }

    let registration_correct = [false, false, false, false, false];

    /**
     * Api-запросы на корректность обязательных данных
     *
     */

    let registration_elements = document.getElementsByClassName('registration-element');
    Array.prototype.forEach.call(registration_elements, function(element){
        let name = element.dataset.name;

        if (name !== undefined) {
            let elements_child = element.childNodes;
            let element_input = null;
            let element_error = null;

            for (let i = 0; i < elements_child.length; i++) {
                if (elements_child[i].nodeType === 1) {
                    element_input = elements_child[i].classList.contains('registration-element__field') ?
                        elements_child[i] : element_input;
                    element_error = elements_child[i].classList.contains('registration-element__error') ?
                        elements_child[i] : element_error;
                }
            }

            element_input.onblur = function () {
                if (element_input.value !== '') {
                    validate(name, element, element_input.value);
                } else {
                    registration_correct[element.dataset.number] = false;
                    registrationAbility(registration_correct);
                    element.dataset.status = '';
                    element_error.innerHTML = '';
                }
            }
        }
    });


    /**
     * Обрабатываем  валидацию непустого поля
     *
     */
    function validate(elementName, element, elementValue) {
        let request = axios.create({});

        validateApiRagistration(elementName, elementValue)
            .then(function (response) {
                showValidate(element, response);
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
            let elements_child = element.childNodes;
            let element_error = null;

            for (let i = 0; i < elements_child.length; i++) {
                if (elements_child[i].nodeType === 1) {
                    element_error = elements_child[i].classList.contains('registration-element__error') ?
                        elements_child[i] : element_error;
                }
            }

            if (status.errors.length !== 0) {
                element.dataset.status = 'error';
                element_error.innerHTML = status.errors[0];
                registration_correct[element.dataset.number] = false;
                registrationAbility(registration_correct);
            } else {
                element.dataset.status = 'correct';
                element_error.innerHTML = '';
                registration_correct[element.dataset.number] = true;
                registrationAbility(registration_correct);
            }
        }
    }



    /**
     * Обрабатываем повтор пароля
     */
    let password = document.getElementById('registration-password');
    let password_confirmation = document.getElementById('registration-password_confirmation');

    if ((password !== null) && (password_confirmation !== null)) {
        password_confirmation.oninput = function () {
            password_confirmation.parentNode.setAttribute('data-status', '');
            if (password_confirmation.value !== '') {
                if (password_confirmation.value !== password.value) {
                    let element = password_confirmation.parentNode;
                    element.setAttribute('data-status', 'error');
                    registration_correct[element.dataset.number] = false;
                    registrationAbility(registration_correct);
                } else {
                    let element = password_confirmation.parentNode;
                    element.setAttribute('data-status', 'correct');
                    registration_correct[element.dataset.number] = true;
                    registrationAbility(registration_correct);
                }
            }
        };

        password.oninput = function () {
            password_confirmation.parentNode.setAttribute('data-status', '');
            if (password_confirmation.value !== '') {
                if (password_confirmation.value !== password.value) {
                    let element = password_confirmation.parentNode;
                    element.setAttribute('data-status', 'error');
                    password_confirmation.parentNode.setAttribute('data-status', 'error');
                    registration_correct[element.dataset.number] = false;
                    registrationAbility(registration_correct);
                } else {
                    let element = password_confirmation.parentNode;
                    element.setAttribute('data-status', 'correct');
                    registration_correct[element.dataset.number] = true;
                    registrationAbility(registration_correct);
                }
            }
        };
    }



    /**
     * Выносим вердикт, можно ли пользователю с текущими данным зарегистрироваться
     */
    let checkbox = document.getElementById('registration-checkbox');

    if (checkbox !== null) {
        checkbox.addEventListener('click', function (c) {
            if (!checkbox.checked) {
                registration_correct[checkbox.dataset.number] = false;
                registrationAbility(registration_correct);
            } else {
                registration_correct[checkbox.dataset.number] = true;
                registrationAbility(registration_correct);
            }
        });
    }
})();