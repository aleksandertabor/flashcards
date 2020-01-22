<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" sizes="192x192" href="img/app/icons/icon-192x192.png">
    <link rel="apple-touch-icon" href="img/app/icons/icon-192x192.png">
    <meta name="theme-color" content="#1565c0">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Progressive Web App -->
    <link rel="manifest" href="/site.webmanifest">
    <!-- Styles and scripts -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body>
    <div id="app">
        @yield('content')
    </div>
</body>

</html>
