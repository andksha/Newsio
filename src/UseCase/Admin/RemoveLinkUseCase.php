<?php

namespace Newsio\UseCase\Admin;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Link;

class RemoveLinkUseCase
{
    /**
     * @param IdBoundary $id
     * @param StringBoundary $reason
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function remove(IdBoundary $id, StringBoundary $reason)
    {
        if (!$link = Link::query()->find($id->getValue())) {
            throw new ModelNotFoundException('Event');
        }

        $link->remove($reason->getValue());

        return $link;
    }
}