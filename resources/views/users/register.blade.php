@extends('layout')

@section('title', 'Регистрация')

@section('content')

    @if ($errors->all())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <h1>Регистрация</h1>
        <form method="post" action="/users/register">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>

@stop