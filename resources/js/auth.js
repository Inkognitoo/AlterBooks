"use strict";

import axios from 'axios';

// Api
(function () {
    let auth_button = document.getElementById('auth-button');
    let url = '/api/v1/login';

    auth_button.onclick = function authUser() {
        let email = document.getElementById('email');
        let password = document.getElementById('password');
        return new Promise(function (resolve, reject) {
            axios.post(url, {
                email: email.value,
                password: password.value
            })
                .then(function (response) {
                    email.parentNode.setAttribute('data-status', '');
                    password.parentNode.setAttribute('data-status', '');

                    let page_body = document.body;
                    let modal_close_button = document.getElementsByClassName('authentication__close');
                    Array.prototype.forEach.call(modal_close_button, function(button){
                        let modal_number = button.getAttribute('data-modal-number');
                        let modal = document.getElementById('modal-' + modal_number);
                        page_body.setAttribute('data-status', 'modal-close');
                        modal.setAttribute('data-status', 'modal-close');
                    });


                })
                .catch(function (error) {
                    switch (error.response.data.data) {
                        case 'email':
                            email.parentNode.setAttribute('data-status', 'error');
                            password.parentNode.setAttribute('data-status', '');
                            break;
                        case 'password':
                            password.parentNode.setAttribute('data-status', 'error');
                            email.parentNode.setAttribute('data-status', '');
                            break;
                    }
                });
        });
    }
})();

// Modal auth
(function () {

    let page_body = document.body;
    let modal_button = document.getElementsByClassName('modal-button');
    Array.prototype.forEach.call(modal_button, function(button){
        button.addEventListener('click', function (b) {
            let modal_number = button.getAttribute('data-modal-number');
            let modal = document.getElementById('modal-' + modal_number);
            page_body.setAttribute('data-status', 'modal-open');
            modal.setAttribute('data-status', 'modal-open');
        })
    });

    let modal_close_button = document.getElementsByClassName('authentication__close');
    Array.prototype.forEach.call(modal_close_button, function(button){
        button.addEventListener('click', function (b) {
            let modal_number = button.getAttribute('data-modal-number');
            let modal = document.getElementById('modal-' + modal_number);
            page_body.setAttribute('data-status', 'modal-close');
            modal.setAttribute('data-status', 'modal-close');
        })
    });

    let forget_password = document.getElementsByClassName('authentication__forget');
    Array.prototype.forEach.call(forget_password, function(button){
        button.addEventListener('click', function (b) {
            let authentication = button.parentNode.parentNode.parentNode.parentNode;
            let re_password = authentication.nextSibling.nextSibling;
            authentication.setAttribute('data-status', 'close');
            re_password.setAttribute('data-status', 'open');
        });
    });

    let back_forget_password = document.getElementsByClassName('authentication__back');
    Array.prototype.forEach.call(back_forget_password, function(button){
        button.addEventListener('click', function (b) {
            let re_password = button.parentNode.parentNode.parentNode;
            let authentication = re_password.previousSibling.previousSibling;
            re_password.setAttribute('data-status', 'close');
            authentication.setAttribute('data-status', 'open');
        });
    });

    let send_mail = document.getElementsByClassName('authentication__button_send');
    Array.prototype.forEach.call(send_mail, function(button){
        button.addEventListener('click', function (b) {
            let send = button.parentNode.parentNode.parentNode;
            send.setAttribute('data-status', 'correct');
            let usual = send.previousSibling.previousSibling;
            let time = document.getElementById('authentication__correct-message');

            let seconds = 5;
            time.innerHTML = seconds;
            seconds--;
            let time_id = setInterval(function () {
                time.innerHTML = seconds;
                seconds--;
            }, 1000);

            setTimeout(function () {
                usual.parentNode.parentNode.setAttribute('data-status', 'modal-close');
                page_body.setAttribute('data-status', 'modal-close');
                usual.setAttribute('data-status', 'open');
                send.setAttribute('data-status', 'close');
                clearInterval(time_id);
            }, 5000);
        });
    });
})();
