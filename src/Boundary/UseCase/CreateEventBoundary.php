<?php

namespace Newsio\Boundary\UseCase;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\LinksBoundary;
use Newsio\Boundary\TagsBoundary;
use Newsio\Boundary\TitleBoundary;

final class CreateEventBoundary
{
    private TitleBoundary $title;
    private TagsBoundary $tags;
    private LinksBoundary $links;
    private IdBoundary $category;
    private IdBoundary $userId;

    /**
     * CreateEventBoundary constructor.
     * @param $title
     * @param $tags
     * @param $links
     * @param $category
     * @param $userId
     * @throws \Newsio\Exception\BoundaryException
     */
    public function __construct($title, $tags, $links, $category, $userId)
    {
        $this->title = new TitleBoundary($title);
        $this->tags = new TagsBoundary($tags);
        $this->links = new LinksBoundary($links);
        $this->category = new IdBoundary($category);
        $this->userId = new IdBoundary($userId);
    }

    public function getTitle(): TitleBoundary
    {
        return $this->title;
    }


    public function getTags(): TagsBoundary
    {
        return $this->tags;
    }

    public function getLinks(): LinksBoundary
    {
        return $this->links;
    }


    public function getCategory(): int
    {
        return $this->category->getValue();
    }

    public function getUserId(): int
    {
        return $this->userId->getValue();
    }


}