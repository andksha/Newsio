<?php

namespace Newsio\Boundary\UseCase;

use Newsio\Exception\BoundaryException;

abstract class BaseUseCaseBoundary
{
    protected array $boundaries = [];
    protected array $errors;
    protected array $instances = [];
    public const MESSAGE = 'validation error';

    /**
     * DefaultUseCaseBoundary constructor.
     * @param array $input
     * @throws BoundaryException
     */
    public function __construct(array $input)
    {
        foreach ($input as $key => $value) {
            try {
                if (!isset($this->boundaries[$key])) {
                    throw new BoundaryException("Invalid key", $key);
                }
                $this->instances[$key] = new $this->boundaries[$key]($value);
            } catch (BoundaryException $e) {
                $this->errors[$key] = $e->getMessage();
            }
        }
        if (!empty($this->errors)) {
            throw new BoundaryException(self::MESSAGE, $this->errors);
        }
    }

    protected function getString(string $key): ?string
    {
        return array_key_exists($key, $this->instances) ? $this->instances[$key]->getValue() : '';
    }

    protected function getInt(string $key): ?int
    {
        return array_key_exists($key, $this->instances) ? $this->instances[$key]->getValue() : 0;
    }
}