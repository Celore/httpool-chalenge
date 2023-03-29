<?php

namespace App\Application\Dto\Book;

class BookListItemDetailsDto
{
    /**
     * @param string $isbn
     * @param string|null $publishDate
     * @param string|null $physicalFormat
     * @param int|null $numberOfPages
     * @param string|null $weight
     * @param \App\Application\Dto\Book\ImageInterface $image
     */
    public function __construct(
        public readonly string         $isbn,
        public readonly ?string        $publishDate,
        public readonly ?string        $physicalFormat,
        public readonly ?int           $numberOfPages,
        public readonly ?string        $weight,
        public readonly ImageInterface $image
    )
    {
    }
}