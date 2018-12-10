"use strict";

import axios from "axios";

(function () {
    let info_form = document.getElementById('user-edit-info');
    if (info_form !== null) {
        let input = document.getElementsByClassName('edit-block-element__content_input');
        Array.prototype.forEach.call(input, function(field){
            let size = field.parentNode.nextSibling.nextSibling;
            size.innerHTML = field.value.length + ' / ' + field.getAttribute('maxlength');
            field.oninput = function () {
                size.innerHTML = field.value.length + ' / ' + field.getAttribute('maxlength');
            };
        });
    }

    // Изменение логина и/или пароля
    let email_form = document.getElementById('user-edit-email');
    if (email_form !== null) {
        let password = document.getElementsByClassName('edit-block-element__content_password');
        let re_password = document.getElementsByClassName('edit-block-element__content_re-password');

        let button_re_password = re_password[0].parentNode.parentNode;
        while (true) {
            if ((button_re_password.nodeType !== 3) && (button_re_password.classList.contains('button'))) {
                break;
            }
            button_re_password = button_re_password.nextSibling;
        }

        re_password[0].oninput = function () {
            if ((password[0].value === '') && (password[0].value === re_password[0].value)) {
                button_re_password.disabled = false;
            } else if ((password[0].value !== '') && (password[0].value === re_password[0].value)) {
                re_password[0].setAttribute('data-status', 'correct');
                button_re_password.disabled = false;
            } else {
                re_password[0].setAttribute('data-status', 'error');
                button_re_password.disabled = true;
            }
        };
        password[0].oninput = function () {
            if ((password[0].value === '') && (password[0].value === re_password[0].value)) {
                button_re_password.disabled = false;
            } else if ((password[0].value !== '') && (password[0].value === re_password[0].value)) {
                re_password[0].setAttribute('data-status', 'correct');
                button_re_password.disabled = false;
            } else {
                re_password[0].setAttribute('data-status', 'error');
                button_re_password.disabled = true;
            }
        };
    }

    let request = axios.create({
        headers: {'Authorization': 'Bearer ' + document.getElementsByName('api_token')[0].content}
    });

    let blockingElement = document.getElementsByClassName('edit-block-blocking__subject')[0];
    if (blockingElement) {
        let status = blockingElement.dataset.status === 'correct';
        blockingEdit(!status);

        let blockingInput = blockingElement.getElementsByTagName('input')[0];
        blockingInput.oninput = function () {
            blockingEdit(true);
            if (blockingInput.value.length >= 6) {
                inputActivePassword();
            }
        }
    }

    /**
     * Обрабатываем процесс ввода старого пароля
     *
     */
    function inputActivePassword() {
        let blockingInputValue = blockingElement.getElementsByTagName('input')[0].value;

        checkingActivePassword(blockingInputValue)
            .then(function (response) {
                blockingEdit(!response.success);
            })
            .catch(function (response) {
                blockingEdit(!response.success);
            });
    }

    /**
     * Api запрос для проверки валидности пароля
     *
     * @param {string} value текущее значение пароля
     * @returns {Promise<any>}
     */
    function checkingActivePassword(value) {
        let url = `/api/v1/user/edit/checking/password`;

        return new Promise(function (resolve, reject) {
            request.put(url, {
                password: value
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

    function blockingEdit(status) {
        let blockedElement = document.getElementsByClassName('edit-block-blocking__object')[0];
        let blockedContent = blockedElement.childNodes;
        Array.prototype.forEach.call(blockedContent, function(blockedChild){
            if (blockedChild instanceof Element) {
                if (blockedChild.classList.contains('edit-block-element')) {
                    blockedChild.getElementsByTagName('input')[0].disabled = status;
                }
                if (blockedChild.tagName === 'INPUT') {
                    blockedChild.disabled = status;
                }
            }
        });

        let blockingElement = document.getElementsByClassName('edit-block-blocking__subject')[0];
        blockingElement.dataset.status = status ? 'blocking' : 'correct' ;

        let errorBlock = blockingElement.parentNode;
        errorBlock = errorBlock.getElementsByClassName('edit-block-element__content_error')[0];
        errorBlock.innerHTML = status? '&nbsp;' : 'Внимание! Изменение логина и/или пароля приведет к изменению данных для авторизации на сайте' ;
    }
})();