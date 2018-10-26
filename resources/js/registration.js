"use strict";

import axios from 'axios';

(function () {
    let registration = document.getElementsByName('registration');
    if (registration) {
        let re_password_success = false;
        let required_success = false;

        let registration_button = document.getElementById('registration-button');
        let checkbox = document.getElementById('registration-checkbox');

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

                        re_password_success = false;
                        registrationAgreement();
                    } else {
                        let element = password_confirmation.parentNode;
                        element.setAttribute('data-status', 'correct');

                        re_password_success = true;
                        registrationAgreement();
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

                        re_password_success = false;
                        registrationAgreement();

                    } else {
                        let element = password_confirmation.parentNode;
                        element.setAttribute('data-status', 'correct');

                        re_password_success = true;
                        registrationAgreement();
                    }
                }
            };
        }

        /**
         *  Проверяем обязательные поля
         */
        let elements = Array.from(document.getElementsByClassName(' registration-element_validated'));

        let data = [];
        let names = [];

        elements.forEach(function(element, index) {
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

            data[index] = '';
            names[index] = element_input.name;
        });

        let post_data = {};

        elements.forEach(function(element, index) {
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
                data[index] =  element_input.value;
                names.forEach(function(item, index) {
                    post_data[item] = data[index];
                });

                let request = axios.create({});

                validateApiRegistration(post_data)
                    .then(function (response) {
                        registrationRequiredCorrect(element, element_input, element_error);
                    })
                    .catch(function (error) {
                        registrationCatchErrors(error);
                    });

                /**
                 * Api запрос для валидации поля
                 *
                 * @returns {Promise<any>}
                 */
                function validateApiRegistration(post_data) {
                    let url = `/api/v1/registration/validate`;

                    return new Promise(function (resolve, reject) {
                        request.post(url, post_data)
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
            };
        });

        /**
         * Выводим ошибки
         */
        function registrationCatchErrors(response) {
            elements.forEach(function(element) {
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

                element_error.innerHTML = '';
                element.dataset.status = element_input.value === '' ? '' : 'correct' ;

                response.errors.forEach(function(error) {
                    if (error.name === element_input.name) {
                        element_error.innerHTML = error.message;
                        element.dataset.status = 'error';
                    }
                });

                required_success = false;
                registrationAgreement();
            });
        }

        /**
         * Выводим состояние поля в отсутствии ошибок
         */
        function registrationRequiredCorrect() {
            elements.forEach(function(element) {
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

                element_error.innerHTML = '';
                element.dataset.status = element_input.value === '' ? '' : 'correct' ;

                required_success = true;
                registrationAgreement();
            });
        }

        /**
         *  Определяем, можно ли пользователю зарегистрироваться
         */

        checkbox.onclick = registrationAgreement;

        function registrationAgreement() {
            registration_button.disabled = !(re_password_success && required_success && checkbox.checked);
        }
    }
})();