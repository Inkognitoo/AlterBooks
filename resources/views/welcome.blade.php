@php
    /** @var Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users */
    /** @var Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books */
@endphp

@extends('layouts.app')

@section('title', 'AlterBooks')

@section('content')
    <main class="landing-content">
        <div class="landing-element landing-books">
            <div class="landing-element__title landing-books__title">
                {{ t('book', 'Новинки') }}
            </div>
            <div class="landing-element__area landing-books__area">
                @foreach($books as $book)
                    @include('landing.book', ['book' => $book])
                @endforeach
            </div>
            <a class="landing-more button"
               type="button"
               href="{{ route('book.books-list') }}">
                {{ t('book.button', 'Больше книг') }}
            </a>
        </div>
        <div class="landing-element landing-users">
            <div class="landing-element__title landing-users__title">
                {{ t('user', 'Пользователи') }}
            </div>
            <div class="landing-element__area landing-users__area">
                @foreach($users as $user)
                    @include('landing.user', ['user' => $user])
                @endforeach
            </div>
            <a class="landing-more button"
               type="button"
               href="{{ route('user.users-list') }}">
                {{ t('user.button', 'Все пользователи') }}
            </a>
        </div>
    </main>
@endsection
