<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="{{ csrf_token() }}"/>

    <title>
        {{ config('app.name') }}
    </title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,600">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container-fluid">
    <div class="title">
        {{config('app.name')}}
    </div>
    <div class="header-links">
        <a href="{{ url('/events') }}" @if(strpos(url()->current(), 'events'))class="active"@endif>Events</a>
        <a href="{{ url('/websites/approved') }}" @if(strpos(url()->current(), 'websites'))class="active"@endif>Websites</a>
        <a href="{{ url('/admin/moderators') }}" @if(strpos(url()->current(), 'admin/moderators'))class="active"@endif>Moderators</a>
        @include('search')
    </div>
    @if (session()->has('error_message') || isset($error_message))
        <div class="error-block input_error">
            @if (session()->get('error_message'))
                {{ session()->get('error_message') }}
            @elseif (isset($error_message))
                {{ $error_message }}
            @endif
        </div>
    @endif
    <div id="auth-block">
        @if (auth()->guard('admin')->user())
            <span>{{ auth()->guard('admin')->user()->email }}</span>
            <a href="{{ url('admin/logout') }}">Logout</a>
        @endif
    </div>
    <div class="response-error input_error"></div>
    <div class="row">
        <div class="col-md-12">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>

