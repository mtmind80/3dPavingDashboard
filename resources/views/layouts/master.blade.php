<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="{{ URL::asset('/assets/images/favicon.ico')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$web_config['webSitetitle']}} @yield('title')</title>

    <link rel="shortcut icon" href="{{ URL::asset('/assets/images/favicon-16x16.png')}}">

    @include('layouts.head')

    @stack('components-styles')

    @yield('css-files')
</head>
@section('body')
@show
<body data-sidebar-size="small"  data-sidebar="dark" class="{{ $bodyClass ?? null }}">
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @include('_partials.alert')

                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    @include('layouts.right-sidebar')
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')

    @stack('components-scripts')

    <!-- JVC -->
    @yield('js-plugin-files')

    <script src="{{ URL::asset('/js/common-base.min.js')}}"></script>
    <script src="{{ URL::asset('/js/extra-scripts.js')}}"></script>

    <script src="{{ URL::asset('/js/sweetalert2.min.js')}}"></script>
    <link rel="stylesheet" href="{{ URL::asset('/css/sweetalert2.min.css')}}">


    <script>
        var controllerName = "{{ \App\Helpers\RouteAction::getControllerName() }}";
        var functionName = "{{ \App\Helpers\RouteAction::getFunctionName() }}";

        var alertBox = $('#alert');

        function showErrorAlert(message, alertEl = $('#alert'))
        {
            alertEl.removeClass('alert-danger alert-success alert-info alert-warning hidden').addClass('alert-danger').find('.alert-message').html(message);
        }
        function showSuccessAlert(message, alertEl = $('#alert'))
        {
            alertEl.removeClass('alert-danger alert-success alert-info alert-warning hidden').addClass('alert-success').find('.alert-message').html(message);
        }
        function showInfoAlert(message, alertEl = $('#alert'))
        {
            alertEl.removeClass('alert-danger alert-success alert-info alert-warning hidden').addClass('alert-info').find('.alert-message').html(message);
        }
        function showWarningAlert(message, alertEl = $('#alert'))
        {
            alertEl.removeClass('alert-danger alert-success alert-info alert-warning hidden').addClass('alert-warning').find('.alert-message').html(message);
        }
        function closeAlert(alertEl = $('#alert'))
        {
            alertEl.fadeOut(300, function() {
                alertEl.removeClass('alert-danger alert-success alert-info alert-warning').addClass('hidden').removeAttr("style").find('.alert-message').html('');
            });
        }

        function closeMe() {
            $("#alertmessage").hide()
        }

        $(document).ready(function(){
            if ($("#alertmessage")) {
                //setTimeout("closeMe()",2000);
            }

            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}" }});

            let sideMenu = $('#side-menu');

            sideMenu.find('li').removeClass('mm-active');
            sideMenu.find('li a').removeClass('active');

            let activeLi = sideMenu.find('li.'+controllerName+'-controller');

            activeLi.addClass('mm-active');
            activeLi.find('a.'+functionName+'-function').addClass('active');

            $('.btn-close').on('click', function(){
                $(this).closest('.alert').fadeOut(300, function() {
                    $(this).removeClass('alert-danger alert-success alert-info alert-warning').addClass('hidden').removeAttr("style").find('.alert-message').html('');
                });
            });

            $('body').on('click', function(){
                closeAlert();
            });
        });
    </script>

    @yield('page-js')

    @stack('partials-scripts')
</body>
</html>
