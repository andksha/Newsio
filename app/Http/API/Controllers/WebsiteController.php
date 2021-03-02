<?php

namespace App\Http\API\Controllers;

use App\Http\API\APIResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Newsio\Boundary\NullableStringBoundary;
use Newsio\Boundary\DomainBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\BoundaryException;
use Newsio\UseCase\Website\ApplyWebsiteUseCase;
use Newsio\UseCase\Website\GetWebsitesUseCase;

class WebsiteController extends Controller
{
    public function websites(Request $request, GetWebsitesUseCase $uc, $status = ''): JsonResponse
    {
        $this->resolvePagination($request);

        try {
            $websites = $uc->getWebsites($status, $this->perPage, new NullableStringBoundary($request->search));
            $total = $uc->getTotal();
        } catch (BoundaryException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok([
            'websites' => $websites,
            'total' => $total
        ], Response::HTTP_OK);
    }

    public function apply(Request $request, ApplyWebsiteUseCase $applyWebsiteUseCase): JsonResponse
    {
        try {
            $applyWebsiteUseCase->apply(new DomainBoundary($request->domain));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok([], Response::HTTP_ACCEPTED);
    }
}