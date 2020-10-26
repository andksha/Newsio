<?php

namespace Newsio\UseCase;

use Illuminate\Database\Eloquent\Model;
use Newsio\Boundary\CategoryBoundary;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\LinkBoundary;
use Newsio\Boundary\TagBoundary;
use Newsio\Boundary\TitleBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Event;

class EditEventUseCase
{
    /**
     * @param IdBoundary $id
     * @param TitleBoundary $title
     * @param TagBoundary $tag
     * @param LinkBoundary $link
     * @param CategoryBoundary $category
     * @return Event|Model
     * @throws ModelNotFoundException
     */
    public function edit(IdBoundary $id, TitleBoundary $title, TagBoundary $tag, LinkBoundary $link, CategoryBoundary $category): Event
    {
        if (!$event = Event::query()->find($id->getValue())) {
            throw new ModelNotFoundException('Event');
        }

        $event->update([
            'title'    => $title->getValue(),
            'tag'      => $tag->getValue(),
            'link'     => $link->getValue(),
            'category' => $category->getValue(),
        ]);

        return $event;
    }
}
