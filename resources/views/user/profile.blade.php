@php
    /** @var \App\Models\User $user */
    /** @var Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books */
@endphp

@extends('layouts.app')

@section('title', $user->full_name)

@section('canonical', $user->canonical_url)

@section('content')

@endsection
