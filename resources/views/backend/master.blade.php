<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ Storage::url('setting/' . SettingHelper::get('logo')) }}" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('assets') }}/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets') }}/bootstrap/css/bootstrap-icons.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets') }}/custom/css/style.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    @yield('style')
</head>


<body>
    @include('backend.partials.header_top')
    @include('backend.partials.sidebar')
    @yield('content')


    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('assets') }}/custom/js/lib/jquery-3.6.3.min.js"></script>
    <script src="{{ asset('assets') }}/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets') }}/custom/js/lib/chart.js"></script>
    <script src="{{ asset('assets') }}/custom/js/script.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    @yield('script')
</body>

</html>
