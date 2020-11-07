<?php

namespace Newsio\UseCase\Website;

use Carbon\Carbon;
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
        $existingWebsite = Website::query()->where('domain', 'like', '%' . $domain->getValue() . '%')->first();

        if ($existingWebsite) {
            if ($existingWebsite->approved === true || $existingWebsite->approved === null) {
                throw new AlreadyExistsException('Website ' . $domain->getValue() . ' is already ' . $existingWebsite->getStatus(), [
                    'website' => $existingWebsite
                ]);
            } elseif ($existingWebsite->approved === false && $existingWebsite->updated_at > Carbon::now()->subMonth()) {
                throw new AlreadyExistsException('
            Website ' . $domain->getValue() . ' is already ' . $existingWebsite->getStatus() . '.
             You can apply again in not less than a month since last application.', [
                    'website' => $existingWebsite
                ]);
            }
        }

        $website = new Website();
        $website->domain = $domain->getValue();
        $website->save();

        return $website;
    }
}