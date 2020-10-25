@extends('header')

@section('content')
    @foreach($events as $event)
        <div class="col-md-10">
            <p class="event_title">
                {{$event->title}}
            </p>
            <p>{{$event->tag}}</p>
        </div>
    @endforeach
@endsection
