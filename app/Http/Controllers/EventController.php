<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Newsio\Boundary\CategoryBoundary;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Boundary\NullableStringBoundary;
use Newsio\Boundary\TagsBoundary;
use Newsio\Boundary\TitleBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\BoundaryException;
use Newsio\Model\Category;
use Newsio\UseCase\AddLinksUseCase;
use Newsio\UseCase\CreateEventUseCase;
use Newsio\UseCase\GetEventsUseCase;

class EventController
{
    public function events(Request $request, $removed = null)
    {
        $uc = new GetEventsUseCase();
        $categories = Category::all();

        try {
            $events = $uc->getEvents(
                new NullableStringBoundary($request->search),
                new NullableStringBoundary($request->tag),
                new NullableStringBoundary($removed)
            );
        } catch (BoundaryException $e) {
            // @TODO:fix too many redirects error
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }

        return view('event.events')->with(['events' => $events, 'categories' => $categories]);
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
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['event' => $event]);
    }


    public function addLinks(Request $request)
    {
        $uc = new AddLinksUseCase();

        try {
            $newLinks = $uc->addLinks(new IdBoundary($request->event_id), new LinksBoundary($request->links));
        } catch (ApplicationException $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['new_links' => $newLinks]);
    }
}
