<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | 3D Paving</title>
    <link rel="shortcut icon" href="{{ URL::asset('/assets/images/favicon.ico')}}">
    @include('layouts.head')

    @yield('css-files')
</head>
@yield('body')
@yield('content')
@include('layouts.vendor-scripts')
@yield('page-js')
</html>
