<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
@include('layouts.head')
<body>
@inertia

@routes

@client
@vite('app')
</body>
</html>
