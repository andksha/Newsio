<?php

namespace Newsio\Contract;

use Closure;

interface Cache
{
    public function hremember(string $pluralKey, string $key, Closure $closure, int $ttl = 3600);
    public function remember(string $key, Closure $closure, int $ttl = 3600);
}