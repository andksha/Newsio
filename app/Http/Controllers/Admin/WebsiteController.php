<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            return response()->json(['error_message' => $e->getMessage()]);
        }

        return response()->json(['website' => $website]);
    }

    public function reject(Request $request, RejectWebsiteUseCase $rejectWebsiteUseCase)
    {
        try {
            $website = $rejectWebsiteUseCase->reject(new IdBoundary($request->website_id), new StringBoundary($request->reason));
        } catch (ApplicationException $e) {
            return response()->json(['error_message' => $e->getMessage()]);
        }

        return response()->json(['website' => $website]);
    }
}