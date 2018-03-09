@component('mail::message')

{{ $exception->getMessage() }}

{{ $exception->getFile() . ':' . $exception->getLine() }}

@endcomponent
