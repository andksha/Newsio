<?php

namespace Newsio\UseCase\Website;

use Newsio\Boundary\DomainBoundary;
use Newsio\Exception\AlreadyExistsException;
use Newsio\Model\Website;

class ApplyWebsiteUseCase
{
    /**
     * @param DomainBoundary $domain
     * @return Website
     * @throws AlreadyExistsException
     */
    public function apply(DomainBoundary $domain)
    {
        if ($existingWebsite = Website::query()->where('domain', $domain->getValue())->first()) {
            throw new AlreadyExistsException('Website ' . $domain->getValue() . 'already exists', [
                'website' => $existingWebsite
            ]);
        }

        $website = new Website();
        $website->domain = $domain->getValue();
        $website->save();

        return $website;
    }
}