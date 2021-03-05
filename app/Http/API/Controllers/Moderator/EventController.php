<?php

namespace App\Http\API\Controllers\Moderator;

use App\Http\API\APIResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Moderator\RemoveEventUseCase;
use Newsio\UseCase\Moderator\RemoveLinkUseCase;
use Newsio\UseCase\Moderator\RestoreEventUseCase;
use Newsio\UseCase\Moderator\RestoreLinkUseCase;

class EventController extends Controller
{
    /**
     * @param Request $request
     * @param RemoveEventUseCase $removeEventUseCase
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeEvent(Request $request, RemoveEventUseCase $removeEventUseCase): JsonResponse
    {
        try {
            $event = $removeEventUseCase->remove(new IdBoundary($request->event_id), new StringBoundary($request->reason));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok(['event' => $event], Response::HTTP_OK);
    }

    public function restoreEvent(Request $request, RestoreEventUseCase $restoreEventUseCase): JsonResponse
    {
        try {
            $event = $restoreEventUseCase->restore(new IdBoundary($request->event_id));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok(['event' => $event], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param RemoveLinkUseCase $removeLinkUseCase
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeLink(Request $request, RemoveLinkUseCase $removeLinkUseCase): JsonResponse
    {
        try {
            $link = $removeLinkUseCase->remove(new IdBoundary($request->link_id), new StringBoundary($request->reason));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);

        }
        return APIResponse::ok(['link' => $link], Response::HTTP_OK);
    }

    public function restoreLink(Request $request, RestoreLinkUseCase $restoreLinkUseCase): JsonResponse
    {
        try {
            $link = $restoreLinkUseCase->restore(new IdBoundary($request->link_id));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok(['link' => $link], Response::HTTP_OK);
    }
}
