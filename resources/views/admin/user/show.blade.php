@php
use \App\Models\Admin\Helper\BreadCrumbs;

/** @var \App\Models\Admin\User $user */

$attributes = [
    'id', 'nickname', 'email', 'password', 'remember_token', 'api_token', 'avatar',
    'name', 'surname', 'patronymic', 'gender', 'birthday_date', 'about', 'timezone',
    'is_admin', 'created_at', 'updated_at',
];
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
                        <a href="#" class="btn btn-primary m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
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
                        @foreach($user->getAttributesList($attributes) as $attribute => $value)
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
        </div>
        <!--end::Form-->
    </div>
@endsection