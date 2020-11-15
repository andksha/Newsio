<?php

namespace Newsio\Boundary\UseCase;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\NullableStringBoundary;

final class GetProfileBoundary
{
    private GetEventsBoundary $getEventsBoundary;
    private IdBoundary $userId;
    private NullableStringBoundary $saved;

    /**
     * GetProfileBoundary constructor.
     * @param GetEventsBoundary $getEventsBoundary
     * @param array $input
     * @throws \Newsio\Exception\BoundaryException
     */
    public function __construct(GetEventsBoundary $getEventsBoundary, array $input)
    {
        $this->getEventsBoundary = $getEventsBoundary;
        $this->userId = new IdBoundary($input['user_id'] ?? null);
        $this->saved = new NullableStringBoundary($input['saved'] ?? null);
    }

    public function getEventsBoundary(): GetEventsBoundary
    {
        return  $this->getEventsBoundary;
    }

    public function getUserId(): int
    {
        return $this->userId->getValue();
    }

    public function getSaved(): ?string
    {
        return $this->saved->getValue();
    }
}