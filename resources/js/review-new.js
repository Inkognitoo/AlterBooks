"use strict";

(function () {
    let review_create_button = document.getElementsByClassName('review-new-create__button');
    Array.prototype.forEach.call(review_create_button, function(button){
        let create = button.parentNode;
        let form = create.nextSibling.nextSibling;

        button.addEventListener('click', function (b) {
            create.setAttribute('data-status', 'close');
            form.setAttribute('data-status', 'open');

            let header_length = 0;
            let header = document.getElementById('nr-header');
            let output_header = document.getElementById('review-new-form__remain');
            header.oninput = function () {
                header_length = this.value.length;
                output_header.innerHTML = String(67 - header_length);
            };
        })
    });

    let review_self = document.getElementById('review-self');
    let review_new = document.getElementById('review-new');
    let review_text = document.getElementById('review-text');

    function newReviewForm() {
        let header_length = 0;
        let header = document.getElementById('nr-header');
        let output_header = document.getElementById('review-new-form__remain');
        if (header.value === '') {
            header.setAttribute('data-has-error', 'true');
        }
        header.oninput = function () {
            header.setAttribute('data-has-error', 'false');
            header_length = this.value.length;
            output_header.innerHTML = String(67 - header_length);
        };

        let content = document.getElementById('nr-content');
        if (content.value === '') {
            content.setAttribute('data-has-error', 'true');
        }
        content.oninput = function () {
            content.setAttribute('data-has-error', 'false');
        };

        review_new.setAttribute('data-status', 'close');
        review_self.setAttribute('data-status', 'open');
    }
})();

