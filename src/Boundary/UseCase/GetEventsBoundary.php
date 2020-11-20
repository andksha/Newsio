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
    private NullableIntBoundary $currentPage;

    /**
     * GetEventsBoundary constructor.
     * @param array $input
     * @throws \Newsio\Exception\BoundaryException
     */
    public function __construct(array $input)
    {
        $this->search = new NullableStringBoundary($input['search'] ?? null);
        $this->tag = new NullableStringBoundary($input['tag'] ?? null);
        $this->removed = new NullableStringBoundary($input['removed'] ?? null);
        $this->category = new NullableIntBoundary($input['category'] ?? null);
        $this->userId = new NullableIntBoundary($input['user_id'] ?? null);
        $this->currentPage = new NullableIntBoundary($input['page'] ?? 1);
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

    public function getCurrentPage(): int
    {
        return $this->currentPage->getValue();
    }
}