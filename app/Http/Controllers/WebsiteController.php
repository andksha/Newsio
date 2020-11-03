<?php

namespace App\Http\Controllers;

use Newsio\Model\Website;

class WebsiteController extends Controller
{
    public function websites()
    {
        // @TODO: paginate websites
        list($approvedWebsites, $rejectedWebsites) = Website::all()->partition(fn ($w) => $w->approved === true);

        return view('websites')->with([
            'approvedWebsites' => $approvedWebsites,
            'rejectedWebsites' => $rejectedWebsites
        ]);
    }
}