@extends('layouts.main')

@section('title', 'AlterBooks')

@section('content')
    <div class="row header">
        <h1><span class="red">A</span>lter<span class="red">B</span>ooks</h1>
        <p class="moto">книги для людей, а не издательств</p>
    </div>
    <div id="dynamic-wrapper">
        <div class="row icon unsubscribe"></div>
        <p class="info-text mail">{{ $email }}</p>
        <p class="info-text">Вы отписались от новостной рассылки AlterBooks! Очень жаль! Возвращайтесь к нам!</p>
    </div>
@endsection