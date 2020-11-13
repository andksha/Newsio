<div class="control-panel">
    <a href="{{ url('events') }}" @if (strpos(url()->current(), 'removed') === false)class="active"@endif>
        published
    </a>
    |
    <a href="{{ url('events/removed') }}" @if (strpos(url()->current(), 'removed') !== false)class="active"@endif>
        removed
    </a>
    @if (auth()->user())
        <button class="add-button" id="add-event-button">+</button>
    @endif
</div>
<div class="row" style="position: relative;z-index: 1;">
    <div class="col-md-12" id="events">
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
            <div class="row event" id="{{ $event->id }}">
                <div class="col-md-12 event_table">
                    @if ($event->reason)
                        <span class="removed">X {{ $event->reason }}</span>
                    @endif
                    <span class="event_title">
                        {{ ucfirst($event->title) }}
                    </span>
                    @if (auth()->guard('moderator')->user())
                        @if (!$event->reason)
                            <span class="remove_event">X</span>
                            <div class="remove_block">
                                <input class="remove-input" aria-label="reason">
                                <span class="confirm_removing_event">V</span>
                            </div>
                        @else
                            <button class="restore-event">Restore</button>
                        @endif
                    @endif
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
                            @if (auth()->user())
                                <button class="add-button add-link-button">+</button>
                            @endif
                            <div class="new-links-errors"></div>
                            <div class="links">
                                @foreach ($event->links as $link)
                                    <a href="{{ $link->content }}" class="event_link" target="_blank">{{ $link->content }}</a>
                                    @if (!$link->reason && auth()->guard('moderator')->user())
                                        <span class="remove_link">X</span>
                                        <div class="remove_link_block">
                                            <input class="remove-link-input" aria-label="reason" id="link-{{ $link->id }}">
                                            <span class="confirm_removing">V</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="new-link-form">
                                <textarea aria-label="new link" class="new-links-input"></textarea>
                                <input type="submit" value="Submit" class="submit_links_button">
                            </div>
                        </div>
                        <div class="removed-links">
                            @foreach ($event->removedLinks as $removedLink)
                                <span class="event_link">
                                    <a href="{{ $removedLink->content }}">
                                        {{ $removedLink->content }}:
                                    </a><span class="removed">{{ $removedLink->reason }}</span>
                                </span>
                                @if (auth()->guard('moderator')->user())
                                    <button class="restore-link" id="link-{{ $removedLink->id }}">Restore</button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        {{ $events->links() }}
    </div>
</div>
@if (auth()->guard('moderator')->user())
    <script src="{{ asset('js/moderator_event.js') }}" type="module"></script>
@endif
<script src="{{ asset('js/event.js') }}" type="module"></script>
