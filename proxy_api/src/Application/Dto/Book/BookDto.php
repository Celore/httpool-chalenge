<?php

namespace App\Application\Dto\Book;

class BookDto implements FormatInterface
{
    /**
     * @param string $title
     * @param string[] $authors
     * @param string|null $publishDate
     * @param string|null $physicalFormat
     * @param \App\Application\Dto\Book\ImageInterface $image
     */
    public function __construct(
        public readonly string         $title,
        public readonly array          $authors,
        public readonly ?string        $publishDate,
        public readonly ?string        $physicalFormat,
        public readonly ImageInterface $image
    )
    {
    }
}