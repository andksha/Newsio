<?php

namespace Newsio\DTO;

use JsonSerializable;

final class TokenDTO implements DTO, JsonSerializable
{
    private string $token;
    private string $type;
    private string $expiresIn;

    public const TYPE_BEARER = 'bearer';

    public function __construct(string $token, string $type, string $expiresIn)
    {
        $this->token = $token;
        $this->type = $type;
        $this->expiresIn = $expiresIn;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'type' => ['required', 'string'],
            'expires_in' => ['required', 'numeric'],
        ];
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getExpiresIn(): string
    {
        return $this->expiresIn;
    }

    public function jsonSerialize(): array
    {
        return [
            'token' => $this->getToken(),
            'type' => $this->getType(),
            'expires_in' => $this->getExpiresIn(),
        ];
    }
}