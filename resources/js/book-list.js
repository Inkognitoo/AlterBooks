"use strict";

(function () {
    let genres_more = document.getElementsByClassName('book-list-genres__more');

    Array.prototype.forEach.call(genres_more, function(more){
        more.addEventListener('click', function (m) {
            let parent = more.parentNode;
            let block = more.previousElementSibling;

            if (parent.getAttribute('data-status') === 'close') {
                parent.style.maxHeight = 'none';
                parent.style.overflow = 'visible';
                parent.style.marginBottom = '50px';
                parent.setAttribute('data-status', 'open');

                block.style.display = 'none';

                more.style.bottom = '-25px';
                more.style.top = 'auto';
                more.innerHTML = 'свернуть';
            } else {
                if (document.documentElement.clientWidth <= 1023) {
                    parent.style.maxHeight = '150px';
                    more.style.top = '110px';
                    block.style.top = '110px';
                } else {
                    parent.style.maxHeight = '625px';
                    more.style.top = '590px';
                    block.style.top = '590px';
                }
                parent.style.overflow = 'hidden';
                parent.style.marginBottom = '20px';

                block.style.display = 'block';

                more.style.bottom = 'auto';
                more.innerHTML = 'больше жанров';

                parent.setAttribute('data-status', 'close');
            }
        })
    });


    // Сортировка по жанрам


    let genres = document.getElementsByName('genre-1');
    Array.prototype.forEach.call(genres, function(genre){
        genre.addEventListener('click', function (g) {
            let combined_genre = document.getElementById('genre-2-' + genre.dataset.id);
            combined_genre.checked = genre.checked;

            let book_genres = document.getElementsByName(genre.value);

            Array.prototype.forEach.call(book_genres, function(book_genre){
                if (book_genre.classList.contains('book-list-element-genres__element')) {
                    if (genre.checked) {
                        book_genre.classList.add('book-list-element-genres__element_active');
                    } else {
                        book_genre.classList.remove('book-list-element-genres__element_active');
                    }
                }
            });
        })
    });

    let mini_genres = document.getElementsByName('genre-2');
    Array.prototype.forEach.call(mini_genres, function(mini_genre){
        mini_genre.addEventListener('click', function (g) {
            let combined_genre = document.getElementById('genre-1-' + mini_genre.dataset.id);
            combined_genre.checked = mini_genre.checked;

            let book_genres = document.getElementsByName(mini_genre.value);

            Array.prototype.forEach.call(book_genres, function(book_genre){
                if (book_genre.classList.contains('book-list-element-genres__element')) {
                    if (mini_genre.checked) {
                        book_genre.classList.add('book-list-element-genres__element_active');
                    } else {
                        book_genre.classList.remove('book-list-element-genres__element_active');
                    }
                }
            });
        })
    });

    let descriptions = document.getElementsByClassName('book-list-element-description__more');
    Array.prototype.forEach.call(descriptions, function(more){
        more.addEventListener('click', function (m) {
            let parent = more.parentNode;
            console.log('yes');
            if (parent.getAttribute('data-status') === 'close') {
                more.innerHTML = 'свернуть';
                parent.setAttribute('data-status', 'open');
            } else {
                more.innerHTML = 'читать далее';
                parent.setAttribute('data-status', 'close');
            }
        });
    });
})();