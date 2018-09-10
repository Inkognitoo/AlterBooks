@component('mail::message')

{{ $message }}

{{ $file . ':' . $line }}

@endcomponent
