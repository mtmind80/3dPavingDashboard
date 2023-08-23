<?php

?>


        <!DOCTYPE html>
<html>
<style>


    body, html {
        height: 100%;
    }

    .bg {
        /* The image used */
        background-image: url("{{ URL::asset('/assets/images/3D-letterhead.jpg')}}");

        /* Full height */
        height: 100%;

        /* Center and scale the image nicely */
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

</style>

<head>
    <title>Hi There Buddy</title>
</head>
<body>
<h1>{{ $title }}</h1>
<p>{{ $date }}</p>
<p>Loram ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
    consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
    cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
</body>
</html>

