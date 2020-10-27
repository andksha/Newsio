<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        {{ config('app.name') }}
    </title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,600">

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
{{--    <link href="http://127.0.0.1:8901/public/css/bootstrap.min.css" rel="stylesheet" type="text/css">--}}
{{--    <link href="/public/css/main.css" rel="stylesheet" type="text/css">--}}

    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }


        .title {
            font-size: 28px;
            text-align: center;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .content {
            width: 95%;
            padding: 0 5px;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .event_title {
            font-size: 16px;
            text-align: left;
        }

        .events {

        }
    </style>
</head>
<body>
<div class="content">
    <div class="title m-b-md">
        {{config('app.name')}}
    </div>
    @yield('content')
</div>
<script src="/public/js/bootstrap.js"></script>
</body>
</html>
