<?php

namespace Newsio\UseCase;

use Illuminate\Database\Eloquent\Model;
use Newsio\Boundary\CategoryBoundary;
use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Boundary\TagsBoundary;
use Newsio\Boundary\TitleBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Event;

class EditEventUseCase
{
    /**
     * @param IdBoundary $id
     * @param TitleBoundary $title
     * @param TagsBoundary $tag
     * @param LinksBoundary $link
     * @param CategoryBoundary $category
     * @return Event|Model
     * @throws ModelNotFoundException
     */
    public function edit(IdBoundary $id, TitleBoundary $title, TagsBoundary $tag, LinksBoundary $link, CategoryBoundary $category): Event
    {
        if (!$event = Event::query()->find($id->getValue())) {
            throw new ModelNotFoundException('Event');
        }

        $event->update([
            'title'    => $title->getValue(),
            'tag'      => implode(' ', $tag->getValues()),
            'link'     => implode(' ', $link->getValues()),
            'category' => $category->getValue(),
        ]);

        return $event;
    }
}
