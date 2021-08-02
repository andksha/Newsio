<?php

namespace App\GraphQL\Error;

use Closure;
use GraphQL\Error\Error;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Nuwave\Lighthouse\Execution\ErrorHandler;

final class NewsioGraphqlErrorHandler implements ErrorHandler
{
    public function __invoke(?Error $error, Closure $next): ?array
    {
        $previous = $error->getPrevious();
        if ($previous instanceof RendersErrorsExtensions) {

            $error = new Error(
                $previous->getMessage(),
                null,
                null,
                [],
                null,
                null,
                array_merge(
                    [
                        'category' => $previous->getCategory()
                    ],
                    $previous->extensionsContent()
                )
            );

            return $next($error);
        }

        return $next($error);
    }
}