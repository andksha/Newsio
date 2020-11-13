<?php

namespace Newsio\Boundary\UseCase;

use Newsio\Boundary\NullableIntBoundary;
use Newsio\Boundary\NullableStringBoundary;

final class GetEventsBoundary
{
    private NullableStringBoundary $search;
    private NullableStringBoundary $tag;
    private NullableStringBoundary $removed;
    private NullableIntBoundary $category;
    private NullableIntBoundary $userId;

    /**
     * GetEventsBoundary constructor.
     * @param $search
     * @param $tag
     * @param $removed
     * @param $category
     * @param $userId
     * @throws \Newsio\Exception\BoundaryException
     */
    public function __construct($search, $tag, $removed, $category, $userId)
    {
        $this->search = new NullableStringBoundary($search);
        $this->tag = new NullableStringBoundary($tag);
        $this->removed = new NullableStringBoundary($removed);
        $this->category = new NullableIntBoundary($category);
        $this->userId = new NullableIntBoundary($userId);
    }

    public function getSearch(): ?string
    {
        return $this->search->getValue();
    }

    public function getTag(): ?string
    {
        return $this->tag->getValue();
    }

    public function getRemoved(): ?string
    {
        return $this->removed->getValue();
    }

    public function getCategory(): ?int
    {
        return $this->category->getValue();
    }

    public function getUserId(): ?int
    {
        return $this->userId->getValue();
    }

}