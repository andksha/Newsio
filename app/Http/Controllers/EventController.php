<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Boundary\TagPeriodBoundary;
use Newsio\Boundary\UseCase\CreateEventBoundary;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\BoundaryException;
use Newsio\Model\Category;
use Newsio\UseCase\AddLinksUseCase;
use Newsio\UseCase\CreateEventUseCase;
use Newsio\UseCase\GetEventsUseCase;
use Newsio\UseCase\GetTagsUseCase;
use Newsio\UseCase\SaveEventUseCase;

class EventController extends Controller
{
    public function events(Request $request, GetTagsUseCase $tagsUseCase, GetEventsUseCase $eventsUseCase, $removed = null)
    {
        $categories = Category::all();

        try {
            $tags = $tagsUseCase->getPopularAndRareTags(new TagPeriodBoundary('week'));
            $events = $eventsUseCase->getEvents(new GetEventsBoundary(
                $request->search,
                $request->tag,
                $removed,
                $request->category,
                null
            ));
        } catch (BoundaryException $e) {
            return view('event.events')->with([
                'events' => new LengthAwarePaginator(collect(), 0, $this->perPage),
                'categories' => $categories,
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ]);
        }

        return view('event.events')->with([
            'events' => $events,
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    public function create(Request $request, CreateEventUseCase $eventUseCase)
    {
        try {
            $event = $eventUseCase->create(new CreateEventBoundary(
                $request->title,
                $request->tags,
                $request->links,
                $request->category,
                auth()->id()
            ));
        } catch (ApplicationException $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['event' => $event]);
    }


    public function addLinks(Request $request, AddLinksUseCase $linksUseCase)
    {
        try {
            $newLinks = $linksUseCase->addLinks(new IdBoundary($request->event_id), new LinksBoundary($request->links));
        } catch (ApplicationException $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['new_links' => $newLinks]);
    }

    public function getTags(Request $request, GetTagsUseCase $tagsUseCase)
    {
        try {
            $tags = $tagsUseCase->getPopularAndRareTags(new TagPeriodBoundary($request->period));
        } catch (ApplicationException $e) {
            return $this->returnErrorResponse($e, Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['tags' => $tags]);
    }

    public function saveEvent(Request $request, SaveEventUseCase $uc)
    {
        try {
            $uc->save(new IdBoundary($request->event_id), new IdBoundary(auth()->id()));
        } catch (ApplicationException $e) {
            return $this->returnErrorResponse($e, Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['success' => true]);
    }
}
