@extends('admin.header')

@section('content')
    Login
    <form method="POST" action="{{ url('admin/login') }}">
        @csrf
        <input aria-label="email" name="email">
        <br/>
        <input aria-label="password" name="password" type="password">
        <br/>
        <input type="submit" value="Submit">
    </form>
@endsection
