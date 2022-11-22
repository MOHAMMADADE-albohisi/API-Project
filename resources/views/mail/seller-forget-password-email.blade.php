@component('mail::message')
# Welcome , {{$name}}

You have requested to reset your password

@component('mail::panel')
Reset code is: {{$code}}
@endcomponent


Thanks,<br>
{{ config('app.name') }}
@endcomponent
