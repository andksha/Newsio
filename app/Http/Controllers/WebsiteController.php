<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Newsio\Boundary\NullableStringBoundary;
use Newsio\Boundary\DomainBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Exception\BoundaryException;
use Newsio\Model\Category;
use Newsio\UseCase\Website\ApplyWebsiteUseCase;
use Newsio\UseCase\Website\GetWebsitesUseCase;

class WebsiteController extends Controller
{
    public function websites(Request $request, GetWebsitesUseCase $uc, $status = '')
    {
        $categories = Category::all();
        $this->resolvePagination($request);

        try {
            $websites = $uc->getWebsites($status, $this->perPage, new NullableStringBoundary($request->search));
            $total = $uc->getTotal();
        } catch (BoundaryException $e) {
            return view('websites')->with([
                'websites' => new LengthAwarePaginator(collect(), 0, $this->perPage),
                'categories' => $categories,
                'total' => [
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                ],
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ]);
        }

        return view('websites')->with([
            'websites' => $websites,
            'categories' => $categories,
            'total' => $total
        ]);
    }

    public function apply(Request $request, ApplyWebsiteUseCase $applyWebsiteUseCase)
    {
        try {
            $applyWebsiteUseCase->apply(new DomainBoundary($request->domain));
        } catch (ApplicationException $e) {
            return redirect()->back()->with([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ]);
        }

        return redirect()->route('websites', 'pending');
    }
}