<template>
    <div class="book-list-genres" v-bind:class="{ 'book-list-genres_mini': mini }" v-bind:data-status="status">
        <div class="book-list-genres__title">
            список&nbsp;жанров
            <hr class="book-list-genres__hr">
        </div>
        <form class="book-list-genres__content">
            <div style="display: flex" v-bind:key="genre.id" v-for="genre in genres">
                <input type="checkbox"
                       v-model="genre.checked"
                       v-bind:value="genre.slug">
                <label class="book-list-genres__element"
                       v-on:click="changeGenreStatus(genre)" >
                    {{ genre.name }}
                </label>
            </div>
        </form>
        <div class="book-list-genres__stripe"></div>
        <div class="book-list-genres__more" v-on:click="showMoreStatusChange">
            {{ linkName }}
        </div>
    </div>
</template>

<script>
    export default {
        name: "GenresPanel",
        props: {
            mini: {
                type: Boolean,
                default: () => false
            },
            allGenres: {
                type: Array,
                default: () => []
            },
            activeGenres: {
                type: Array,
                default: () => []
            },
        },
        data: function() {
            return {
                axios: window.Axios,
                status: 'close',
                linkName: 'больше жанров',
            }
        },
        computed: {
            /**
             * На основе списка активных жанров, вычисляем как отображать список обычных жанров
             *
             * @returns {*[]}
             */
            genres: function () {
                let genres = [];
                let allGenres = this.allGenres;
                let activeGenre = undefined;
                for (let i = 0; i < this.activeGenres.length; i++ ) {
                    activeGenre = allGenres.find((genre) => {
                        return genre.slug === this.activeGenres[i];
                    });
                    if (activeGenre !== undefined) {
                        activeGenre.checked = true;
                        allGenres = allGenres.filter((genre) => {
                           return genre.id !== activeGenre.id
                        });
                        genres.push(activeGenre);
                    }
                }

                return genres.concat(allGenres.map((element) => {
                    element.checked = false;

                    return element;
                }));
            }
        },
        methods:  {
            changeGenreStatus: function (genre) {
                let activeGenre = this.activeGenres;
                if (genre.checked) {
                    activeGenre = activeGenre.filter((element) => {
                        return element !== genre.slug;
                    });
                } else {
                    activeGenre.push(genre.slug)
                }

                this.$emit('change-active-genres', activeGenre);
            },

            showMoreStatusChange: function () {
                if (this.status === 'close') {
                    this.status = 'open';
                    this.linkName = 'свернуть';
                } else {
                    this.status = 'close';
                    this.linkName = 'больше жанров';
                }
            }
        },
    }
</script>

<style scoped>

</style>