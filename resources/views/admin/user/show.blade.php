@php
use \App\Models\Admin\Helper\BreadCrumbs;

/** @var \App\Models\Admin\User $user */
@endphp

@extends('admin.layouts.app')

@section('title', 'AlterBooks | ' . $user->full_name)

@section('section-name', $user->full_name)
@section('breadcrumbs-separator', 'm-subheader__title--separator')
@section('breadcrumbs')
    {!! BreadCrumbs::create([
            ['Пользователи', route('users')],
            [$user->full_name]
    ])  !!}
@endsection

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Подробная информация о пользователе
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{ route('user.edit.show', ['id' => $user->id]) }}" class="btn btn-primary m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
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
            <!--begin::Section-->
            <div class="m-section">
                <div class="m-section__content">
                    <table class="table table-striped m-table">
                        <thead>
                        <tbody>
                        @foreach($user->getAttributesList() as $attribute => $value)
                            <tr>
                                <td>
                                    {{ $attribute }}
                                </td>
                                <td>
                                    {!! $user->getHtmlViewForAttribute($attribute) !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end::Section-->

            <a href="#" class="btn btn-primary m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
                <span>
                    <i class="la la-close"></i>
                    <span>
                        Мягко удалить
                    </span>
                </span>
            </a>

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
                        Вы действительно хотите удалить пользователя "{{ $user->full_name }}"?
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Внимание! <br>
                        Это действие необратимо и пользователь будет навсегда удалён из базы данных. <br>
                        Если вы не уверены в своих действиях, то лучше используйте мягкое удаление.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger">Удалить</button>
                </div>
            </div>
        </div>
    </div>
@endsection