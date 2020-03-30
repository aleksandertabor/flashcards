<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" sizes="192x192" href="/images/icons/icon-192x192.png">
    <link rel="apple-touch-icon" href="/images/icons/icon-192x192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Flashcards">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <link rel="apple-touch-icon" sizes="152x152" href="/images/icons/icon-152x152.png" type="image/png">

    <meta name="theme-color" content="#1565c0">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Progressive Web App -->
    <link rel="manifest" href="/site.webmanifest">
    <!-- Styles and scripts -->
    <style>
        @keyframes loading-bounce {
            0%,
            25%,
            50%,
            75%,
            100% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }

            15% {
                -webkit-transform: translateY(-20px);
                transform: translateY(-20px);
            }

            35% {
                -webkit-transform: translateY(-12px);
                transform: translateY(-12px);
            }
        }

        @-webkit-keyframes loading-bounce {

            0%,
            25%,
            50%,
            75%,
            100% {
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }

            15% {
                -webkit-transform: translateY(-20px);
                transform: translateY(-20px);
            }

            35% {
                -webkit-transform: translateY(-12px);
                transform: translateY(-12px);
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>

<body>
    <div id="app">
        @yield('content')
    </div>
    <script>
        var installPromptGlobal = null;
        window.addEventListener("beforeinstallprompt", function (e) {
            installPromptGlobal = e;
        });
    </script>
</body>

</html>
