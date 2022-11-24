@component('mail::message')
# اهلا بك  , {{$name}}

لقد طلبت إعادة تعيين كلمة المرور الخاصة بك
@component('mail::panel')
رمز إعادة التعيين هو: {{$code}}
@endcomponent


شكرًا,<br>
{{ config('app.name') }}
@endcomponent
