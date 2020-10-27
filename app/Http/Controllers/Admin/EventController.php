<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Newsio\Boundary\CategoryBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Boundary\TagsBoundary;
use Newsio\Boundary\TitleBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\EditEventUseCase;

class EventController extends Controller
{
    public function edit(Request $request)
    {
        $uc = new EditEventUseCase();

        try {
            $event = $uc->edit(
                $request->id,
                new TitleBoundary($request->title),
                new TagsBoundary($request->tags),
                new LinksBoundary($request->links),
                new CategoryBoundary($request->category)
            );
        } catch (ApplicationException $e) {
            return view('error')->with(['message' => $e->getMessage()]);
        }

        return view('event.events')->with(['event' => $event]);
    }
}
