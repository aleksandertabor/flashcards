<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flashcards - create your decks and add flashcards.</title>
    <meta name="description" content="Create your decks and add flashcards.">
    <link rel="icon" sizes="192x192" href="/images/icons/icon-192x192.png">
    <link rel="apple-touch-icon" href="/images/icons/icon-192x192.png">
    <meta name="application-name" content="Flashcards">
    <meta name="apple-mobile-web-app-title" content="Flashcards">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Flashcards">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="152x152" href="/images/icons/icon-152x152.png" type="image/png">

    <meta name="msapplication-TileImage" content="/images/icons/icon-144x144.png">
    <meta name="msapplication-TileColor" content="#1565c0">

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

    <link rel="preconnect" href="https://storage.googleapis.com">

    <link rel="preload" href="{{ asset('js/app.js') }}" as="script" crossorigin>
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style" crossorigin>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        window.App = {!! json_encode([
            'isLoggedIn' => Auth::check(),
            'user' => [
                'username' => Auth::user()->username ?? null,
                'email' => Auth::user()->email ?? null,
                'image' => Auth::check() ? Auth::user()->getFirstMediaUrl('main') : "",
            ]
        ]) !!};
    </script>
</head>

<body>
    <div id="app">
        {{ $slot }}
    </div>
    <script>
        var installPromptGlobal = null;
        window.addEventListener("beforeinstallprompt", function (e) {
            installPromptGlobal = e;
        });
    </script>
</body>

</html>
