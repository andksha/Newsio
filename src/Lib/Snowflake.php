<?php

namespace Newsio\Lib;

final class Snowflake
{
    const MAX_TIMESTAMP_LENGTH = 41;

    const MAX_DATACENTER_LENGTH = 5;

    const MAX_WORKER_LENGTH = 5;

    const MAX_SEQUENCE_LENGTH = 12;

    const MAX_FIRST_LENGTH = 1;

    private int $datacenter;

    private int $worker;

    public function __construct(int $datacenter = null, int $worker = null)
    {
//        dd(log(41, 2));
        $maxDataCenter = -1 ^ (-1 << self::MAX_DATACENTER_LENGTH); // 31
        $maxWorker = -1 ^ (-1 << self::MAX_WORKER_LENGTH); // 31

        $this->datacenter = $datacenter > $maxDataCenter
            || $datacenter < 0
            || is_null($datacenter)
            ? mt_rand(0, $maxDataCenter) : $datacenter;

        $this->worker = $worker > $maxWorker
            || $worker < 0
            || is_null($worker)
            ? mt_rand(0, $maxWorker) : $worker;
    }

    public function id(): string
    {
        // trims decimal places
        $currentTime = floor(microtime(true) * 1000) | 0;
        $sequence = mt_rand(0, -1 ^ (-1 << self::MAX_SEQUENCE_LENGTH)); // 4095

        $workerLeftMoveLength = self::MAX_SEQUENCE_LENGTH;
        $datacenterLeftMoveLength = $workerLeftMoveLength + self::MAX_WORKER_LENGTH;
        $currentTimeLeftMoveLength = $datacenterLeftMoveLength + self::MAX_DATACENTER_LENGTH;

        return (string) (($currentTime - strtotime('2019-08-08 08:08:08') * 1000) << $currentTimeLeftMoveLength)
            | ($this->datacenter << $datacenterLeftMoveLength)
            | ($this->worker << $workerLeftMoveLength)
            | ($sequence);
    }
}