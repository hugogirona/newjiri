@component('mail::message')
# Introduction

Le Jiri "{{$jiri->name}}" à bien été crée

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
