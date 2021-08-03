<?php

namespace Newsio\DTO;

use Newsio\Lib\Validation\Validator;

final class LoginDTO implements DTO
{
    private string $email;

    private string $password;


    /**
     * UserDTO constructor.
     * @param array $data
     * @param Validator $validator
     * @throws \Newsio\Lib\Validation\ValidationException
     */
    public function __construct(array $data, Validator $validator)
    {
        $validatedData = $validator->validate($data, $this->rules());

        $this->email = $validatedData['email'];
        $this->password = $validatedData['password'];
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'between:8,32'],
        ];
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}