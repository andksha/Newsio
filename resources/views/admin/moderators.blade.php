@extends('admin.header')

@section('content')
    <div class="align-items-center moderators">
        @foreach($moderators as $moderator)
            <div class="moderator">
                {{ $moderator->email }}
                @if ($moderator->email_verified_at)
                    <span title="verified at">{{ $moderator->email_verified_at }}</span>
                @endif
            </div>
            <hr/>
        @endforeach
        Add moderator:
        <form method="POST" action="{{ url('admin/moderator') }}">
            @csrf
            <input aria-label="email" name="email">
            <br/>
            <input type="submit" value="Submit">
        </form>
    </div>
@endsection
