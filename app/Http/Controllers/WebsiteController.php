<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            // @TODO:fix too many redirects error
            return redirect()->back()->with([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ]);
        }

        return view('websites')->with([
            'websites' => $websites,
            'pending' => $total[0] ? $total[0]->total : 0,
            'approved' => $total[1] ? $total[1]->total : 0,
            'rejected' => $total[2] ? $total[2]->total : 0
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