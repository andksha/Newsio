<?php

namespace App\Http\API\Controllers;

use App\Http\API\APIResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Boundary\TagPeriodBoundary;
use Newsio\Boundary\UseCase\CreateEventBoundary;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Boundary\UserIdentifierBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\BoundaryException;
use Newsio\Lib\PRedis;
use Newsio\Model\Category;
use Newsio\UseCase\AddLinksUseCase;
use Newsio\UseCase\CreateEventUseCase;
use Newsio\UseCase\GetEventsUseCase;
use Newsio\UseCase\GetTagsUseCase;
use Newsio\UseCase\IncrementViewCountUseCase;
use Newsio\UseCase\SaveEventUseCase;

class EventController extends Controller
{
    public function events(Request $request, PRedis $client, GetTagsUseCase $tagsUseCase, GetEventsUseCase $eventsUseCase, $removed = null): JsonResponse
    {
        $categories = $client->remember('categories.all', function () {
            return Category::all();
        }, 604800); // one week

        try {
            $tags = $tagsUseCase->getPopularAndRareTags(new TagPeriodBoundary('week'));
            $events = $eventsUseCase->getEvents(new GetEventsBoundary(array_merge($request->all(), [
                    'removed' => $removed,
                    'user_id' => auth()->id()
                ])
            ));
        } catch (BoundaryException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok([
            'events' => $events,
            'categories' => $categories,
            'tags' => $tags
        ], Response::HTTP_OK);
    }

    public function create(Request $request, CreateEventUseCase $eventUseCase): JsonResponse
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
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok(['event' => $event], Response::HTTP_CREATED);
    }

    public function addLinks(Request $request, AddLinksUseCase $linksUseCase): JsonResponse
    {
        try {
            $newLinks = $linksUseCase->addLinks(new IdBoundary($request->event_id), new LinksBoundary($request->links));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok(['new_links' => $newLinks], Response::HTTP_CREATED);
    }

    public function getTags(Request $request, GetTagsUseCase $tagsUseCase): JsonResponse
    {
        try {
            $tags = $tagsUseCase->getPopularAndRareTags(new TagPeriodBoundary($request->period));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_BAD_REQUEST);
        }

        return APIResponse::ok(['tags' => $tags], Response::HTTP_OK);
    }

    public function saveEvent(Request $request, SaveEventUseCase $saveEventUseCase): JsonResponse
    {
        try {
            $saveEventUseCase->save(new IdBoundary($request->event_id), new IdBoundary(auth()->id()));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_BAD_REQUEST);
        }

        return APIResponse::ok(['success' => true], Response::HTTP_OK);
    }

    public function incrementViewCount(Request $request, IncrementViewCountUseCase $countUseCase): JsonResponse
    {
        try {
            $countUseCase->incrementViewCount(
                new IdBoundary($request->event_id),
                new UserIdentifierBoundary(
                    new StringBoundary($request->ip()),
                    new StringBoundary($request->userAgent())
                )
            );
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok(['success' => true], Response::HTTP_ACCEPTED);
    }
}
