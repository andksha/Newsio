@extends('header')

@section('content')
    Change default password
    <form method="POST" action="{{ url('moderator/confirmation') }}">
        @csrf
        <input aria-label="password" type="password" name="password" placeholder="Password">
        <br/>
        <input aria-label="password_confirmation" type="password" name="password_confirmation" placeholder="Password confirmation">
        <br/>
        <input type="hidden" name="token" value="{{ $token }}">
        <br/>
        <input type="submit" value="Submit">
    </form>
@endsection
