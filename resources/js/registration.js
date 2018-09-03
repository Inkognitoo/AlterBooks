"use strict";

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
})();