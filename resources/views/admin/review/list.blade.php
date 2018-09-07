@extends('admin.layouts.app')

@section('title', 'AlterBooks | Рецензии')

@section('section-name', 'Рецензии')

@section('css-vendor')
<link href="{{ url('/metronic/vendor/css/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js-vendor')
<script src="{{ url('/metronic/vendor/js/datatables.bundle.js') }}" type="text/javascript"></script>
<script src="{{ url('/metronic/js/reviews-search.js') }}" type="text/javascript"></script>
@endsection

@section('content')
<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Список текущих рецензий
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('review.create.show') }}" class="btn btn-primary m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
                        <span>
                            <i class="la la-plus"></i>
                            <span>
                                Создать
                            </span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="reviews_table">
            <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>
                    Оценка
                </th>
                <th>
                    Автор
                </th>
                <th>
                    Книга
                </th>
                <th>
                    Дата публикации
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