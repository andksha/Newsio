<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Newsio\Boundary\CategoryBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Boundary\TagsBoundary;
use Newsio\Boundary\TitleBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Model\Event;
use Newsio\UseCase\CreateEventUseCase;

class EventController
{
    public function events()
    {
        $events = Event::query()->orderByDesc('updated_at')->paginate(15);

        return view('event.events')->with(['events' => $events]);
    }

    public function createForm()
    {
        return view();
    }

    public function create(Request $request)
    {
        $uc = new CreateEventUseCase();

        try {
            $event = $uc->create(
                new TitleBoundary($request->title),
                new TagsBoundary($request->tags),
                new LinksBoundary($request->links),
                new CategoryBoundary($request->category)
            );
        } catch (ApplicationException $e) {
            return view('error')->with(['message' => $e->getMessage(), 'additional_data' => $e->getAdditionalData()]);
        }

        return view('event.events')->with(['event' => $event]);
    }
}
