<template>
    <div>
        <div class="book-list-sort__title">
            сортировать
        </div>
        <form class="book-list-sort__content">
            <input type="radio" id="sort-date" name="sort" value="date" checked>
            <label class="book-list-sort__element" for="sort-date" disabled>
                по новизне
            </label>
            <input type="radio" id="sort-rating" name="sort" value="rating" disabled>
            <label class="book-list-sort__element" for="sort-rating">
                по рейтингу
            </label>
            <input type="radio" id="sort-size" name="sort" value="size" disabled>
            <label class="book-list-sort__element" for="sort-size">
                по объему
            </label>
        </form>
    </div>
</template>

<script>
    export default {
        name: "SortPanel"
    }
</script>