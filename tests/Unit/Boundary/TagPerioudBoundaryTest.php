<?php

namespace Tests\Unit\Boundary;

use Carbon\Carbon;
use Newsio\Boundary\TagPeriodBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

final class TagPerioudBoundaryTest extends BaseTestCase
{
    /**
     * @throws \Newsio\Exception\BoundaryException
     */
    public function test_Boundary_WithDayPeriod_ReturnsStartOfDay()
    {
        $boundary = new TagPeriodBoundary('day');
        $this->assertEquals(Carbon::now()->startOfDay(), $boundary->getValue());
    }

    /**
     * @throws \Newsio\Exception\BoundaryException
     */
    public function test_Boundary_WithWeekPeriod_ReturnsSubWeek()
    {
        $boundary = new TagPeriodBoundary('week');
        $this->assertEquals(Carbon::now()->subWeek()->format('Y-m-d'), $boundary->getValue()->format('Y-m-d'));
    }

    /**
     * @throws \Newsio\Exception\BoundaryException
     */
    public function test_Boundary_WithMonthPeriod_ReturnsStartOfMonth()
    {
        $boundary = new TagPeriodBoundary('month');
        $this->assertEquals(Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'), $boundary->getValue()->format('Y-m-d'));
    }

    /**
     * @throws \Newsio\Exception\BoundaryException
     */
    public function test_Boundary_WithYearPeriod_ReturnsStartOfYear()
    {
        $boundary = new TagPeriodBoundary('year');
        $this->assertEquals(Carbon::now()->subYear()->startOfYear()->format('Y-m-d'), $boundary->getValue()->format('Y-m-d'));
    }

    /**
     * @throws \Newsio\Exception\BoundaryException
     */
    public function test_Boundary_WithInvalidPeriod_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TagPeriodBoundary('halfmonth');
    }
}