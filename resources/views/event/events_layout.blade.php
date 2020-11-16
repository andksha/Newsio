
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
                        @if ($event->user_id !== auth()->id())
                            @if (!$event->userSaved || (int) $event->userSaved->user_id !== (int) auth()->id())
                                <button class="save-button">Save</button>
                            @else
                                Saved
                            @endif
                        @endif
                        <a class="show_published_links active">published ({{ $event->links->count() }})</a>
                        |
                        <a class="show_removed_links">removed ({{ $event->removedLinks->count() }})</a>
                    </span>
                    <div class="event_tags">
                        @foreach ($event->tags as $tag)
                            <a href="{{ url(url()->current() . '?tag=' . $tag->name) }}" class="event_tag">{{ $tag->name }}</a>
                        @endforeach
                        <span class="view_count">
                            {{ $event->view_count }}
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.134 13.134 0 0 0 1.66 2.043C4.12 11.332 5.88 12.5 8 12.5c2.12 0 3.879-1.168 5.168-2.457A13.134 13.134 0 0 0 14.828 8a13.133 13.133 0 0 0-1.66-2.043C11.879 4.668 10.119 3.5 8 3.5c-2.12 0-3.879 1.168-5.168 2.457A13.133 13.133 0 0 0 1.172 8z"/>
                              <path fill-rule="evenodd" d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                            </svg>
                        </span>
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
