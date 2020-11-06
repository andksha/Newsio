<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Newsio\Boundary\CategoryBoundary;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Boundary\TagsBoundary;
use Newsio\Boundary\TitleBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Admin\RemoveEventUseCase;
use Newsio\UseCase\EditEventUseCase;

class EventController extends Controller
{
    public function edit(Request $request)
    {
        $uc = new EditEventUseCase();

        try {
            $event = $uc->edit(
                $request->id,
                new TitleBoundary($request->title),
                new TagsBoundary($request->tags),
                new LinksBoundary($request->links),
                new CategoryBoundary($request->category)
            );
        } catch (ApplicationException $e) {
            return view('error')->with(['message' => $e->getMessage()]);
        }

        return view('event.events')->with(['event' => $event]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function removeEvent(Request $request)
    {
        $uc = new RemoveEventUseCase();

        try {
            $event = $uc->remove(new IdBoundary($request->event_id), new StringBoundary($request->reason));
        } catch (ApplicationException $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['event' => $event]);
    }
}
