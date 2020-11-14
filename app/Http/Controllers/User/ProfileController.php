<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Newsio\Model\Category;

final class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $events = new LengthAwarePaginator([], 0, 15, 1);

        return view('user.profile')->with([
            'events' => $events,
            'categories' => Category::all()
        ]);
    }
}