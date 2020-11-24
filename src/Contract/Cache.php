<?php

namespace Newsio\Contract;

use Closure;

interface Cache
{
    public function hremember(string $key, Closure $closure);
    public function remember(string $key, Closure $closure);
}