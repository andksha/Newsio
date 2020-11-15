<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Newsio\Boundary\UseCase\GetEventsBoundary;
use Newsio\Boundary\UseCase\GetProfileBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\Model\Category;
use Newsio\UseCase\Profile\GetProfileUseCase;

final class ProfileController extends Controller
{
    public function profile(Request $request, GetProfileUseCase $getProfileUseCase, $saved = null)
    {
        try {
            $events = $getProfileUseCase->getProfile(new GetProfileBoundary(new GetEventsBoundary($request->all()), [
                    'user_id' => auth()->id(),
                    'saved' => $saved,
                ]
            ));
        } catch (ApplicationException $e) {
            return $this->redirectBackWithError($e);
        }

        return view('user.profile')->with([
            'events' => $events,
            'categories' => Category::all()
        ]);
    }
}