<?php

namespace Newsio\Lib\Validation;

interface Validator
{
    /**
     * @param array $data
     * @param array $rules
     * @return array
     * @throws ValidationException
     */
    public function validate(array $data, array $rules): array;
}