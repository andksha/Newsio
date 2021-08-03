<?php

namespace App\GraphQL\Mutations\User;

use App\GraphQL\DefaultGraphQLRequest;
use App\GraphQL\Error\GraphqlError;
use App\GraphQL\GraphQLRequest;
use App\GraphQL\Middleware\AddHeadersMiddleware;
use App\GraphQL\Middleware\StartMiddleware;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
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

    private StartMiddleware $startMiddleware;

    public function __construct()
    {
        $this->loginUseCase = new LoginUseCase();
        $this->validatorFactory = new ValidatorFactory();

        $this->startMiddleware = new StartMiddleware();
        $this->startMiddleware->addNext(new AddHeadersMiddleware());
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
        return $this->startMiddleware->resolve(
            new DefaultGraphQLRequest($rootValue, $args, $context, $resolveInfo),
            function (GraphQLRequest $request) {
                $args = $request->getArgs();
                $dto = new LoginDTO($args['dto'] ?? [], $this->validatorFactory->make());

                Log::info(print_r($args, true));

                try {
                    $token = $this->loginUseCase->login(new EmailBoundary($dto->getEmail()), new PasswordBoundary($dto->getPassword()), 'api');
                } catch (InvalidDataException $e) {
                    throw new GraphqlError($e->getMessage());
                }

                return $token->jsonSerialize();
            }
        );
    }
}