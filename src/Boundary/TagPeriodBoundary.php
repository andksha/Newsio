<?php

namespace Newsio\Boundary;

use Carbon\Carbon;
use Newsio\Exception\BoundaryException;

final class TagPeriodBoundary
{
    private string $period;

    private const PERIOD_DAY = 'day';
    private const PERIOD_WEEK = 'week';
    private const PERIOD_MONTH = 'month';
    private const PERIOD_YEAR = 'year';


    /**
     * TagPeriodBoundary constructor.
     * @param string $period
     * @throws BoundaryException
     */
    public function __construct(string $period)
    {
        if (!$this->allowedPeriod($period)) {
            throw new BoundaryException("Period $period is not allowed");
        }

        $this->period = $period;
    }

    private function allowedPeriod($period)
    {
        return in_array($period, [
            self::PERIOD_DAY, self::PERIOD_WEEK, self::PERIOD_MONTH, self::PERIOD_YEAR
        ]);
    }

    /**
     * @return Carbon
     * @throws BoundaryException
     */
    public function getValue(): Carbon
    {
        if ($this->period === self::PERIOD_DAY) {
            $carbon = Carbon::now()->startOfDay();
        } elseif ($this->period === self::PERIOD_WEEK) {
            $carbon = Carbon::now()->subWeek();
        } elseif ($this->period === self::PERIOD_MONTH) {
            $carbon = Carbon::now()->subMonth()->startOfMonth();
        } else {
            $carbon = Carbon::now()->subYear()->startOfYear();
        }

        return $carbon;
    }
}