<?php

namespace App\Http\API\Controllers\User;

use App\Http\API\APIResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Boundary\UseCase\GetProfileBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Model\Category;
use Newsio\UseCase\Profile\GetProfileUseCase;

final class ProfileController extends Controller
{
    public function profile(Request $request, GetProfileUseCase $getProfileUseCase, $saved = null): JsonResponse
    {
        try {
            $events = $getProfileUseCase->getProfile(new GetProfileBoundary(new GetEventsBoundary($request->all()), [
                    'user_id' => auth()->id(),
                    'saved' => $saved,
                ]
            ));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok([
            'events' => $events,
            'categories' => Category::all()
        ], Response::HTTP_OK);
    }
}