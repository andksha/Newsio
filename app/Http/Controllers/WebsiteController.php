<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Newsio\Model\Website;

class WebsiteController extends Controller
{
    public function websites(Request $request, $status = '')
    {
        $this->resolvePagination($request);

        $websites = Website::query()->status($status)->paginate($this->perPage);
        $total = DB::table('websites')->select('approved', DB::raw('count(*) as total'))
            ->groupBy('approved')
            ->get()
            ->toArray();

        return view('websites')->with([
            'websites' => $websites,
            'pending' => $total[0]->total ?? 0,
            'approved' => $total[1]->total ?? 0,
            'rejected' => $total[2]->total ?? 0
        ]);
    }

    public function apply(Request $request)
    {

    }
}