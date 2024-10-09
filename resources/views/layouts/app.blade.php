<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @includeIf('partials.favicons')
</head>
<body class="antialiased min-vh-100">
@includeIf('partials.header')
<main>{{ $slot }}</main>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
