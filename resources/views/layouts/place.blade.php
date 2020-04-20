<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="manifest" href="{{url('/manifest.json')}}">
    <meta name="theme-color" content="#fff"/>

    {{-- icons for IOS devices --}}
    {{-- <link rel="apple-touch-icon" sizes="60x60" href="/icons/apple-60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/icons/apple-76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/icons/apple-120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/apple-152.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/icons/apple-167.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-180.png">
    <meta name="apple-mobile-web-app-capable" content="yes"> --}}

    {{-- checks for service worker support.if you have the push manager package then use this line
        if ('serviceWorker' in navigator && 'PushManager' in window) instead of
        if ('serviceWorker' in navigator ) --}}
    <script>
        if ('serviceWorker' in navigator ) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    // Registration was successful
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    // registration failed :(
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        window.Laravel = {!! json_encode([
                'siteName' => config('app.name'),
                'siteUrl' => config('app.url'),
                'apiUrl' => config('app.url') . '/api',
                'logoutUrl' => route('api_logout'),
                'csrf' => csrf_token()
            ]) !!};
    </script>
</head>
<body>
<div id="app">
    <v-app>
        <v-content>
            <v-container></v-container>
        </v-content>
    </v-app>
</div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>