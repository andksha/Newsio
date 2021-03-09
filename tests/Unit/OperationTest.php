<?php

namespace Tests\Unit;


use Illuminate\Foundation\Testing\TestCase;
use Newsio\Model\Operation;

final class OperationTest
{
    private TestCase $testCase;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function assertOperationsCount(string $operationType, string $modelType, int $count)
    {
        $this->testCase->assertEquals($count,
            Operation::query()
            ->where('operation_type', $operationType)
            ->where('model_type', $modelType)
            ->count()
        );
    }
}