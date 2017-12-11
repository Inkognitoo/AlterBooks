@php
    /** @var Illuminate\Database\Eloquent\Collection|\App\User[] $users */
@endphp

@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
    <div class="user-content container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading text-center books-list-heading">
                        <div>Список пользователей</div>

                        <div class="btn-group books-list-sort">
                            <button type="button" class="btn btn-default dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @switch(Request::get('sort'))
                                    @case('rating')
                                        По рейтингу <span class="caret"></span>
                                        @break
                                    @case('books')
                                        По количеству книг <span class="caret"></span>
                                        @break
                                    @default
                                        По рейтингу <span class="caret"></span>
                                @endswitch
                            </button>
                            <ul class="dropdown-menu books-list-sort__open">
                                <li><a href="{{ route('user.users-list', ['sort' => 'rating']) }}">По рейтингу</a></li>
                                <li><a href="{{ route('user.users-list', ['sort' => 'books']) }}">По количеству книг</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div>
                            @if($users->isEmpty())
                                <div class="text-center">
                                    Увы! Этим сайтом еще никто не пользуется. Станьте первым!
                                </div>
                            @endif

                            @foreach ($users as $user)
                                @include('user.user-profile-mini')
                            @endforeach
                        </div>
                        <div class="col-md-12 text-center">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection