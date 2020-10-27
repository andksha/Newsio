@extends('header')

@section('content')
    <a href="{{route('test')}}">test</a>
    @foreach($events as $event)
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <p class="event_title">
                    {{$event->title}}
                </p>
                <p>{{$event->tags}}</p>
            </div>
            <div class="col-md-2"></div>
        </div>
    @endforeach
@endsection
