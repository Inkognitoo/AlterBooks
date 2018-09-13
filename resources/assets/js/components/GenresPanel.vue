<template>
    <div>
        <div class="book-list-genres__title">
            список&nbsp;жанров
            <hr class="book-list-genres__hr">
        </div>
        <form class="book-list-genres__content">
            <div style="display: flex" v-bind:key="genre.id" v-for="genre in genres">
                <input type="checkbox"
                       v-bind:value="genre.slug">
                <label class="book-list-genres__element">
                    {{ genre.name }}
                </label>
            </div>
        </form>
        <div class="book-list-genres__stripe"></div>
        <div class="book-list-genres__more">
            больше жанров
        </div>
    </div>
</template>

<script>
    export default {
        name: "GenresPanel",
        data: function() {
            return {
                axios: window.Axios,
                genres: [],
            }
        },
        mounted: function () {
            console.log('teeeest');
            let self = this;
            this.axios.get('/api/v1/genres')
                .then(function (response) {
                    self.genres = response.data.data;
                });
        }
    }
</script>

<style scoped>

</style>