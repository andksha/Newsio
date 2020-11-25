<?php

namespace Tests\Unit\Boundary;

use Carbon\Carbon;
use Newsio\Boundary\TagPeriodBoundary;
use Newsio\Exception\BoundaryException;
use Tests\BaseTestCase;

final class TagPeriodBoundaryTest extends BaseTestCase
{
    /**
     * @throws \Newsio\Exception\BoundaryException
     */
    public function test_Boundary_WithDayPeriod_ReturnsStartOfDay()
    {
        $tagPeriodBoundary = new TagPeriodBoundary('day');
        $this->assertEquals(Carbon::now()->startOfDay(), $tagPeriodBoundary->getValue());
        $this->assertEquals('day', $tagPeriodBoundary->getString());
    }

    /**
     * @throws \Newsio\Exception\BoundaryException
     */
    public function test_Boundary_WithWeekPeriod_ReturnsSubWeek()
    {
        $tagPeriodBoundary = new TagPeriodBoundary('week');
        $this->assertEquals(Carbon::now()->subWeek()->format('Y-m-d'), $tagPeriodBoundary->getValue()->format('Y-m-d'));
    }

    /**
     * @throws \Newsio\Exception\BoundaryException
     */
    public function test_Boundary_WithMonthPeriod_ReturnsStartOfMonth()
    {
        $tagPeriodBoundary = new TagPeriodBoundary('month');
        $this->assertEquals(Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'), $tagPeriodBoundary->getValue()->format('Y-m-d'));
    }

    /**
     * @throws \Newsio\Exception\BoundaryException
     */
    public function test_Boundary_WithYearPeriod_ReturnsStartOfYear()
    {
        $tagPeriodBoundary = new TagPeriodBoundary('year');
        $this->assertEquals(Carbon::now()->subYear()->startOfYear()->format('Y-m-d'), $tagPeriodBoundary->getValue()->format('Y-m-d'));
    }

    /**
     * @throws \Newsio\Exception\BoundaryException
     */
    public function test_Boundary_WithInvalidPeriod_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TagPeriodBoundary('halfmonth');
    }

    /**
     * @throws \Newsio\Exception\BoundaryException
     */
    public function test_Boundary_WithNullPeriod_ThrowsBoundaryException()
    {
        $this->expectException(BoundaryException::class);
        new TagPeriodBoundary(null);
    }
}