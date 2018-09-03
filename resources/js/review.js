"use strict";

(function () {
    let read_more = document.getElementsByClassName('review-text__more');

    if (read_more !== null) {
        Array.prototype.forEach.call(read_more, function(more){
            more.addEventListener('click', function (m) {
                let text = more.parentNode;
                let block = more.previousElementSibling;

                if (text.getAttribute('data-status') === 'close') {
                    text.style.maxHeight = 'none';
                    text.style.marginBottom = '40px';
                    text.style.overflow = 'visible';

                    block.style.display = 'none';

                    more.style.top = 'auto';
                    more.style.bottom = '-1.5em';
                    more.innerHTML = 'свернуть';

                    text.setAttribute('data-status', 'open');
                } else {
                    text.style.maxHeight = '16.5em';
                    text.style.marginBottom = '20px';
                    text.style.overflow = 'hidden';

                    block.style.display = 'block';

                    more.style.top = 'calc(15em - 2px)';
                    more.style.bottom = 'auto';
                    more.innerHTML = 'читать далее';

                    text.setAttribute('data-status', 'close');
                }
            })
        });
    }

    let downloadTab = document.getElementById('book-buttons-tab');
    let dowloadButtons = document.getElementById('download-buttons');
    if (dowloadButtons !== null) {
        let bookButtons = downloadTab.parentNode;
        if((bookButtons !== null) && (dowloadButtons !== null)) {
            downloadTab.style.height = (bookButtons.offsetHeight + dowloadButtons.offsetHeight + 20) + 'px';
        }
    }
})();