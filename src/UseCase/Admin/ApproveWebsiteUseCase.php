<?php

namespace Newsio\UseCase\Admin;

use Newsio\Boundary\IdBoundary;
use Newsio\Exception\ModelNotFoundException;
use Newsio\Model\Website;

class ApproveWebsiteUseCase
{
    /**
     * @param IdBoundary $id
     * @return Website
     * @throws ModelNotFoundException
     */
    public function approve(IdBoundary $id): Website
    {
        if (!$website = Website::query()->status('pending')->find($id->getValue())) {
            throw new ModelNotFoundException('Website');
        }

        $website->approved = true;
        $website->save();

        return $website;
    }
}