<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class DefaultGraphQLRequest implements GraphQLRequest
{
    private $rootValue;
    private $args;
    private GraphQLContext $context;
    private ResolveInfo $resolveInfo;

    public function __construct($rootValue, $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->rootValue = $rootValue;
        $this->args = $args;
        $this->context = $context;
        $this->resolveInfo = $resolveInfo;
    }

    public function getRootValue()
    {
        return $this->rootValue;
    }

    public function getArgs()
    {
        return $this->args;
    }

    public function getContext(): GraphQLContext
    {
        return $this->context;
    }

    public function getResolveInfo(): ResolveInfo
    {
        return $this->resolveInfo;
    }

    public function setRootValue($rootValue): void
    {
        $this->rootValue = $rootValue;
    }

    public function setArgs($args): void
    {
        $this->args = $args;
    }

    public function setContext(GraphQLContext $context): void
    {
        $this->context = $context;
    }

    public function setResolveInfo(ResolveInfo $resolveInfo): void
    {
        $this->resolveInfo = $resolveInfo;
    }
}