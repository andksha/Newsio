<?php

namespace Newsio\Boundary;

final class UserIdentifierBoundary
{
    private StringBoundary $ip;
    private StringBoundary $userAgent;

    public function __construct(StringBoundary $ip, StringBoundary $userAgent)
    {
        $this->ip = $ip;
        $this->userAgent = $userAgent;
    }

    public function getValue()
    {
        return $this->ip->getValue() . ' ' . $this->userAgent->getValue();
    }
}