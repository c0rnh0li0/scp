<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#fff"/>
    <meta name="mobile-wep-app-capable" content="yes">
    <meta name="apple-mobile-wep-app-capable" content="yes">

    <link rel="manifest" href="{{url('/manifest.json')}}">

    <link rel="icon" type="image/png" sizes="96x96" href="/img/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/icons/favicon-16x16.png">

    {{-- icons for IOS devices --}}
    <link rel="apple-touch-icon" sizes="60x60" href="/img/icons/apple-60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/icons/apple-76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/img/icons/apple-120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/img/icons/apple-152.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/img/icons/apple-167.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/icons/apple-180.png">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="preconnect prerender stylesheet" href="{{ asset('css/app.css') }}">
    <script>
        window.Laravel = {!! json_encode([
                'siteName' => config('app.name'),
                'siteUrl' => config('app.url'),
                'apiUrl' => config('app.url') . '/api',
                'loginUrl' => route('api_login'),
                'registerUrl' => route('api_register'),
                'logoutUrl' => route('api_logout'),
                'csrf' => csrf_token()
            ]) !!};
    </script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" type="module"></script>
</head>
<body>
    <div id="app">
        <v-app app>
            <v-content app>
                <v-offline @detected-condition="checkOnlineStatus"></v-offline>
                <loading :isloading="loading"></loading>
                <index v-if="inSession" @logout="logout"></index>
                <home v-else @login="login"
                             @register="register"
                             @actions="actions"
                             @loginclose="loginclose"
                             @registerclose="registerclose"
                             :login-errors="loginErrors"
                             :register-errors="registerErrors"
                             :register-dialog="registerDialog"
                             :login-dialog="loginDialog">
                </home>
                <install-banner></install-banner>
            </v-content>
        </v-app>
    </div>
</body>
</html>