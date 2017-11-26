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
                    <div class="panel-heading">Пользователи</div>
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