<?php

namespace Newsio\UseCase;

use Newsio\Boundary\CategoryBoundary;
use Newsio\Boundary\LinkBoundary;
use Newsio\Boundary\TagBoundary;
use Newsio\Boundary\TitleBoundary;
use Newsio\Model\Event;

class CreateEventUseCase
{
    /**
     * @param TitleBoundary $title
     * @param TagBoundary $tag
     * @param LinkBoundary $link
     * @param CategoryBoundary $category
     * @return Event
     */
    public function create(TitleBoundary $title, TagBoundary $tag, LinkBoundary $link, CategoryBoundary $category): Event
    {
        if (!$this->validateLink($link->getValue())) {
            return;
        }

        $event = new Event();
        $event->fill([
            'title'    => $title->getValue(),
            'tag'      => $tag->getValue(),
            'link'     => $link->getValue(),
            'category' => $category->getValue(),
        ]);
        $event->save();

        return $event;
    }

    //TODO: validate link
    private function validateLink(string $link): bool
    {
        if (!true) {
            return false;
        }

        return true;
    }
}
