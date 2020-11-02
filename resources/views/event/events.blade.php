@extends('header')

@section('content')
    <div class="control-panel">
        <span id="search-span">
            <input aria-label="search" id="search-input" placeholder="search for events"/>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search search-icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
              <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
            </svg>
        </span>
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
                        <a class="show_published_links active">published ({{ $event->links->count() }})</a>
                        |
                        <a class="show_removed_links">removed ({{ $event->removedLinks->count() }})</a>
                    </span>
                    <div class="event_tags">
                        @foreach ($event->tags as $tag)
                            <a href="{{ url(url()->current() . '?tag=' . $tag->name) }}" class="event_tag">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                    <div class="event_links">
                        <div class="published-links">
                            @foreach ($event->links as $link)
                            <a href="{{ $link->content }}" class="event_link" target="_blank">{{ $link->content }}</a>
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
