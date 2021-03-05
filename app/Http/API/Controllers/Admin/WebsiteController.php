<?php

namespace App\Http\API\Controllers\Admin;

use App\Http\API\APIResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Admin\ApproveWebsiteUseCase;
use Newsio\UseCase\Admin\RejectWebsiteUseCase;

class WebsiteController extends Controller
{
    public function approve(Request $request, ApproveWebsiteUseCase $approveWebsiteUseCase)
    {
        try {
            $website = $approveWebsiteUseCase->approve(new IdBoundary($request->website_id));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok(['website' => $website], Response::HTTP_OK);
    }

    public function reject(Request $request, RejectWebsiteUseCase $rejectWebsiteUseCase)
    {
        try {
            $website = $rejectWebsiteUseCase->reject(new IdBoundary($request->website_id), new StringBoundary($request->reason));
        } catch (ApplicationException $e) {
            return APIResponse::error($e->getMessage(), $e->getErrorData(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return APIResponse::ok(['website' => $website], Response::HTTP_OK);
    }
}