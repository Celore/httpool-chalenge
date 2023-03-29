<?php

namespace App\Application\Dto\Book;

class BookListItemDto implements FormatInterface
{
    /**
     * @param string $title
     * @param string[] $authors
     * @param \App\Application\Dto\Book\BookListItemDetailsDto[] $details
     */
    public function __construct(
        public readonly string $title,
        public readonly array  $authors,
        public readonly array  $details
    )
    {
    }
}