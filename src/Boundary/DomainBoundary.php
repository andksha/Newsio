<?php

namespace Newsio\Boundary;

use Newsio\Exception\BoundaryException;

class DomainBoundary
{
    private string $domain;
    public const DOMAIN_REGEX = '/^https:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\/?$/';

    /**
     * WebsiteBoundary constructor.
     * @param $domain
     * @throws BoundaryException
     */
    public function __construct($domain)
    {
        if (!is_string($domain) || $domain === '' || !preg_match(self::DOMAIN_REGEX, $domain)) {
            throw new BoundaryException('Invalid domain format', ['domain' => 'Website is invalid']);
        }

        $this->domain = $domain;
    }

    public function getValue(): string
    {
        return $this->domain;
    }
}