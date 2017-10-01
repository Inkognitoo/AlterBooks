@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">User profile</div>

                <div class="panel-body">

                    Welcome to {{$user->name}}'s page!
                    @if ($isOwner)
                        (And yes, this is you)
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
