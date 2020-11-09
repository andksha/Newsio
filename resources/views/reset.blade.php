@extends('header')

@section('content')
    Reset password
    <form method="POST" action="{{ url('password/reset') }}">
        @csrf
        <input aria-label="password" type="password" name="password" placeholder="Password">
        <input aria-label="password_confirmation" type="password" name="password_confirmation" placeholder="Password confirmation">
        <input type="hidden" value="{{ $token }}" name="token">
        <input type="submit" value="Submit" id="submit_domain_button">
    </form>
@endsection
