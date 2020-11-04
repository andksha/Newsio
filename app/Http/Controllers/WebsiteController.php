<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Newsio\Boundary\NullableStringBoundary;
use Newsio\Boundary\DomainBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\BoundaryException;
use Newsio\UseCase\Website\ApplyWebsiteUseCase;
use Newsio\UseCase\Website\GetWebsitesUseCase;

class WebsiteController extends Controller
{
    public function websites(Request $request, $status = '')
    {
        $uc = new GetWebsitesUseCase();
        $this->resolvePagination($request);

        try {
            $websites = $uc->getWebsites($status, $this->perPage, new NullableStringBoundary($request->search));
            $total = $uc->getTotal();
        } catch (BoundaryException $e) {
            return view('websites')->with([
                'websites' => new LengthAwarePaginator(collect(), 0, $this->perPage),
                'pending' => 0,
                'approved' => 0,
                'rejected' => 0,
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ]);
        }

        return view('websites')->with([
            'websites' => $websites,
            'pending' => $total['pending'],
            'approved' => $total['approved'],
            'rejected' => $total['rejected']
        ]);
    }

    public function apply(Request $request)
    {
        $uc = new ApplyWebsiteUseCase();

        try {
            $uc->apply(new DomainBoundary($request->domain));
        } catch (ApplicationException $e) {
            return redirect()->back()->with([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ]);
        }

        return redirect()->route('websites', 'pending');
    }
}