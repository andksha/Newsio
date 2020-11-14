@extends('header')

@section('left-sidebar')
    <span class="category" onclick="window.location.href =
                    location.protocol + '//' + location.host + '/events'">
        All
    </span>
    @if (isset($categories))
        @foreach($categories as $category)
        <span class="category" onclick="window.location.href =
                location.protocol + '//' + location.host + '/events' + '?category={{ $category->id }}'">
                    {{ ucfirst($category->name) }}
        </span>
        @endforeach
    @endif
@endsection

@section('right-sidebar')
    @if (isset($tags))
        <a class="tag-filter" id="day">Day</a>
        <a class="tag-filter" id="week">Week</a>
        <a class="tag-filter" id="month">Month</a>
        <a class="tag-filter" id="year">Year</a>
        <h4>Popular tags</h4>
        <div class="popular-tags">
            @foreach($tags['popular'] as $popularTag)
                <a href="{{ url(url()->current() . '?tag=' . $popularTag->tag->name) }}" class="event_tag">{{ $popularTag->tag->name }}</a>
            @endforeach
        </div>
        <h4>Rare tags</h4>
        <div class="rare-tags">
            @foreach($tags['rare'] as $rareTag)
                <a href="{{ url(url()->current() . '?tag=' . $rareTag->tag->name) }}" class="event_tag">{{ $rareTag->tag->name }}</a>
            @endforeach
        </div>
    @endif
@endsection

@section('content')
    <div class="control-panel">
        <a href="{{ url('events') }}" @if (strpos(url()->current(), 'removed') === false)class="active"@endif>
            published
        </a>
        |
        <a href="{{ url('events/removed') }}" @if (strpos(url()->current(), 'removed') !== false)class="active"@endif>
            removed
        </a>
        @if (auth()->user() && strpos(url()->current(), '/events') !== false)
            <button class="add-button" id="add-event-button">+</button>
        @endif
    </div>
    @include('event.events_layout')
@endsection
