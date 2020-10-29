<?php

namespace Newsio\Boundary;

use Newsio\Exception\BoundaryException;

class CategoryBoundary
{
    private int $category;

    /**
     * LinkBoundary constructor.
     * @param $category
     * @throws BoundaryException
     *
     * @TODO: better tag checking
     */
    public function __construct($category)
    {
        if (!is_int((int)$category)) {
            throw new BoundaryException('Category is invalid', ['category' => 'Category is invalid']);
        }

        $this->category = $category;
    }

    public function getValue(): int
    {
        return $this->category;
    }
}
