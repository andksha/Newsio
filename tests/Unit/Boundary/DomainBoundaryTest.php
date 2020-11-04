<?php

namespace Tests\Unit\Boundary;

use Newsio\Boundary\DomainBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class DomainBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithValidDomain_ReturnsDomain()
    {
        $domain = new DomainBoundary('https://specprom-kr.com.ua/');

        $this->assertEquals('https://specprom-kr.com.ua/', $domain->getValue());
    }

    public function test_Boundary_WithInvalidProtocol_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new DomainBoundary('http://test.com');
    }

    public function test_Boundary_WithInvalidFormat_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new DomainBoundary('http://test.com/test');
    }
}