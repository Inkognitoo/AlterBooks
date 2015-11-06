@extends('layouts.main')

@section('title', 'AlterBooks')

@section('content')
    <div class="row header">
        <h1><span class="red">A</span>lter<span class="red">B</span>ooks</h1>
        <p class="moto">книги для людей, а не издательств</p>
    </div>
    <div id="dynamic-wrapper">
        <div class="row mainform">
            <form name="subscribeform" id="subscribe_form">
                <input type="text" class="textfield" placeholder="Введите ваш e-mail...">
                <button class="button" id="send_button"><span class="button-sign">ПОДПИСАТЬСЯ</span></button>
            </form>
        </div>
    </div>
@endsection
