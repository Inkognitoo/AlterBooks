<template>
    <div class="book-list-sort col-12 col-clear">
        <div class="book-list-sort__title">
            сортировать
        </div>
        <form class="book-list-sort__content">
            <input type="radio" id="sort-date" name="sort" value="date" checked
                   v-on:click="changeSort('date')"
                   :checked="this.activeSort === 'date'">
            <label class="book-list-sort__element" for="sort-date">
                по новизне
            </label>
            <input type="radio" id="sort-rating" name="sort" value="rating"
                   v-on:click="changeSort('rating')"
                   :checked="this.activeSort === 'rating'">
            <label class="book-list-sort__element" for="sort-rating">
                по рейтингу
            </label>
            <input type="radio" id="sort-size" name="sort" value="size"
                   v-on:click="changeSort('size')"
                   :checked="this.activeSort === 'size'">
            <label class="book-list-sort__element" for="sort-size">
                по объему
            </label>
        </form>
    </div>
</template>

<script>
    export default {
        name: "SortPanel",
        props: {
            sort: {
                type: String,
                default: () => 'date'
            },
        },
        data: function() {
            return {
                activeSort: this.sort,
            }
        },
        methods:  {
            changeSort: function (sort) {
                if (this.activeSort === sort) {
                    return;
                }
                
                this.activeSort = sort;

                this.$emit('change-active-sort', sort);
            }
        },
    }
</script>