<template>
    <div class="row row-center">
        <div class="col-12 col-clear col-center">
            <div class="pagination" v-if="this.pageCount > 1">

                <span class="pagination__element pagination__element_text"
                      v-on:click="changePage(activePage - 1)"
                      :disabled="(activePage === 1)" >
                    Назад
                </span>
                <span class="pagination__element pagination__element_symbol"
                      v-on:click="changePage(activePage - 1)"
                      :disabled="(activePage === 1)" >
                    &lt;
                </span>

                <span class="pagination__element" v-for="page in pages"
                      v-bind:class="{
                        'pagination__element_dots': (page === MANY_PAGE_LABEL),
                        'pagination__element_current': (page === activePage)
                      }"
                      v-on:click="changePage(page)" >
                    {{ page }}
                </span>

                <span class="pagination__element pagination__element_text"
                      v-on:click="changePage(activePage + 1)"
                      :disabled="(activePage === pageCount)" >
                    Вперед
                </span>
                <span class="pagination__element pagination__element_symbol"
                      v-on:click="changePage(activePage + 1)"
                      :disabled="(activePage === pageCount)" >
                    &gt;
                </span>

            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: "Paginate",
        props: {
            pageCount: {
                type: Number,
                default: () => 1
            },
            currentPage: {
                type: Number,
                default: () => 1
            },
        },
        data: function() {
            return {
                MANY_PAGE_LABEL: '...',
                activePage: this.currentPage,
            }
        },
        computed: {
            /**
             * На основе количества страниц, вычисляем как отображать пагинацию
             *
             * @returns [Number]
             */
            pages: function () {
                let pages = this.activePage;

                pages = this.activePage === 1 ? pages : String(this.activePage - 1) + ',' + pages;
                pages = this.activePage === this.pageCount ? pages : pages + ',' + String(this.activePage + 1);

                if (this.activePage - 2 >= 1) {
                    pages = this.activePage - 2 === 1 ? '1,' + pages : '1,' + this.MANY_PAGE_LABEL + ',' + pages;
                }

                if (this.activePage + 2 <= this.pageCount) {
                    pages = this.activePage + 2 === this.pageCount ? pages + ',' + String(this.pageCount) : pages + ',' + this.MANY_PAGE_LABEL + ',' + String(this.pageCount);
                }

                return pages.split(',').map((number) => {
                    if (number === this.MANY_PAGE_LABEL) {
                        return number;
                    }

                    return parseInt(number);
                })
            }
        },
        methods:  {
            changePage: function (page) {
                if (page === this.activePage ||
                    page === this.MANY_PAGE_LABEL ||
                    page < 1 ||
                    page > this.pageCount) {

                    return;
                }

                this.activePage = page;

                this.$emit('change-active-page', page);
            }
        },
    }
</script>