<?php

namespace Newsio\Contract;

interface OperationEvent
{
    public function operationType(): int;

    public function modelType(): int;

    public function modelId(): int;

    public function model(): string;
}