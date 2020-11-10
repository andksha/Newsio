<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Controller as BaseController;
use Newsio\Contract\ApplicationException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var int
     */
    protected int $perPage = 15;

    /**
     * @var int
     */
    protected int $currentPage = 1;

    protected function resolvePagination(Request $request)
    {
        $this->currentPage = (int)$request->input('page', 1);
        $this->perPage = (int)$request->input('per_page', 15);

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }

    public function redirectBackWithError(ApplicationException $e)
    {
        return redirect()->back()->with([
            'error_message' => $e->getMessage(),
            'error_data' => $e->getErrorData()
        ]);
    }
}
