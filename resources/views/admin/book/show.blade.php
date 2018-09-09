@php
use \App\Models\Admin\Helper\BreadCrumbs;

/** @var \App\Models\Admin\Book $book */
@endphp

@extends('admin.layouts.app')

@section('title', 'AlterBooks | ' . $book->title)

@section('section-name', $book->title)
@section('breadcrumbs-separator', 'm-subheader__title--separator')
@section('breadcrumbs')
    {!! BreadCrumbs::create([
            ['Книги', route('books')],
            [$book->title]
    ])  !!}
@endsection

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Подробная информация о Книге
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{ route('book.edit.show', ['id' => $book->id]) }}" class="btn btn-primary m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
                            <span>
                                <i class="la la-edit"></i>
                                <span>
                                    Редактировать
                                </span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <table class="table table-striped m-table">
                        <thead>
                        <tbody>
                        @foreach($book->getAttributesList() as $attribute => $value)
                            <tr>
                                <td>
                                    {{ $attribute }}
                                </td>
                                <td>
                                    {!! $book->getHtmlViewForAttribute($attribute) !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Section-->

            @if($book->trashed())
                <a href="{{ route('book.restore', ['id' => $book->id]) }}" class="btn btn-success m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
                <span>
                    <i class="la la-history"></i>
                    <span>
                        Восстановить
                    </span>
                </span>
                </a>
            @else
                <a href="{{ route('book.delete', ['id' => $book->id]) }}" class="btn btn-primary m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
                <span>
                    <i class="la la-close"></i>
                    <span>
                        Мягко удалить
                    </span>
                </span>
                </a>
            @endif

            <button type="button" class="btn btn-danger m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air" data-toggle="modal" data-target="#delete_modal">
                <span>
                    <i class="la la-warning"></i>
                    <span>
                        Жёстко удалить
                    </span>
                </span>
            </button>
        </div>
        <!--end::Form-->
    </div>

    <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        Вы действительно хотите удалить книгу "{{ $book->title }}"?
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Внимание! <br>
                        Это действие необратимо и книга будет навсегда удалёна из базы данных. <br>
                        Если вы не уверены в своих действиях, то лучше используйте мягкое удаление.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" onclick="location.href='{{ route('book.delete.permanent', ['id' => $book->id]) }}'">Удалить</button>
                </div>
            </div>
        </div>
    </div>
@endsection