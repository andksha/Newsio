@extends('header')

@section('content')
    <div class="published-removed-events">
        <a href="{{ url('events') }}" @if (strpos(url()->current(), 'removed') === false)class="active"@endif>
            published
        </a>
        |
        <a href="{{ url('events/removed') }}" @if (strpos(url()->current(), 'removed') !== false)class="active"@endif>
            removed
        </a>
        <button id="add-event-button">+</button>
    </div>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" id="events">
            <div class="row" id="inputs">
                <div class="col-md-6">
                    <div>
                        <label for="event_title_input">Title</label>
                        <input id="event_title_input"/>
                        <span id="event_title_error"></span>
                    </div>
                    <div>
                        <label for="event_tags_input">Tags</label>
                        <input id="event_tags_input"/>
                        <span id="event_tags_error"></span>
                    </div>
                    <div>
                        <label for="event_links_input">Links</label>
                        <textarea id="event_links_input"></textarea>
                        <span id="event_links_error"></span>
                    </div>
                    <div>
                        <label for="event_category_input">Category</label>
                        <select id="event_category_input">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <span id="event_category_error"></span>
                    </div>
                    <input type="submit" value="Submit" id="submit_event_button">
                </div>
                <div class="col-md-4 error_message"></div>
            </div>
        @foreach($events as $event)
            <div class="row event">
                <div class="col-md-12 event_table">
                    @if ($event->reason)
                        <span class="removed">X {{ $event->reason }}</span>
                    @endif
                    <span class="event_title">
                        {{ ucfirst($event->title) }}
                    </span>
                    <span class="published-removed-links">
                        <a href="#" class="show_published_links active">published ({{ $event->links->count() }})</a>
                        |
                        <a href="#" class="show_removed_links">removed ({{ $event->removedLinks->count() }})</a>
                    </span>
                    <div class="event_tags">
                        @foreach ($event->tags as $tag)
                            <span class="event_tag">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <div class="event_links">
                        <div class="published-links">
                            @foreach ($event->links as $link)
                            <a href="{{ $link->content }}" class="event_link">{{ $link->content }}</a>
                            @endforeach
                        </div>
                        <div class="removed-links">
                            @foreach ($event->removedLinks as $removedLink)
                                <span class="event_link">
                                    <a href="{{ $removedLink->content }}">
                                        {{ $removedLink->content }}
                                    </a>: {{ $removedLink->reason }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
    <script>
    </script>
@endsection
