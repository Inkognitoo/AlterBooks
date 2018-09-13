@extends('layouts.app')

@section('title', 'Список книг')

@section('content')
<div id="app" style="display: flex">
    <div class="book-list-aside col-3 col-clear col-lg-0">
        <div class="book-list-genres" data-status="close">
            <genres-panel></genres-panel>
        </div>
    </div>

    <div class="book-list-main col-8 col-clear col-lg-10 col-md-11 col-sm-12">
        <div class="row">
            <div class="col-12 col-clear">
                <search-form></search-form>
            </div>

            <div class="book-list-sort col-12 col-clear">
                <sort-panel></sort-panel>
            </div>

            <div class="col-0 col-lg-12 col-lg-clear">
                <div class="book-list-genres book-list-genres_mini" data-status="close">
                    <genres-panel></genres-panel>
                </div>
            </div>

            <book v-bind:book="book" v-bind:key="book.id" v-for="book in books.data"></book>
            <div v-if="books.total === 0" class="text-center">
                Нет ни одной книги, доступной для чтения
            </div>

            <div class="row row-center">
                <div class="col-12 col-clear col-center">
                    <!-- LINKS -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection