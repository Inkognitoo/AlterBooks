@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Профиль пользователя</div>

                <div class="panel-body">
                    Добро пожаловать на страницу пользователя {{ $user->name }}!
                    @auth
                        @if(Auth::user()->id == $user->id)
                            (И да, это ваша страница)
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
