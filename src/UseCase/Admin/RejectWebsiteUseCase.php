<?php

namespace Newsio\UseCase\Admin;

use Newsio\Boundary\IdBoundary;
use Newsio\Boundary\StringBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Website;

class RejectWebsiteUseCase
{
    /**
     * @param IdBoundary $id
     * @param StringBoundary $reason
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|Website|Website[]|null
     * @throws ModelNotFoundException
     */
    public function reject(IdBoundary $id, StringBoundary $reason)
    {
        if (!$website = Website::query()->status('pending')->find($id->getValue())) {
            throw new ModelNotFoundException('Website');
        }

        $website->approved = false;
        $website->reason = $reason->getValue();
        $website->save();

        return $website;
    }
}