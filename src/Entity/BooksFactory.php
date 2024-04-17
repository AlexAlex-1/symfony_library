<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Class BooksFactory
 */
final class BooksFactory
{
    /**
     * @return Books
     */
    final public function create(): Books
    {
        return new Books();
    }
}
