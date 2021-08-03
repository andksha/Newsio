<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

interface GraphQLRequest
{
    public function getRootValue();

    public function getArgs();

    public function getContext(): GraphQLContext;

    public function getResolveInfo(): ResolveInfo;

    public function setRootValue($rootValue): void;

    public function setArgs($args): void;

    public function setContext(GraphQLContext $context): void;

    public function setResolveInfo(ResolveInfo $resolveInfo): void;

}