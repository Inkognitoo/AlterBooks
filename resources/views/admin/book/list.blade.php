@extends('admin.layouts.app')

@section('title', 'AlterBooks | Книги')

@section('section-name', 'Книги')

@section('css-vendor')
<link href="{{ url('/metronic/vendor/css/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js-vendor')
<script src="{{ url('/metronic/vendor/js/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ url('/metronic/js/books-search.js') }}" type="text/javascript"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Список текущих книг
                </h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="book_table">
            <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>
                    Название
                </th>
                <th>
                    Автор
                </th>
                <th>
                    Статус
                </th>
                <th>
                    Действия
                </th>
            </tr>
            </thead>
        </table>
        <!--end: Datatable -->
    </div>
</div>
@endsection