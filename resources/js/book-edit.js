"use strict";

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
})();