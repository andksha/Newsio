<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Newsio\Boundary\CategoryBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Boundary\TagsBoundary;
use Newsio\Boundary\TitleBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Model\Category;
use Newsio\Model\Event;
use Newsio\UseCase\CreateEventUseCase;

class EventController
{
    public function events($removed = '')
    {
        $events = $removed === 'removed'
            ? Event::onlyTrashed()->orderByDesc('updated_at')->paginate(15)
            : Event::query()->orderByDesc('updated_at')->paginate(15);

        return view('event.events')->with(['events' => $events, 'categories' => Category::all()]);
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
}
