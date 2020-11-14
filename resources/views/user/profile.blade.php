@extends('header')

@section('left-sidebar')
@endsection

@section('right-sidebar')
@endsection

@section('content')
    <div class="control-panel">
        <a href="{{ url('profile') }}" @if (strpos(url()->current(), 'profile/saved') === false)class="active"@endif>
            My events
        </a>
        |
        <a href="{{ url('profile/saved') }}" @if (strpos(url()->current(), 'profile/saved') !== false)class="active"@endif>
            Saved events
        </a>
    </div>
    @include('event.events_layout')
@endsection
