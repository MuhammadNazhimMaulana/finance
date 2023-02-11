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
    <link href="/themes/cork/assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="/themes/cork/assets/js/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link rel="dns-prefetch" href="https://kit-pro.fontawesome.com">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://kit-pro.fontawesome.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="/themes/cork/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/themes/cork/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/elements/breadcrumb.css">
    <link rel="stylesheet" type="text/css" href="/themes/cork/assets/css/elements/alert.css">
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="/themes/cork/assets/css/elements/custom-pagination.css" rel="stylesheet" type="text/css" />
    {{-- Custom --}}
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}">
    {{-- End Custom --}}
    @stack('styles')
    <style>
    .layout-px-spacing {
        min-height: calc(100vh - 166px) !important;
    }
    </style>
    @stack('styles')
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
</head>

<body>
    <div class="ownloading" style="display: none;"></div>
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->
    @include('layouts._partials.header')
    <!--  BEGIN NAVBAR  -->
    <div class="sub-header-container">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </a>
            @yield('breadcrumb')
        </header>
    </div>
    <!--  END NAVBAR  -->
    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>
        @include('layouts._partials.sidebar')
        <!--  BEGIN CONTENT PART  -->
        <div id="content" class="main-content">
            @yield('content')
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">© 2020 - {{ date('Y') }} All rights reserved.</p>
                </div>
                <div class="footer-section f-section-2">
                    <p class="">Coded with
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                    </p>
                </div>
            </div>
        </div>
        <!--  END CONTENT PART  -->
    </div>
    <!-- END MAIN CONTAINER -->
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="/themes/cork/assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="/themes/cork/bootstrap/js/popper.min.js"></script>
    <script src="/themes/cork/bootstrap/js/bootstrap.min.js"></script>
    <script src="/themes/cork/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/themes/cork/assets/js/app.js"></script>
    <script>
    $(document).ready(function() {
        App.init();
    });
    </script>
    <script src="/themes/cork/assets/js/custom.js"></script>
    <script src="/themes/cork/assets/js/fontawesomepro/fontawesomepro.js"></script>
    @stack('scripts')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    {{-- MIX --}}
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script type="text/javascript">
        $( document ).ready(function() {
            $( ".showownloading" ).on( "click", function() {
                var $this = $(this);
                var modalName = $this.data('mid');
                $('#'+modalName).modal('hide');
                $('.ownloading').css('display', 'block');
            });
        });
    </script>
</body>

</html>
