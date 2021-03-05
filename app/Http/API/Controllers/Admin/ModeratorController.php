<?php

namespace App\Http\API\Controllers\Admin;

use App\Http\API\APIResponse;
use App\Http\Controllers\Controller;
use App\Model\Moderator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Admin\CreateModeratorUseCase;

class ModeratorController extends Controller
{
    public function getModerators(): JsonResponse
    {
        $moderators = Moderator::query()->orderByDesc('updated_at')->paginate($this->perPage);

        return APIResponse::ok(['moderators' => $moderators], Response::HTTP_OK);
    }

    public function createModerator(Request $request, CreateModeratorUseCase $createModeratorUseCase): JsonResponse
    {
        try {
            $createModeratorUseCase->createModerator(
                new EmailBoundary($request->email)
            );
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok([], Response::HTTP_CREATED);
    }
}