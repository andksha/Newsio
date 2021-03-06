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
        @if (auth()->guard('admin')->user())
            <a href="{{ url('/admin/moderators') }}" @if(strpos(url()->current(), 'admin/moderators'))class="active"@endif>Moderators</a>
        @endif
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
        @if (!auth()->user() && !auth()->guard('moderator')->user())
            <button id="register">Register</button>
            <button id="login">Login</button>
            <div id="register-block">
                <input aria-label="email" id="register-email" placeholder="Email">
                <input aria-label="password" type="password" id="register-password" placeholder="Password" autocomplete="new-password">
                <input aria-label="password_confirmation" type="password" id="register-password_confirmation" placeholder="Password confirmation" autocomplete="new-password">
                <input aria-label="submit" id="submit-registration" type="submit" value="Submit">
            </div>
            <div id="login-block">
                <input aria-label="email" id="login-email" placeholder="Email" autocomplete="username">
                <input aria-label="password" type="password" id="login-password" placeholder="Password" autocomplete="current-password">
                <input aria-label="submit" id="submit-login" type="submit" value="Submit">
            </div>
            <span id="reset-password">Forgot password?</span>
            <div id="reset-password-block">
                <input aria-label="email" id="reset-email" placeholder="Email">
                <input aria-label="submit" id="submit-reset" type="submit" value="Submit">
            </div>
        @elseif (auth()->guard('moderator')->user())
            <span>{{ auth()->guard('moderator')->user()->email }}</span>
            <a href="{{ url('moderator/logout') }}">Logout</a>
        @else
            <a href="{{ url('/profile') }}">{{ auth()->user()->email }}</a>
            <a href="{{ url('logout') }}">Logout</a>
            @if (!auth()->user()->email_verified_at)
                <a href="{{ url('repeat-confirmation') }}">Resend confirmation email</a>
            @endif
        @endif
    </div>
    <div class="response-error input_error"></div>
    <div class="row">
        <div class="col-md-2 left-sidebar">
            @yield('left-sidebar')
        </div>
        <div class="col-md-8">
            @yield('content')
        </div>
        <div class="col-md-2 right-sidebar">
            @yield('right-sidebar')
        </div>
    </div>
</div>
<script src="{{ asset('js/main.js') }}" type="module"></script>
</body>
</html>
