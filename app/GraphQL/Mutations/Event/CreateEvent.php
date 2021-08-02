<?php

namespace App\GraphQL\Mutations\Event;

use App\GraphQL\Error\GraphqlError;
use GraphQL\Type\Definition\ResolveInfo;
use Newsio\Boundary\UseCase\CreateEventBoundary;
use Newsio\Contract\ApplicationException;
use Newsio\DTO\EventDTO;
use Newsio\Factory\ValidatorFactory;
use Newsio\UseCase\CreateEventUseCase;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class CreateEvent
{
    private CreateEventUseCase $createEventUseCase;

    private ValidatorFactory $validatorFactory;

    public function __construct()
    {
        $this->createEventUseCase = new CreateEventUseCase();
        $this->validatorFactory = new ValidatorFactory();
    }

    /**
     * @param $rootValue
     * @param $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @throws \Newsio\Lib\Validation\ValidationException
     * @throws GraphqlError
     * @throws \Newsio\Exception\BoundaryException
     */
    public function resolve($rootValue, $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $dto = new EventDTO($args['dto'] ?? [], $this->validatorFactory->make());

        try {
            $event = $this->createEventUseCase->create(new CreateEventBoundary(
                $dto->getTitle(),
                $dto->getTags(),
                $dto->getLinks(),
                $dto->getCategory(),
                auth()->id(),
            ));
        } catch (ApplicationException $e) {
            throw new GraphqlError($e->getMessage());
        }

        return $event->jsonSerialize();
    }
}