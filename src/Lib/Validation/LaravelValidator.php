<?php

namespace Newsio\Lib\Validation;

final class LaravelValidator implements Validator
{
    public function validate(array $data, array $rules): array
    {
        $validator = \Illuminate\Support\Facades\Validator::make($data, $rules);

        try {
            return $validator->validated();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw new LaravelValidationException($e);
        }
    }
}