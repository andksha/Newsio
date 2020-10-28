@extends('header')

@section('content')
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
        @foreach($events as $event)
            <div class="row">
                <div class="col-md-12">
                    <p class="event_title">
                        {{$event->title}}
                    </p>
                    <p>{{$event->tags}}</p>
                </div>
            </div>
        @endforeach
        </div>
        <div class="col-md-2">
            <button class="add-event-button">+</button>
        </div>
    </div>

    <script>
    </script>
@endsection
