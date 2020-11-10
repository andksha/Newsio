<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Moderator;
use Illuminate\Http\Request;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\Admin\CreateModeratorUseCase;

class ModeratorController extends Controller
{
    public function getModerators()
    {
        $moderators = Moderator::query()->orderByDesc('updated_at')->paginate($this->perPage);

        return view('admin/moderators')->with(['moderators' => $moderators]);
    }

    public function createModerator(Request $request, CreateModeratorUseCase $uc)
    {
        try {
            $uc->createModerator(
                new EmailBoundary($request->email)
            );
        } catch (ApplicationException $e) {
            return redirect()->back()->with([
                'error_message' => $e->getMessage(),
                'error_data' => $e->getErrorData()
            ]);
        }

        return redirect()->route('get_moderators');
    }
}