<?php

namespace App\Event;

use Newsio\Contract\OperationEvent;

abstract class BaseOperationEvent implements OperationEvent
{
    protected int $operationType;
    protected int $modelType;
    protected int $modelId;
    protected string $model;

    public function operationType(): int
    {
        return $this->operationType;
    }

    public function modelType(): int
    {
        return $this->modelType;
    }

    public function modelId(): int
    {
        return $this->modelId;
    }

    public function model(): string
    {
        return $this->model;
    }

}