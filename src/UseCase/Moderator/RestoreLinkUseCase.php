<?php

namespace Newsio\UseCase\Moderator;

use Newsio\Boundary\IdBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Link;

class RestoreLinkUseCase
{
    /**
     * @param IdBoundary $id
     * @return \Illuminate\Database\Query\Builder|mixed|Link
     * @throws ModelNotFoundException
     */
    public function restore(IdBoundary $id)
    {
        if (!$link = Link::query()->onlyTrashed()->find($id->getValue())) {
            throw new ModelNotFoundException('Link');
        }

        $link->restore();

        return $link;
    }
}