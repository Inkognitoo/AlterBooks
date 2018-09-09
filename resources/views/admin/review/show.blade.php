@php
use \App\Models\Admin\Helper\BreadCrumbs;

/** @var \App\Models\Admin\Review $review */
@endphp

@extends('admin.layouts.app')

@section('title', 'AlterBooks | ' . $review->id)

@section('section-name', $review->id)
@section('breadcrumbs-separator', 'm-subheader__title--separator')
@section('breadcrumbs')
    {!! BreadCrumbs::create([
            ['Рецензии', route('reviews')],
            [$review->id]
    ])  !!}
@endsection

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Подробная информация о рецензии
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{ route('review.edit.show', ['id' => $review->id]) }}" class="btn btn-primary m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
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
                        @foreach($review->getAttributesList() as $attribute => $value)
                            <tr>
                                <td>
                                    {{ $attribute }}
                                </td>
                                <td>
                                    {!! $review->getHtmlViewForAttribute($attribute) !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Section-->

            @if($review->trashed())
                <a href="{{ route('review.restore', ['id' => $review->id]) }}" class="btn btn-success m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
            <span>
                <i class="la la-history"></i>
                <span>
                    Восстановить
                </span>
            </span>
                </a>
            @else
                <a href="{{ route('review.delete', ['id' => $review->id]) }}" class="btn btn-primary m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
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
    </div>

    <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        Вы действительно хотите удалить рецензию "{{ $review->id }}"?
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Внимание! <br>
                        Это действие необратимо и рецензия будет навсегда удалена из базы данных. <br>
                        Если вы не уверены в своих действиях, то лучше используйте мягкое удаление.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" onclick="location.href='{{ route('review.delete.permanent', ['id' => $review->id]) }}'">Удалить</button>
                </div>
            </div>
        </div>
    </div>
@endsection