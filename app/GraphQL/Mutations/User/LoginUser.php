<?php

namespace App\GraphQL\Mutations\User;

use App\GraphQL\Error\GraphqlError;
use GraphQL\Type\Definition\ResolveInfo;
use Newsio\Boundary\Auth\EmailBoundary;
use Newsio\Boundary\Auth\PasswordBoundary;
use Newsio\DTO\LoginDTO;
use Newsio\Exception\InvalidDataException;
use Newsio\Factory\ValidatorFactory;
use Newsio\UseCase\Auth\LoginUseCase;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class LoginUser
{
    private LoginUseCase $loginUseCase;

    private ValidatorFactory $validatorFactory;

    public function __construct()
    {
        $this->loginUseCase = new LoginUseCase();
        $this->validatorFactory = new ValidatorFactory();
    }

    /**
     * @param $rootValue
     * @param $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @throws \Newsio\Lib\Validation\ValidationException
     * @throws GraphqlError
     */
    public function resolve($rootValue, $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $dto = new LoginDTO($args['dto'] ?? [], $this->validatorFactory->make());

        try {
            $token = $this->loginUseCase->login(new EmailBoundary($dto->getEmail()), new PasswordBoundary($dto->getPassword()), 'api');
        } catch (InvalidDataException $e) {
            throw new GraphqlError($e->getMessage());
        }

        return $token->jsonSerialize();
    }
}