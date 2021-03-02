<?php

namespace Newsio\EventHandler;

use Newsio\Contract\OperationEvent;
use Newsio\Model\Operation;

final class OperationHandler
{
    public function handle(OperationEvent $event)
    {
        $operation = new Operation();
        $operation->operation_type = $event->operationType();
        $operation->model_type = $event->modelType();
        $operation->model_id = $event->modelId();
        $operation->model = $event->model();
        $operation->save();
    }
}