"use strict";

import axios from "axios";

(function () {

    /**
     *  Установление длины текстовых полей
     */

    let info_form = document.getElementById('user-edit-info');
    if (info_form !== null) {
        let input = document.getElementsByClassName('edit-block-element__content_input');
        Array.prototype.forEach.call(input, function(field){
            let size = field.parentNode.nextSibling.nextSibling;
            size.innerHTML = field.value.length + ' / ' + field.getAttribute('maxlength');
            field.oninput = function () {
                size.innerHTML = field.value.length + ' / ' + field.getAttribute('maxlength');

                this.classList.remove('edit-block-element__content_error-field');
                document.getElementById('edit-error-' + this.name).innerHTML = '&nbsp;';
                document.getElementsByClassName('edit-block-status_correct')[0].style.display = 'none';
            };
        });

        let textareas = document.getElementById('user-edit-info').getElementsByClassName('edit-block-element_textarea');
        Array.prototype.forEach.call(textareas, function (textarea) {
            textarea.getElementsByTagName('textarea')[0].oninput = function () {
                this.classList.remove('edit-block-element__content_error-field');
                document.getElementById('edit-error-' + this.name).innerHTML = '&nbsp;';
                document.getElementsByClassName('edit-block-status_correct')[0].style.display = 'none';
            };
        });
    }


    /**
     *  Проверка совпадения нового пароля и его повтора
     */

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


    /**
     *  Изменение логина и/или пароля
     */

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


    /**
     *  Изменение информации о пользователе
     */

    let userEditInfoForm = document.getElementById('user-edit-info');
    if (userEditInfoForm) {
        let userEditButton = document.getElementById('user-edit-info-button');
        userEditButton.addEventListener('click', function (e) {
            editUserInfo(userEditInfoForm);
        });
    }


    /**
     * Обрабатываем процесс изменения информации о пользователе
     *
     */
    function editUserInfo(parentElement) {
        let data = {};
        data['nickname'] = document.getElementById('change_nickname').value;
        data['name'] = document.getElementById('change_name').value;
        data['surname'] = document.getElementById('change_surname').value;
        data['patronymic'] = document.getElementById('change_patronymic').value;
        data['birthday_data'] = document.getElementById('change_birthday_date').value;
        data['about'] = document.getElementById('change_about').value;

        data.gender = 'n';
        Array.prototype.forEach.call(document.getElementsByName('gender'), function (gender) {
            data['gender'] = gender.checked ? gender.value : data.gender;
        });


        validateApiUserInfo(data)
            .then(function (response) {
                showUserCorrect(parentElement);
                document.getElementById('user-edit-to-profile').href = response.data;
                window.history.pushState(null, '', 'http://alterbooks/user/' + data.nickname + '/edit');
            })
            .catch(function (response) {
                showUserErrors(response.errors, parentElement);
            });
    }

    /**
     * Api запрос для изменения данных пользователя
     *
     * @param {object} data данные формы
     * @returns {Promise<any>}
     */
    function validateApiUserInfo(data) {
        let url = `/api/v1/user/edit/info`;

        return new Promise(function (resolve, reject) {
            request.post(url, {
                nickname: data.nickname,
                name: data.name,
                surname: data.surname,
                patronymic: data.patronymic,
                birthday_date: data.birthday_data,
                gender: data.gender,
                about: data.about
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
     *  Изменение даныных пользователя дял входа
     */

    let userEditEmailForm = document.getElementById('user-edit-email');
    if (userEditEmailForm) {
        let userEditButton = document.getElementById('user-edit-email-button');
        userEditButton.addEventListener('click', function (e) {
            editUserEmail(userEditEmailForm);
        });
    }


    /**
     * Обрабатываем процесс изменения даныных пользователя дял входа
     *
     */
    function editUserEmail(parentElement) {
        let data = {};
        data.email = document.getElementById('change_email').value;
        data.password = document.getElementById('change_password').value;
        data.password_confirmation = document.getElementById('change_password_confirmation').value;
        data.old_password = document.getElementById('old_password').value;

        validateApiUserEmail(data)
            .then(function (response) {
                showUserCorrect(parentElement);
            })
            .catch(function (response) {
                showUserErrors(response.errors, parentElement);
            });
    }

    /**
     * Api запрос для изменения даныных пользователя дял входа
     *
     * @param {object} data данные формы
     * @returns {Promise<any>}
     */
    function validateApiUserEmail(data) {
        let url = `/api/v1/user/edit/email`;

        return new Promise(function (resolve, reject) {
            request.post(url, {
                email: data.email,
                password: data.password,
                password_confirmation: data.password_confirmation,
                old_password: data.old_password
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
     *  @param {object} parentElement
     */
    function showUserErrors(errors, parentElement) {
        Array.prototype.forEach.call(errors, function (error) {
            document.getElementById('edit-error-' + error.name).innerText = error.message;
            document.getElementById('change_' + error.name).classList.add('edit-block-element__content_error-field');
            parentElement.getElementsByClassName('edit-block-status_correct')[0].style.display = 'none';
        });
    }

    /**
     *  Выводим сообщение об успешном редактирования данных
     */
    function showUserCorrect(parentElement) {
        parentElement.getElementsByClassName('edit-block-status_correct')[0].style.display = 'flex';
    }
})();