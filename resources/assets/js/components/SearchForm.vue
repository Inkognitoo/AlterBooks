<template>
    <form class="book-list-search" onsubmit="return false">
        <input class="book-list-search__input" id="book-search" type="text"
               placeholder="название произведения"
               v-model="title">
        <input class="book-list-search__button" type="submit" value=""
               v-on:click="changeTitle(title)">
        <div class="book-list-search-variants" v-if="this.tips.length > 0">
            <span class="book-list-search-variants__element" v-for="tip in this.tips"
                  onclick="alert('ololo')">
                {{ tip }}
            </span>
        </div>
    </form>
</template>

<script>
    export default {
        name: "SearchForm",
        props: {
            currentTitle: {
                type: status,
                default: () => null
            },
        },
        data: function() {
            return {
                tips: [],
                title: this.currentTitle,
                axios: window.Axios,
            }
        },
        methods:  {
            chooseTip: function (tip) {
                console.log(tip);
                this.title = tip;
            },
            changeTitle: function (title) {
                this.$emit('change-title', title);
            }
        },
        watch: {
            'title': function (title) {
                if (title.length > 0) {
                    this.axios.get('/api/v1/books/tips', {
                        params: {
                            'title': title
                        }})
                        .then((response) => {
                            this.tips = response.data.data;
                        });
                } else {
                    this.tips = [];
                }
            }
        }
    }
</script>