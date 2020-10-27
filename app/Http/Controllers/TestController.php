<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Newsio\Boundary\CategoryBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Boundary\TagsBoundary;
use Newsio\Boundary\TitleBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\UseCase\CreateEventUseCase;

class TestController extends Controller
{
    public function test()
    {

        $uc = new CreateEventUseCase();

        try {
            $event = $uc->create(
                new TitleBoundary('test1'),
                new TagsBoundary(['ebr', 'bre', 'brtbrtb', 'xzcbf']),
                new LinksBoundary(['erberbwedwef', 'eservbetrdewb', 'everdwevet', 'brbyrnvdewrvt']),
                new CategoryBoundary(1)
            );
        } catch (ApplicationException $e) {
            return view('error')->with(['message' => $e->getMessage()]);
        }
//        location ~* \.(jpg|jpeg|gif|css|png|js|ico|html)$ {
//    access_log off;
//        expires max;
//    }
//
//    location /public/css/main.css {
//        alias /application/public/css/main.css;
//    }
//
//    location /public/js/ {
//        alias /application/public/js/;
//    }
//
//    location ~ /\.ht {
//        deny all;
//    }
//
//    location ~* \.(?:ico|css|js|jpeg|JPG|png|svg|woff)$ {
//    expires 365d;
//    }

        return redirect('/events');
    }
}
