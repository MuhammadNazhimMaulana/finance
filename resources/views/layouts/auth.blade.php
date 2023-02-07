<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ url('/favicon') }}/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ url('/favicon') }}/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ url('/favicon') }}/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ url('/favicon') }}/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ url('/favicon') }}/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ url('/favicon') }}/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ url('/favicon') }}/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ url('/favicon') }}/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/favicon') }}/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ url('/favicon') }}/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('/favicon') }}/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ url('/favicon') }}/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('/favicon') }}/favicon-16x16.png">
    <link rel="manifest" href="{{ url('/favicon') }}/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ url('/favicon') }}/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="/themes/cork/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/themes/cork/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="/themes/cork/assets/css/authentication/form-2.css" rel="stylesheet" type="text/css" />
    <link href="/themes/cork/assets/css/forms/theme-checkbox-radio.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/elements/alert.css">
    <!-- END GLOBAL MANDATORY STYLES -->
</head>

<body class="form">
    @yield('content')
    <script src="/themes/cork/assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="/themes/cork/bootstrap/js/popper.min.js"></script>
    <script src="/themes/cork/bootstrap/js/bootstrap.min.js"></script>
    <script src="/themes/cork/assets/js/authentication/form-2.js"></script>
    <script src="/themes/cork/assets/js/fontawesomepro/fontawesomepro.js"></script>
</body>

</html>
