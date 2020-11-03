@extends('header')

@section('content')
    <div class="row websites">
        <div class="header-links">
            <a href="{{ url('/websites/pending') }}" @if(strpos(url()->current(), 'pending'))class="active"@endif>Pending ({{ $pending }})</a>
            <a href="{{ url('/websites/approved') }}" @if(strpos(url()->current(), 'approved'))class="active"@endif>Approved ({{ $approved }})</a>
            <a href="{{ url('/websites/rejected') }}" @if(strpos(url()->current(), 'rejected'))class="active"@endif>Rejected ({{ $rejected }})</a>
        </div>
        <div class="col-md-6">
            @foreach ($websites as $website)
                <a class="website" href="{{ $website->domain }}">{{ $website->domain }}</a>
                @if ($website->approved === false)
                    - {{ $website->reason }}
                @endif
            @endforeach
            {{ $websites->links() }}
        </div>
    </div>
@endsection