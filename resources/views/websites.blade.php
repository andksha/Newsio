@extends('header')

@section('content')
    <div class="row websites">
        <h5>Here is a list of websites that are approved and rejected to be referred to as an event source:</h5>
        <div class="col-md-4">
            <h4>Approved</h4>
            @foreach ($approvedWebsites as $approvedWebsite)
                <a class="website" href="{{ $approvedWebsite->domain }}">{{ $approvedWebsite->domain }}</a>
            @endforeach
        </div>
        <div class="col-md-8">
            <h4>Rejected</h4>
            @foreach ($rejectedWebsites as $rejectedWebsite)
                <div class="website">
                    <a href="{{ $rejectedWebsite->domain }}">
                        {{ $rejectedWebsite->domain }}
                    </a> - {{ $rejectedWebsite->reason }}
                </div>
            @endforeach
        </div>
    </div>
@endsection