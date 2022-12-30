<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ Storage::url('setting/' . SettingHelper::get('logo')) }}" />
    <link href="{{ asset('/assets') }}/assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="{{ asset('/assets') }}/assets/js/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('/assets') }}/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/assets') }}/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="{{ asset('/assets') }}/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets') }}/assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />

    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    @yield('style')
    <style>
        .ck.ck-balloon-panel.ck-balloon-panel_arrow_n.ck-balloon-panel_visible.ck-balloon-panel_with-arrow {
            z-index: 10000 !important;
        }

        @media print {
            .hide-print {
                display: none !important;
            }
        }
    </style>
</head>


<body>
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    @include('backend.partials.header_top')


    <!--  END NAVBAR  -->

    <!--  BEGIN NAVBAR  -->

    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        @include('backend.partials.sidebar')
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">

            @yield('content')

            <div class="hide-print footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © {{ now()->year }} <a
                            href="#">{{ SettingHelper::get('name') }}</a>, All rights
                        reserved.</p>
                </div>
                {{-- <div class="footer-section f-section-2">
                    <p class="">Developed By <a href="https://www.fiverr.com/sojebsikder10" target="__blank">SojebSikder</a> </p>
                </div> --}}
            </div>
        </div>
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('/assets') }}/assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="{{ asset('/assets') }}/bootstrap/js/popper.min.js"></script>
    <script src="{{ asset('/assets') }}/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{ asset('/assets') }}/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('/assets') }}/assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="{{ asset('/assets') }}/assets/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="{{ asset('/assets') }}/plugins/apex/apexcharts.min.js"></script>
    <script src="{{ asset('/assets') }}/assets/js/dashboard/dash_1.js"></script>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    @yield('script')
</body>

</html>
