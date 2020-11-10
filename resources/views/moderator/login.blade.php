@extends('header')

@section('content')
Login
<form method="POST" action="{{ url('moderator/login') }}">
    @csrf
    <input aria-label="email" type="email" name="email" placeholder="Email">
    <br/>
    <input aria-label="password" type="password" name="password" placeholder="Password">
    <br/>
    <input type="submit" value="Submit">
</form>
@endsection

