<!DOCTYPE html>
<html class="no-js">
    <head>
        {{-- <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('title', _('MERIDIAN LUTSK'))</title>
         <meta name="description" content="@yield('description', _(''))">
        <meta name="viewport" content="width=device-width, initial-scale=1"> --}}

        @section('SEOMeta')
            {!! SEOMeta::generate() !!}
        @show

        @section('OpenGraph')
            {!! OpenGraph::generate() !!}
        @show

        @section('Twitter')
            {!! Twitter::generate() !!}
        @show

        <link rel="apple-touch-icon" sizes="180x180" href="{{ static_asset('/src/favicons/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ static_asset('/src/favicons/favicon-32x32.png') }}" sizes="32x32">
        <link rel="icon" type="image/png" href="{{ static_asset('/favicon-16x16.png') }}" sizes="16x16">
        <link rel="manifest" href="{{ static_asset('/src/favicons/manifest.json') }}">
        <link rel="mask-icon" href="{{ static_asset('/src/favicons/safari-pinned-tab.svg') }}" color="#2daae1">
        <meta name="theme-color" content="#ffffff">

        <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300&amp;subset=cyrillic" rel="stylesheet">
        <link rel="stylesheet" href="{{ static_asset('/src/css/app.css') }}">

        <script src="{{ static_asset('/src/js/vendor/modernizr.min.js') }}"></script>

        <script>
            window.onesignal = {};

            window.onesignal.appId = "{{ env('ONESIGNAL_APP_ID') }}";
            window.onesignal.safariWebId = "{{ env('SAFARI_WEB_ID') }}";
            window.onesignal.subdomainName = "{{ env('ONESIGNAL_DOMAIN_NAME') }}";
        </script>

    </head>

    <body>
        @include('parts.header')

        @yield('content')

        @include('parts.footer')

        @include('parts.scripts')

    </body>
</html>
