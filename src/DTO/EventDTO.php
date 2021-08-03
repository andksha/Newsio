<?php

namespace Newsio\DTO;

use Newsio\Boundary\LinksBoundary;
use Newsio\Boundary\TagsBoundary;
use Newsio\Lib\Validation\Validator;

final class EventDTO implements DTO
{
    private string $title;

    private array $tags;

    private array $links;

    private int $category;

    /**
     * EventDTO constructor.
     * @param array $data
     * @param Validator $validator
     * @throws \Newsio\Lib\Validation\ValidationException
     */
    public function __construct(array $data, Validator $validator)
    {
        $validatedData = $validator->validate($data, $this->rules());

        $this->title = $validatedData['title'];
        $this->tags = $validatedData['tags'];
        $this->links = $validatedData['links'];
        $this->category = $validatedData['category'];
    }

    public function rules(): array
    {
        return [
            'title'    => ['required', 'string'],
            'tags'     => ['required', 'array',],
            'tags.*'   => ['required', 'regex:' . TagsBoundary::TAG_REGEX],
            'links'    => ['required', 'array'],
            'links.*'  => ['required', 'regex:' . LinksBoundary::LINK_REGEX],
            'category' => ['required', 'integer'],
        ];
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function getCategory(): int
    {
        return $this->category;
    }



}