<template>
    <form class="book-list-search" onsubmit="return false">
        <input class="book-list-search__input" id="book-search" type="text"
               placeholder="название произведения">
        <input class="book-list-search__button" type="submit" value="">
        <!-- <div class="book-list-search-variants">
        -- тут могли бы быть ваши варианты --
        </div> -->
    </form>
</template>

<script>
    export default {
        name: "SearchForm"
    }
</script>