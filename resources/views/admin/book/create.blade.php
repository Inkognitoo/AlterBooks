@php
use \App\Models\Admin\Helper\BreadCrumbs;
use \App\Models\Admin\Book;

/** @var \App\Models\Admin\Book $book */
/** @var \Illuminate\Support\ViewErrorBag $errors */

$title = 'Создание книги';
$attributes = [
    'title', 'description', 'cover',
    'author_id', 'text', 'genres',
    'status',
];
@endphp

@extends('admin.layouts.app')

@section('title', 'AlterBooks | ' . $title)

@section('section-name', $title)
@section('breadcrumbs-separator', 'm-subheader__title--separator')
@section('breadcrumbs')
    {!! BreadCrumbs::create([
            ['Книги', route('books')],
            [$title]
    ])  !!}
@endsection

@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide">
                        <i class="la la-gear"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ $title }}
                    </h3>
                </div>
            </div>
        </div>

        <!--begin::Form-->
        <form class="m-form" id="user" method="POST"
              action="{{ route('book.create') }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="m-portlet__body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="m-form__section m-form__section--first">
                    @foreach((new Book())->getAttributesList($attributes) as $attribute => $value)
                        <div class="form-group m-form__group row">
                            <label class="col-lg-3 col-form-label">
                                {{ $attribute }}:
                            </label>
                            <div class="col-lg-6">
                                {!! (new Book())->getHtmlCreateForAttribute($attribute) !!}

                                @if ($errors->has($attribute))
                                    <span class="m-form__help m--font-danger"> {{ $errors->first($attribute) }} </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions">
                    <div class="row">
                        <div class="col-lg-3">
                            <button type="submit" class="btn btn-primary m-btn m-btn--custom m-btn--pill m-btn--icon m-btn--air">
                                <span>
                                    <i class="la la-save"></i>
                                    <span>
                                        Создать
                                    </span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!--end::Form-->
    </div>
    <!--end::Portlet-->
@endsection