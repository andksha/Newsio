<?php

namespace Tests\Unit\Boundary;

use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

class EmailBoundaryTest extends BaseTestCase
{
    public function test_Boundary_WithValidEmail_ReturnsEmail()
    {
        $email = new EmailBoundary('test@test.test');
        $this->assertEquals('test@test.test', $email->getValue());
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithInvalidType_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new EmailBoundary([]);
    }

    /**
     * @throws BoundaryException
     */
    public function test_Boundary_WithInvalidFormat_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new EmailBoundary('test@tes.t');
    }
}