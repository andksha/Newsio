<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Newsio\Boundary\IdBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Admin\ApproveWebsiteUseCase;

class WebsiteController extends Controller
{
    public function approve(Request $request)
    {
        $uc = new ApproveWebsiteUseCase();

        try {
            $uc->approve(new IdBoundary($request->website_id));
        } catch (ApplicationException $e) {
            return response()->json(['error_message' => $e->getMessage()]);
        }

        return response()->json(['success' => true]);
    }
}