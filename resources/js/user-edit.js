"use strict";

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
})();