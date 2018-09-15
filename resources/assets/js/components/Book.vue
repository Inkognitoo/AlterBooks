<template>
    <div class="book-list-element col-12 col-clear">
        <div class="row">
            <div class="col-12 col-clear col-lg-12">
                <div class="book-list-element__aside">
                    <a class="book-list-element__cover col-2 col-end col-lg-3 col-lg-end"
                       v-bind:style="{ 'background-image': 'url(' + book.cover + ')' }"
                       v-bind:href="book.url"></a>
                </div>

                <div class="book-list-element__main">
                    <div class="row">
                        <a class="book-list-element__title col-8 col-lg-12 col-sm-clear"
                           v-bind:href="book.author.url">
                            {{ book.title }}
                        </a>

                        <div class="book-list-element-rating col-3 col-lg-0">
                            <div class="book-list-element-rating-stars">
                                <div class="book-list-element-rating-stars__fill"
                                     v-bind:class="{ 'book-list-element-rating-stars__fill_red': book.rating <= 2,
                                                    'book-list-element-rating-stars__fill_yellow': book.rating > 2 && book.rating <= 3.5,
                                                    'book-list-element-rating-stars__fill_green': book.rating > 3.5 }"
                                     v-bind:style="{ 'width': book.rating * 100 / 5 }"></div>
                                <div class="book-list-element-rating-stars__form"></div>
                            </div>
                            <div class="book-list-element-rating__number">
                                ({{ book.rating }})
                            </div>
                        </div>

                        <a class="book-list-element__author col-12 col-sm-clear"
                           v-bind:href="book.author.url">
                            {{ book.author.fullName }}
                        </a>

                        <div class="book-list-element-rating col-0 col-lg-12 col-sm-clear">
                            <div class="book-list-element-rating-stars">

                                <div class="book-list-element-rating-stars__fill"
                                    v-bind:class="{ 'book-list-element-rating-stars__fill_red': book.rating <= 2,
                                                    'book-list-element-rating-stars__fill_yellow': book.rating > 2 && book.rating <= 3.5,
                                                    'book-list-element-rating-stars__fill_green': book.rating > 3.5 }"
                                     v-bind:style="{ 'width': book.rating * 100 / 5 }"></div>
                                <div class="book-list-element-rating-stars__form"></div>
                            </div>
                            <div class="book-list-element-rating__number">
                                ({{ book.rating }})
                            </div>
                        </div>

                        <div class="book-list-element-description col-12 col-md-0" v-bind:data-status="descriptionStatus">
                            <span v-if="(book.description || '').length > 0" v-html="book.description.replace(/(?:\r\n|\r|\n)/g, '<br />')"></span>
                            <span v-else class="no-description">-описание отсутствует-</span>

                            <div class="book-list-element-description__block"></div>
                            <div class="book-list-element-description__more" v-on:click="statusChange()">
                                {{ linkName }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-clear">
                <div class="book-list-element__description row">
                    <div class="book-list-element-description col-0 col-md-12" v-bind:data-status="descriptionStatus">
                        <span v-if="(book.description || '').length > 0" v-html="book.description.replace(/(?:\r\n|\r|\n)/g, '<br />')"></span>
                        <span v-else class="no-description">-описание отсутствует-</span>

                        <div class="book-list-element-description__block"></div>
                        <div class="book-list-element-description__more" v-on:click="statusChange()">
                            {{ linkName }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="book-list-element__details col-12 col-clear">
                <div class="row">
                    <div class="book-list-element-genres col-12 col-clear">
                        <div class="row row-end">
                            <div class="book-list-element-genres__title col-2 col-end col-md-12 col-md-start">
                                жанры
                            </div>
                            <div class="book-list-element-genres__container col-10 col-md-12">
                                <a class="book-list-element-genres__element" v-for="genre in book.genres.data"
                                   v-bind:name="genre.slug"
                                   v-bind:class="{ 'book-list-element-genres__element_active': activeGenres.includes(genre.slug)}">
                                    {{ genre.name }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 col-md-0"></div>
                    <div class="book-list-element-info col-10 col-md-12">
                        <div class="book-list-element-info__element book-list-element-info__element_not-important">
                            <div class="book-list-element-info__icon
                                    icon icon__calendar_grey"></div>
                            <div class="book-list-element-info__content">
                                {{ moment(book.publishDate.date).locale('ru').format('L') }}
                            </div>
                        </div>
                        <div class="book-list-element-info__element book-list-element-info__element_not-important">
                            <div class="book-list-element-info__icon
                                    icon icon__book-reference_grey"></div>
                            <div class="book-list-element-info__content">
                                {{ pluralize(book.pageCount, ['страница', 'страницы', 'страниц']) }}
                            </div>
                        </div>
                        <a class="book-list-element-info__element" v-bind:href="book.url + '#review'">
                            <div class="book-list-element-info__icon
                                    icon icon__calendar icon__conversation_grey"></div>
                            <div class="book-list-element-info__content">
                                {{ pluralize(book.reviews.total, ['рецензия', 'рецензии', 'рецензий']) }}
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-clear">
                        <div class="row row-end">
                            <div class="col-3 col-clear col-lg-4 col-lg-clear col-md-5 col-md-clear col-sm-12 col-sm-clear">
                                <a class="book-list-element__button button"
                                   v-bind:href="book.url">
                                    профиль
                                </a>
                            </div>
                            <div class="col-3 col-clear col-lg-4 col-lg-clear col-md-5 col-md-clear col-sm-12 col-sm-clear">
                                <a class="book-list-element__button button button_green" href="">
                                    читать
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['book', 'activeGenres'],
        data: function() {
            return {
                moment: window.Moment,
                descriptionStatus: 'close',
                linkName: 'читать далее',
            }
        },
        methods:  {

                /**
                 * https://gist.github.com/znechai/1b25d0ee9a92e5b879175ab4f040dbbc
                 *
                 * Plural forms for russian words
                 * @param  {Number} count quantity for word
                 * @param  {Array} words Array of words. Example: ['депутат', 'депутата', 'депутатов'], ['коментарий', 'коментария', 'комментариев']
                 * @return {String}        Count + plural form for word
                 */
                pluralize: function (count, words) {
                    let cases = [2, 0, 1, 1, 1, 2];
                    return count + ' ' + words[ (count % 100 > 4 && count % 100 < 20) ? 2 : cases[ Math.min(count % 10, 5)] ];
                },

                statusChange: function () {
                    if (this.descriptionStatus === 'close') {
                        this.descriptionStatus = 'open';
                        this.linkName = 'свернуть';
                    } else {
                        this.descriptionStatus = 'close';
                        this.linkName = 'читать далее';
                    }
                }

        },
    }
</script>
