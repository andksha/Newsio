<?php

namespace Tests\Unit\Boundary;

use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class PasswordBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithValidPassword_ReturnsPassword()
    {
        $password = new PasswordBoundary('test1234');
        $this->assertEquals('test1234', $password->getValue());
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithInvalidType_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new PasswordBoundary([]);
    }

    public function test_Boundary_WithInvalidFormat_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new PasswordBoundary('test123');
    }
}